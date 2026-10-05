<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Billet;
use App\Models\Commande;
use App\Models\Evenement;
use App\Models\Paiement;
use App\Models\TypeBillet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PanierController extends Controller
{
    public function index()
    {
        $panier = session('panier', []);
        $montantTotal = session('montant_total', 0);

        return view('client.panier.index', compact('panier', 'montantTotal'));
    }

    public function ajouter(Request $request)
    {
        $request->validate([
            'evenement_id' => 'required|exists:evenements,id',
            'quantite' => 'required|array',
            'quantite.*' => 'required|integer|min:0',
        ]);

        $evenement = Evenement::with('typesBillets')->findOrFail($request->evenement_id);
        $panier = [];
        $montantTotal = 0;

        foreach ($request->quantite as $typeNom => $quantite) {
            $quantite = intval($quantite);
            if ($quantite <= 0) {
                continue;
            }

            $typeBillet = $evenement->typesBillets->firstWhere('nom', $typeNom);
            if (!$typeBillet) {
                continue;
            }

            $disponible = max(0, $typeBillet->quantite - $typeBillet->quantite_vendue);
            if ($quantite > $disponible) {
                return back()->with('error', "Quantité non disponible pour le billet $typeNom. Disponible : $disponible.")->withInput();
            }

            $sousTotal = $typeBillet->prix * $quantite;
            $panier[] = [
                'evenement_id' => $evenement->id,
                'type_id' => $typeBillet->id,
                'type_nom' => $typeNom,
                'quantite' => $quantite,
                'prix' => $typeBillet->prix,
                'sous_total' => $sousTotal,
            ];

            $montantTotal += $sousTotal;
        }

        if (empty($panier)) {
            return back()->with('error', 'Veuillez sélectionner au moins un billet.')->withInput();
        }

        session([
            'panier' => $panier,
            'montant_total' => $montantTotal,
            'reference' => 'CLIENT-' . strtoupper(Str::random(8)),
            'nom_spectateur' => Auth::user()->prenom . ' ' . Auth::user()->nom,
            'email_spectateur' => Auth::user()->email,
        ]);

        return redirect()->route('client.panier.index')->with('success', 'Billets ajoutés au panier.');
    }

    public function modifierQuantite(Request $request, $index)
    {
        $request->validate([
            'quantite' => 'required|integer|min:1',
        ]);

        $panier = session('panier', []);

        if (!isset($panier[$index])) {
            return back()->with('error', 'Article de panier introuvable.');
        }

        $panier[$index]['quantite'] = $request->quantite;
        $panier[$index]['sous_total'] = $panier[$index]['prix'] * $request->quantite;

        $montantTotal = array_sum(array_column($panier, 'sous_total'));
        session(['panier' => $panier, 'montant_total' => $montantTotal]);

        return back()->with('success', 'Quantité mise à jour.');
    }

    public function supprimer($index)
    {
        $panier = session('panier', []);

        if (!isset($panier[$index])) {
            return back()->with('error', 'Élément de panier introuvable.');
        }

        unset($panier[$index]);
        $panier = array_values($panier);

        $montantTotal = array_sum(array_column($panier, 'sous_total'));

        session(['panier' => $panier, 'montant_total' => $montantTotal]);

        return back()->with('success', 'Article supprimé du panier.');
    }

    public function vider()
    {
        session()->forget(['panier', 'montant_total', 'reference', 'nom_spectateur', 'email_spectateur']);

        return back()->with('success', 'Panier vidé.');
    }

    public function paiement()
    {
        $panier = session('panier', []);
        $montantTotal = session('montant_total', 0);

        if (empty($panier)) {
            return redirect()->route('client.evenements.index')->with('error', 'Votre panier est vide.');
        }

        return view('client.panier.paiement', compact('panier', 'montantTotal'));
    }

    public function processPaiement(Request $request)
    {
        $request->validate([
            'methode_paiement' => 'required|in:carte,paypal,virement,especes',
            'carte_numero' => 'required_if:methode_paiement,carte|nullable|string|min:16|max:19',
            'carte_expiration' => ['required_if:methode_paiement,carte', 'nullable', 'string', 'regex:/^(0[1-9]|1[0-2])\/[0-9]{2}$/'],
            'carte_cvv' => 'required_if:methode_paiement,carte|nullable|string|min:3|max:4',
            'carte_titulaire' => 'required_if:methode_paiement,carte|nullable|string|max:255',
            'paypal_email' => 'required_if:methode_paiement,paypal|nullable|email|max:255',
            'virement_reference' => 'required_if:methode_paiement,virement|nullable|string',
            'especes_email' => 'required_if:methode_paiement,especes|nullable|email',
        ]);

        $panier = session('panier', []);
        $montantTotal = session('montant_total', 0);

        if (empty($panier)) {
            return redirect()->route('client.evenements.index')->with('error', 'Votre panier est vide.');
        }

        try {
            DB::beginTransaction();

            foreach ($panier as $item) {
                $typeBillet = TypeBillet::find($item['type_id']);
                if (!$typeBillet) {
                    throw new \Exception('Type de billet introuvable.');
                }
                $disponible = max(0, $typeBillet->quantite - $typeBillet->quantite_vendue);
                if ($item['quantite'] > $disponible) {
                    throw new \Exception("Stock insuffisant pour {$typeBillet->nom}");
                }
            }

            $commande = Commande::create([
                'reference' => session('reference'),
                'client_id' => Auth::id(),
                'evenement_id' => $panier[0]['evenement_id'],
                'date' => now(),
                'montant_total' => $montantTotal,
                'methode_paiement' => $request->methode_paiement,
                'email_spectateur' => session('email_spectateur'),
                'statut' => $request->methode_paiement == 'especes' ? 'En attente' : 'Confirmée',
                'notes' => json_encode(['methode' => $request->methode_paiement]),
            ]);

            $paiement = Paiement::create([
                'reference' => 'PAY-' . strtoupper(Str::random(12)),
                'commande_id' => $commande->id,
                'montant' => $montantTotal,
                'prestataire' => $this->getPrestataire($request->methode_paiement),
                'statut' => $request->methode_paiement == 'especes' ? 'En attente' : 'Payé',
                'date_paiement' => $request->methode_paiement != 'especes' ? now() : null,
                'reponse_prestataire' => json_encode($request->only(['methode_paiement', 'carte_numero', 'paypal_email', 'virement_reference', 'especes_email'])),
            ]);

            foreach ($panier as $item) {
                $typeBillet = TypeBillet::find($item['type_id']);
                for ($i = 0; $i < $item['quantite']; $i++) {
                    $codeUnique = $this->generateUniqueCode();
                    Billet::create([
                        'code_unique' => $codeUnique,
                        'qr_content' => $codeUnique,
                        'type_billet_id' => $typeBillet->id,
                        'commande_id' => $commande->id,
                        'evenement_id' => $item['evenement_id'],
                        'nom_spectateur' => session('nom_spectateur'),
                        'email_spectateur' => session('email_spectateur'),
                        'prix' => $typeBillet->prix,
                        'statut' => 'Valide',
                    ]);
                }
                $typeBillet->increment('quantite_vendue', $item['quantite']);
            }

            // Mettre à jour places vendues
            $evenement = Evenement::find($panier[0]['evenement_id']);
            if ($evenement) {
                $evenement->increment('places_vendues', array_sum(array_column($panier, 'quantite')));
            }

            session()->forget(['panier', 'montant_total', 'reference', 'nom_spectateur', 'email_spectateur']);

            DB::commit();

            return redirect()->route('client.panier.confirmation', $commande)->with('success', 'Paiement effectué avec succès !');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur paiement client', ['error' => $e->getMessage(), 'user_id' => Auth::id()]);

            return back()->with('error', 'Erreur lors du paiement : ' . $e->getMessage());
        }
    }

    public function confirmation(Commande $commande)
    {
        if ($commande->client_id !== Auth::id()) {
            abort(403);
        }

        $commande->load(['billets.evenement', 'paiement']);

        return view('client.panier.confirmation', compact('commande'));
    }

    private function getPrestataire($methode)
    {
        $prestataires = [
            'carte' => 'Stripe',
            'paypal' => 'PayPal',
            'virement' => 'Virement',
            'especes' => 'Espèces',
        ];

        return $prestataires[$methode] ?? 'Autre';
    }

    private function generateUniqueCode()
    {
        do {
            $code = 'TKT-' . strtoupper(Str::random(12));
        } while (Billet::where('code_unique', $code)->exists());

        return $code;
    }
}
