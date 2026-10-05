<?php
// app/Http/Controllers/Admin/EvenementController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use App\Models\User;
use App\Models\Lieu;
use App\Models\TypeBillet;
use App\Models\Commande;
use App\Models\Billet;
use App\Models\Paiement;
use App\Models\Notification;
use App\Models\TicketTypeOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class EvenementController extends Controller
{
    /**
     * Afficher la liste des événements
     */
    public function index(Request $request)
    {
        $query = Evenement::with(['organisateur', 'lieu'])
            ->withCount('billets')
            ->withSum('billets', 'prix');
        
        if ($request->filled('search')) {
            $query->where('titre', 'LIKE', '%' . $request->search . '%');
        }
        
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        
        if ($request->filled('organisateur')) {
            $query->where('user_id', $request->organisateur);
        }
        
        $evenements = $query->latest()->paginate(15);
        
        $stats = [
            'total' => Evenement::count(),
            'publies' => Evenement::where('statut', 'Publié')->count(),
            'brouillons' => Evenement::where('statut', 'Brouillon')->count(),
            'a_venir' => Evenement::where('date', '>=', now())->where('statut', 'Publié')->count(),
            'termines' => Evenement::where('statut', 'Terminé')->count(),
            'annules' => Evenement::where('statut', 'Annulé')->count(),
            'billets_vendus' => Billet::count(),
            'ca_total' => Billet::sum('prix'),
        ];
        
        $organisateurs = User::where('role', 'Organisateur')
            ->where('active', true)
            ->orderBy('nom')
            ->get();
        
        $this->createNotification(
            auth()->id(),
            'Consultation des événements',
            "Vous avez consulté la liste des événements. Total: {$stats['total']} événements",
            'info',
            'admin.evenements.index'
        );
        
        return view('admin.evenements.index', compact('evenements', 'stats', 'organisateurs'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $lieux = Lieu::orderBy('nom')->get();
        $organisateurs = User::where('role', 'Organisateur')
            ->where('active', true)
            ->orderBy('nom')
            ->get();
        $ticketTypes = TicketTypeOption::active()->ordered()->get();
        
        return view('admin.evenements.create', compact('lieux', 'organisateurs', 'ticketTypes'));
    }

    /**
     * Enregistrer un nouvel événement
     */
    public function store(Request $request)
    {
        // Preprocess ticket types data to ensure valid defaults and capacity constraints
        if ($request->has('types_billets') && is_array($request->types_billets)) {
            $processedTypesBillets = [];
            $capacity = (int) $request->capacite;
            $totalNonStandard = 0;
            $standardIndex = null;

            // First pass: process all tickets and calculate non-standard total
            foreach ($request->types_billets as $index => $type) {
                $processedType = [
                    'nom' => $type['nom'] ?? '',
                    'prix' => (float) ($type['prix'] ?? 0),
                    'quantite' => (int) ($type['quantite'] ?? 0),
                ];

                if (($type['nom'] ?? '') === 'Standard') {
                    $standardIndex = $index;
                } else {
                    $totalNonStandard += $processedType['quantite'];
                }

                $processedTypesBillets[] = $processedType;
            }

            // Adjust Standard ticket quantity to fit within capacity
            if ($standardIndex !== null && $totalNonStandard < $capacity) {
                $processedTypesBillets[$standardIndex]['quantite'] = $capacity - $totalNonStandard;
            }

            $request->merge(['types_billets' => $processedTypesBillets]);
        }

        $rules = [
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:100',
            'date' => 'required|date|after:now',
            'date_fin' => 'nullable|date|after:date',
            'capacite' => 'required|integer|min:1',
            'statut' => 'required|in:Brouillon,Publié',
            'lieu_id' => 'nullable|exists:lieux,id',
            'user_id' => 'required|exists:users,id',
            'conditions' => 'nullable|string',
            'date_vente_debut' => 'nullable|date',
            'date_vente_fin' => 'nullable|date|after:date_vente_debut',
            'affiche' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'banniere' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'types_billets' => 'required|array|min:1',
            'types_billets.*.nom' => 'required|string|max:100',
            'types_billets.*.prix' => 'required|numeric|min:0',
            'types_billets.*.quantite' => 'required|integer|min:0',
        ];
        
        if (!$request->filled('lieu_id')) {
            $rules['nouveau_lieu_nom'] = 'required|string|max:255';
            $rules['nouveau_lieu_adresse'] = 'required|string';
            $rules['nouveau_lieu_ville'] = 'required|string|max:100';
            $rules['nouveau_lieu_capacite'] = 'nullable|integer|min:1';
            $rules['nouveau_lieu_code_postal'] = 'nullable|string|max:20';
        }
        
        $request->validate($rules);
        
        try {
            DB::beginTransaction();
            
            $lieuId = $request->lieu_id;
            
            if (!$lieuId && $request->filled('nouveau_lieu_nom')) {
                $lieu = Lieu::create([
                    'nom' => $request->nouveau_lieu_nom,
                    'adresse' => $request->nouveau_lieu_adresse,
                    'ville' => $request->nouveau_lieu_ville,
                    'code_postal' => $request->nouveau_lieu_code_postal,
                    'capacite' => $request->nouveau_lieu_capacite,
                ]);
                $lieuId = $lieu->id;
            }
            
            if (!$lieuId) {
                throw new \Exception('Veuillez sélectionner ou créer un lieu.');
            }
            
            $affichePath = null;
            $bannierePath = null;
            
            if ($request->hasFile('affiche')) {
                $affichePath = $request->file('affiche')->store('events/affiches', 'public');
            }
            
            if ($request->hasFile('banniere')) {
                $bannierePath = $request->file('banniere')->store('events/bannieres', 'public');
            }
            
            $evenement = Evenement::create([
                'titre' => $request->titre,
                'description' => $request->description,
                'type' => $request->type,
                'date' => $request->date,
                'date_fin' => $request->date_fin,
                'capacite' => $request->capacite,
                'statut' => $request->statut,
                'lieu_id' => $lieuId,
                'user_id' => $request->user_id,
                'conditions' => $request->conditions,
                'date_vente_debut' => $request->date_vente_debut,
                'date_vente_fin' => $request->date_vente_fin,
                'affiche' => $affichePath,
                'banniere' => $bannierePath,
            ]);
            
            $quantiteTotale = 0;
            
            foreach ($request->types_billets as $type) {
                TypeBillet::create([
                    'evenement_id' => $evenement->id,
                    'nom' => $type['nom'],
                    'prix' => (float) $type['prix'],
                    'quantite' => (int) $type['quantite'],
                    'actif' => true,
                ]);
                $quantiteTotale += (int) $type['quantite'];
            }
            
            if ($quantiteTotale > $request->capacite) {
                DB::rollBack();
                return back()->with('error', 'La quantité totale des billets (' . $quantiteTotale . ') dépasse la capacité de l\'événement (' . $request->capacite . ')')->withInput();
            }
            
            $this->createNotification(
                auth()->id(),
                'Événement créé',
                "Vous avez créé l'événement \"{$evenement->titre}\" avec succès",
                'success',
                'admin.evenements.show',
                ['evenement_id' => $evenement->id]
            );
            
            $this->createNotification(
                $request->user_id,
                'Nouvel événement créé',
                "Un nouvel événement \"{$evenement->titre}\" a été créé pour vous",
                'success',
                'organisateur.evenements.show',
                ['evenement_id' => $evenement->id]
            );
            
            DB::commit();
            
            return redirect()
                ->route('admin.evenements.index')
                ->with('success', 'Événement créé avec succès !');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->createNotification(
                auth()->id(),
                'Erreur création événement',
                "Une erreur est survenue lors de la création: " . $e->getMessage(),
                'error',
                null,
                ['error' => $e->getMessage()]
            );
            
            return back()
                ->with('error', 'Erreur lors de la création: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Afficher le détail d'un événement
     */
    public function show(Evenement $evenement)
    {
        $evenement->load(['organisateur', 'lieu', 'typesBillets', 'billets.commande.client']);
        
        $this->createNotification(
            auth()->id(),
            'Consultation événement',
            "Vous avez consulté l'événement \"{$evenement->titre}\"",
            'info',
            'admin.evenements.show',
            ['evenement_id' => $evenement->id]
        );
        
        return view('admin.evenements.show', compact('evenement'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(Evenement $evenement)
    {
        $lieux = Lieu::orderBy('nom')->get();
        $organisateurs = User::where('role', 'Organisateur')
            ->where('active', true)
            ->orderBy('nom')
            ->get();
        $typesBillets = $evenement->typesBillets;
        $ticketTypes = TicketTypeOption::active()->ordered()->get();
        
        return view('admin.evenements.edit', compact('evenement', 'lieux', 'organisateurs', 'typesBillets', 'ticketTypes'));
    }

    /**
     * Mettre à jour un événement
     */
    public function update(Request $request, Evenement $evenement)
    {
        // Preprocess ticket types data to ensure valid defaults
        if ($request->has('types_billets') && is_array($request->types_billets)) {
            $processedTypesBillets = [];
            foreach ($request->types_billets as $type) {
                $processedTypesBillets[] = [
                    'id' => $type['id'] ?? null,
                    'nom' => $type['nom'] ?? '',
                    'prix' => (float) ($type['prix'] ?? 0),
                    'quantite' => (int) ($type['quantite'] ?? 0),
                ];
            }
            $request->merge(['types_billets' => $processedTypesBillets]);
        }

        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:100',
            'date' => 'required|date',
            'date_fin' => 'nullable|date|after:date',
            'capacite' => 'required|integer|min:1',
            'statut' => 'required|in:Brouillon,Publié,Terminé,Annulé,Reporté',
            'lieu_id' => 'required|exists:lieux,id',
            'user_id' => 'required|exists:users,id',
            'conditions' => 'nullable|string',
            'date_vente_debut' => 'nullable|date',
            'date_vente_fin' => 'nullable|date|after:date_vente_debut',
            'affiche' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'banniere' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'types_billets' => 'required|array|min:1',
            'types_billets.*.id' => 'nullable|exists:types_billets,id',
            'types_billets.*.nom' => 'required|string|max:100',
            'types_billets.*.prix' => 'required|numeric|min:0',
            'types_billets.*.quantite' => 'required|integer|min:0',
        ]);
        
        try {
            DB::beginTransaction();
            
            if ($request->hasFile('affiche')) {
                if ($evenement->affiche) {
                    Storage::disk('public')->delete($evenement->affiche);
                }
                $affichePath = $request->file('affiche')->store('events/affiches', 'public');
                $evenement->affiche = $affichePath;
            }
            
            if ($request->hasFile('banniere')) {
                if ($evenement->banniere) {
                    Storage::disk('public')->delete($evenement->banniere);
                }
                $bannierePath = $request->file('banniere')->store('events/bannieres', 'public');
                $evenement->banniere = $bannierePath;
            }
            
            $evenement->update([
                'titre' => $request->titre,
                'description' => $request->description,
                'type' => $request->type,
                'date' => $request->date,
                'date_fin' => $request->date_fin,
                'capacite' => $request->capacite,
                'statut' => $request->statut,
                'lieu_id' => $request->lieu_id,
                'user_id' => $request->user_id,
                'conditions' => $request->conditions,
                'date_vente_debut' => $request->date_vente_debut,
                'date_vente_fin' => $request->date_vente_fin,
            ]);

            // Synchroniser les types de billets
            $updatedTypeIds = [];
            $quantiteTotale = 0;

            foreach ($request->types_billets as $typeData) {
                if (!empty($typeData['id'])) {
                    $typeBillet = TypeBillet::where('evenement_id', $evenement->id)->where('id', $typeData['id'])->first();
                    if ($typeBillet) {
                        // Si on réduit la quantité par rapport aux billets déjà vendus, on refuse
                        if ($typeData['quantite'] < $typeBillet->quantite_vendue) {
                            DB::rollBack();
                            return back()->with('error', 'La quantité du type de billet "' . $typeBillet->nom . '" ne peut pas être inférieure aux billets déjà vendus.')->withInput();
                        }

                        $typeBillet->update([
                            'nom' => $typeData['nom'],
                            'prix' => (float) $typeData['prix'],
                            'quantite' => (int) $typeData['quantite'],
                        ]);

                        $updatedTypeIds[] = $typeBillet->id;
                    }
                } else {
                    $newType = TypeBillet::create([
                        'evenement_id' => $evenement->id,
                        'nom' => $typeData['nom'],
                        'prix' => (float) $typeData['prix'],
                        'quantite' => (int) $typeData['quantite'],
                        'actif' => true,
                    ]);
                    $updatedTypeIds[] = $newType->id;
                }

                $quantiteTotale += (int) $typeData['quantite'];
            }

            // Supprimer les types retirés
            $existingTypeIds = $evenement->typesBillets()->pluck('id')->toArray();
            $toDelete = array_diff($existingTypeIds, $updatedTypeIds);
            if (!empty($toDelete)) {
                $typesToDelete = TypeBillet::whereIn('id', $toDelete)->get();
                $soldType = $typesToDelete->firstWhere('quantite_vendue', '>', 0);

                if ($soldType) {
                    DB::rollBack();
                    return back()->with('error', 'Impossible de supprimer le type de billet "' . $soldType->nom . '" car des billets ont déjà été vendus.')->withInput();
                }

                TypeBillet::whereIn('id', $toDelete)->delete();
            }

            if ($quantiteTotale > $request->capacite) {
                DB::rollBack();
                return back()->with('error', 'La quantité totale des billets (' . $quantiteTotale . ') dépasse la capacité de l\'événement (' . $request->capacite . ')')->withInput();
            }

            $this->createNotification(
                auth()->id(),
                'Événement modifié',
                "Vous avez modifié l'événement \"{$evenement->titre}\"",
                'info',
                'admin.evenements.show',
                ['evenement_id' => $evenement->id]
            );
            
            DB::commit();
            
            return redirect()
                ->route('admin.evenements.index')
                ->with('success', 'Événement modifié avec succès !');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Erreur lors de la modification: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Supprimer un événement
     */
    public function destroy(Evenement $evenement)
    {
        $billetsVendus = $evenement->billets()->count();
        if ($billetsVendus > 0) {
            return back()->with('error', "Impossible de supprimer cet événement car {$billetsVendus} billet(s) ont été vendus.");
        }
        
        try {
            DB::beginTransaction();
            
            $titre = $evenement->titre;
            
            if ($evenement->affiche) {
                Storage::disk('public')->delete($evenement->affiche);
            }
            if ($evenement->banniere) {
                Storage::disk('public')->delete($evenement->banniere);
            }
            
            $evenement->typesBillets()->delete();
            $evenement->delete();
            
            $this->createNotification(
                auth()->id(),
                'Événement supprimé',
                "Vous avez supprimé l'événement \"{$titre}\"",
                'warning',
                'admin.evenements.index'
            );
            
            DB::commit();
            
            return redirect()
                ->route('admin.evenements.index')
                ->with('success', 'Événement supprimé avec succès !');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Changer le statut d'un événement
     */
    public function updateStatus(Request $request, Evenement $evenement)
    {
        $request->validate([
            'statut' => 'required|in:Brouillon,Publié,Terminé,Annulé,Reporté'
        ]);

        $ancienStatut = $evenement->statut;
        $nouveauStatut = $request->statut;

        // Vérifications de sécurité pour les changements de statut
        $billetsVendus = $evenement->billets()->where('statut', 'Valide')->count();

        // Ne pas permettre de publier un événement terminé
        if ($ancienStatut === 'Terminé' && $nouveauStatut === 'Publié') {
            return back()->with('error', 'Impossible de republier un événement terminé.');
        }

        // Ne pas permettre d'annuler un événement avec des billets vendus (sauf si c'est déjà annulé)
        if ($nouveauStatut === 'Annulé' && $billetsVendus > 0 && $ancienStatut !== 'Annulé') {
            return back()->with('error', 'Impossible d\'annuler un événement avec des billets vendus. Veuillez contacter le support.');
        }

        // Ne pas permettre de terminer un événement qui n'est pas encore passé
        if ($nouveauStatut === 'Terminé' && $evenement->date->isFuture()) {
            return back()->with('error', 'Impossible de marquer comme terminé un événement qui n\'a pas encore eu lieu.');
        }

        // Ne pas permettre de publier un événement sans types de billets
        if ($nouveauStatut === 'Publié' && $evenement->typesBillets()->count() === 0) {
            return back()->with('error', 'Impossible de publier un événement sans types de billets configurés.');
        }

        $evenement->update(['statut' => $nouveauStatut]);

        $this->createNotification(
            auth()->id(),
            'Statut modifié',
            "Vous avez changé le statut de l'événement \"{$evenement->titre}\" de {$ancienStatut} à {$nouveauStatut}",
            'info',
            'admin.evenements.show',
            ['evenement_id' => $evenement->id]
        );

        return back()->with('success', 'Statut mis à jour avec succès !');
    }

    /**
     * Dupliquer un événement
     */
    public function duplicate(Request $request, Evenement $evenement)
    {
        $request->validate([
            'titre' => 'required|string|max:255'
        ]);
        
        try {
            DB::beginTransaction();
            
            $newEvent = $evenement->replicate();
            $newEvent->titre = $request->titre;
            $newEvent->statut = 'Brouillon';
            $newEvent->created_at = now();
            $newEvent->updated_at = now();
            $newEvent->save();
            
            foreach ($evenement->typesBillets as $type) {
                $newType = $type->replicate();
                $newType->evenement_id = $newEvent->id;
                $newType->quantite_vendue = 0;
                $newType->save();
            }
            
            DB::commit();
            
            return redirect()
                ->route('admin.evenements.index')
                ->with('success', 'Événement dupliqué avec succès !');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la duplication: ' . $e->getMessage());
        }
    }

    /**
     * Récupérer le titre d'un événement pour la duplication
     */
    public function getTitle(Evenement $evenement)
    {
        return response()->json(['titre' => $evenement->titre]);
    }

    /**
     * Afficher la liste des billets pour un événement
     */
    public function billets(Evenement $evenement)
    {
        $evenement->load(['organisateur', 'lieu', 'typesBillets']);
        
        $billets = $evenement->billets()
            ->with(['commande.client', 'typeBillet'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $stats = [
            'total' => $evenement->billets()->count(),
            'valides' => $evenement->billets()->where('statut', 'Valide')->count(),
            'annules' => $evenement->billets()->where('statut', 'Annulé')->count(),
            'rembourses' => $evenement->billets()->where('statut', 'Remboursé')->count(),
            'ca_total' => $evenement->billets()->where('statut', 'Valide')->sum('prix'),
        ];
        
        return view('admin.evenements.billets', compact('evenement', 'billets', 'stats'));
    }

    /**
     * Afficher la page d'achat de tickets
     */
    public function acheterTickets(Evenement $evenement)
    {
        if ($evenement->statut !== 'Publié') {
            return redirect()->route('admin.evenements.show', $evenement)
                ->with('error', 'Cet événement n\'est pas encore publié.');
        }
        
        $typesBillets = $evenement->typesBillets;
        $quantiteMax = $evenement->placesRestantes();
        
        return view('admin.evenements.acheter', compact('evenement', 'typesBillets', 'quantiteMax'));
    }

    /**
     * Confirmer l'achat de tickets (étape intermédiaire)
     */
    public function confirmAchat(Request $request, Evenement $evenement)
    {
        $request->validate([
            'billets' => 'required|array|min:1',
            'billets.*.type_id' => 'required|exists:types_billets,id',
            'billets.*.quantite' => 'required|integer|min:0',
            'nom_spectateur' => 'required|string|max:255',
            'email_spectateur' => 'required|email|max:255',
        ]);

        // Calculer le total et préparer les données
        $montantTotal = 0;
        $billetsData = [];
        $typesBillets = [];

        foreach ($request->billets as $item) {
            if ($item['quantite'] > 0) {
                $type = TypeBillet::find($item['type_id']);
                if ($type) {
                    $subtotal = $type->prix * $item['quantite'];
                    $montantTotal += $subtotal;
                    $billetsData[] = [
                        'type' => $type,
                        'quantite' => $item['quantite'],
                        'prix_unitaire' => $type->prix,
                        'sous_total' => $subtotal
                    ];
                    $typesBillets[] = $type;
                }
            }
        }

        if (empty($billetsData)) {
            return redirect()->back()->with('error', 'Veuillez sélectionner au moins un billet.');
        }

        // Stocker les données en session pour la confirmation
        session([
            'achat_confirmation' => [
                'evenement_id' => $evenement->id,
                'billets' => $request->billets,
                'nom_spectateur' => $request->nom_spectateur,
                'email_spectateur' => $request->email_spectateur,
                'montant_total' => $montantTotal,
                'billets_data' => $billetsData
            ]
        ]);

        return view('admin.evenements.confirm-achat', compact('evenement', 'billetsData', 'montantTotal', 'typesBillets'));
    }

    /**
     * Traiter l'achat de tickets (sauvegarde du panier)
     */
    public function processAchat(Request $request, Evenement $evenement)
    {
        // Récupérer les données de la session
        $confirmationData = session('achat_confirmation');
        if (!$confirmationData) {
            return redirect()->route('admin.evenements.acheter', $evenement)
                ->with('error', 'Session expirée. Veuillez recommencer votre commande.');
        }

        // Validation des informations de paiement
        $rules = [
            'methode_paiement' => 'required|in:carte,paypal,especes',
        ];

        // Validation selon la méthode de paiement
        switch ($request->methode_paiement) {
            case 'carte':
                $rules['carte_numero'] = 'required|string|min:16|max:19';
                $rules['carte_expiration'] = 'required|string|size:5';
                $rules['carte_cvv'] = 'required|string|min:3|max:4';
                $rules['carte_titulaire'] = 'required|string|max:255';
                break;
            case 'paypal':
                $rules['paypal_email'] = 'required|email';
                break;
            case 'especes':
                $rules['especes_email'] = 'required|email';
                $rules['especes_telephone'] = 'nullable|string|max:20';
                break;
        }

        $request->validate($rules);

        // Validation manuelle pour la date d'expiration de carte
        if ($request->methode_paiement === 'carte') {
            $expiration = $request->carte_expiration;
            if (!preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $expiration)) {
                return back()->withErrors(['carte_expiration' => 'Le format de la date d\'expiration doit être MM/YY (ex: 12/25)'])->withInput();
            }
        }

        try {
            DB::beginTransaction();

            // Utiliser les données de la session
            $montantTotal = $confirmationData['montant_total'];
            $billetsData = $confirmationData['billets_data'];
            $nomSpectateur = $confirmationData['nom_spectateur'];
            $emailSpectateur = $confirmationData['email_spectateur'];

            // Créer ou récupérer le client
            $client = User::where('email', $emailSpectateur)->first();
            if (!$client) {
                // Créer un nouveau client
                $noms = explode(' ', $nomSpectateur, 2);
                $client = User::create([
                    'prenom' => $noms[0] ?? '',
                    'nom' => $noms[1] ?? $noms[0] ?? '',
                    'email' => $emailSpectateur,
                    'password' => bcrypt(Str::random(12)), // Mot de passe temporaire
                    'role' => 'Client',
                ]);
            }

            // Créer la commande
            $commande = Commande::create([
                'reference' => 'ADMIN-' . strtoupper(Str::random(12)),
                'client_id' => $client->id,
                'evenement_id' => $evenement->id,
                'montant_total' => $montantTotal,
                'statut' => 'Confirmée', // Commande directement confirmée pour l'admin
                'date' => now(),
            ]);

            // Créer le paiement
            $paiement = Paiement::create([
                'reference' => 'ADMIN-' . strtoupper(Str::random(12)),
                'commande_id' => $commande->id,
                'montant' => $montantTotal,
                'prestataire' => $request->methode_paiement,
                'statut' => 'Payé',
                'date' => now(),
            ]);

            // Créer les billets
            foreach ($billetsData as $data) {
                for ($i = 0; $i < $data['quantite']; $i++) {
                    Billet::create([
                        'code_unique' => 'TKT-' . strtoupper(Str::random(12)),
                        'commande_id' => $commande->id,
                        'evenement_id' => $evenement->id,
                        'type_billet_id' => $data['type']->id,
                        'nom_spectateur' => $nomSpectateur,
                        'email_spectateur' => $emailSpectateur,
                        'statut' => 'Valide', // Billets directement validés pour l'admin
                        'prix' => $data['prix_unitaire'],
                        'date_creation' => now(),
                    ]);
                }
            }

            DB::commit();

            // Nettoyer la session
            session()->forget('achat_confirmation');

            return redirect()->route('admin.commandes.show', $commande)
                ->with('success', 'Les billets ont été créés avec succès.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la création des billets: ' . $e->getMessage());
        }
    }

    /**
     * Afficher la page de paiement
     */
    public function paiement(Evenement $evenement)
    {
        // Vérifier que le panier existe
        if (!session()->has('panier') || empty(session('panier'))) {
            return redirect()->route('admin.evenements.acheter', $evenement)
                ->with('error', 'Votre panier est vide.');
        }
        
        return view('admin.evenements.paiement', compact('evenement'));
    }

    /**
     * Traiter le paiement
     */
    public function processPaiement(Request $request, Evenement $evenement)
    {
        $rules = [
            'methode_paiement' => 'required|in:carte,paypal,virement,especes',
        ];
        
        // Validation selon la méthode de paiement
        switch ($request->methode_paiement) {
            case 'carte':
                $rules['carte_numero'] = 'required|string|min:16|max:19';
                $rules['carte_expiration'] = 'required|string|regex:/^(0[1-9]|1[0-2])\/([0-9]{2})$/';
                $rules['carte_cvv'] = 'required|string|min:3|max:4';
                $rules['carte_titulaire'] = 'required|string|max:255';
                break;
            case 'paypal':
                $rules['paypal_email'] = 'required|email';
                break;
            case 'virement':
                $rules['virement_reference'] = 'required|string';
                $rules['virement_email'] = 'required|email';
                break;
            case 'especes':
                $rules['especes_email'] = 'required|email';
                $rules['especes_telephone'] = 'nullable|string|max:20';
                break;
        }
        
        $request->validate($rules);
        
        try {
            DB::beginTransaction();
            
            $panier = session('panier', []);
            $montantTotal = session('montant_total', 0);
            $nomSpectateur = session('nom_spectateur');
            $emailSpectateur = session('email_spectateur');
            $reference = session('reference');
            
            if (empty($panier)) {
                throw new \Exception('Panier vide');
            }
            
            // Vérifier les stocks
            foreach ($panier as $item) {
                $type = TypeBillet::find($item['type_id']);
                if (!$type) {
                    throw new \Exception('Type de billet introuvable');
                }
                $disponible = $type->quantite - $type->quantite_vendue;
                if ($item['quantite'] > $disponible) {
                    throw new \Exception("Stock insuffisant pour {$type->nom}");
                }
            }
            
            // Préparer les données de paiement
            $paiementData = [
                'methode' => $request->methode_paiement,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ];
            
            switch ($request->methode_paiement) {
                case 'carte':
                    $paiementData['card_last4'] = substr($request->carte_numero, -4);
                    $paiementData['card_brand'] = $this->detectCardBrand($request->carte_numero);
                    break;
                case 'paypal':
                    $paiementData['paypal_email'] = $request->paypal_email;
                    break;
                case 'virement':
                    $paiementData['virement_reference'] = $request->virement_reference;
                    $paiementData['virement_email'] = $request->virement_email;
                    break;
                case 'especes':
                    $paiementData['especes_email'] = $request->especes_email;
                    $paiementData['especes_telephone'] = $request->especes_telephone;
                    break;
            }
            
            // Créer la commande
            $commande = Commande::create([
                'reference' => $reference,
                'client_id' => auth()->id(),
                'evenement_id' => $evenement->id,
                'date' => now(),
                'montant_total' => $montantTotal,
                'methode_paiement' => $request->methode_paiement,
                'email_spectateur' => $emailSpectateur,
                'statut' => $request->methode_paiement == 'especes' ? 'En attente' : 'Confirmée',
                'notes' => json_encode($paiementData)
            ]);
            
            // Créer le paiement
            $paiement = Paiement::create([
                'reference' => 'PAY-' . strtoupper(Str::random(12)),
                'commande_id' => $commande->id,
                'montant' => $montantTotal,
                'prestataire' => $this->getPrestataire($request->methode_paiement),
                'statut' => $request->methode_paiement == 'especes' ? 'En attente' : 'Payé',
                'date_paiement' => $request->methode_paiement != 'especes' ? now() : null,
                'reponse_prestataire' => json_encode($paiementData)
            ]);
            
            // Créer les billets
            $billetsCrees = [];
            foreach ($panier as $item) {
                $type = TypeBillet::find($item['type_id']);
                
                for ($i = 0; $i < $item['quantite']; $i++) {
                    $codeUnique = $this->generateUniqueCode();
                    
                    $billet = Billet::create([
                        'code_unique' => $codeUnique,
                        'qr_content' => $codeUnique,
                        'type_billet_id' => $type->id,
                        'commande_id' => $commande->id,
                        'evenement_id' => $evenement->id,
                        'nom_spectateur' => $nomSpectateur,
                        'email_spectateur' => $emailSpectateur,
                        'prix' => $type->prix,
                        'statut' => 'Valide',
                    ]);
                    $billetsCrees[] = $billet;
                    
                    $type->increment('quantite_vendue');
                }
            }
            
            // Mettre à jour les places vendues
            $evenement->increment('places_vendues', count($billetsCrees));
            
            // Nettoyer la session
            session()->forget(['panier', 'montant_total', 'nom_spectateur', 'email_spectateur', 'reference']);
            
            // Notification
            $this->createNotification(
                auth()->id(),
                'Paiement effectué',
                "Vous avez payé " . number_format($montantTotal, 2, ',', ' ') . " DH pour l'événement \"{$evenement->titre}\"",
                'success',
                'admin.evenements.confirmation',
                ['commande_id' => $commande->id]
            );
            
            DB::commit();
            
            return redirect()
                ->route('admin.evenements.confirmation', $evenement)
                ->with('success', 'Paiement effectué avec succès !')
                ->with('commande', $commande);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur paiement', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'evenement_id' => $evenement->id
            ]);
            
            return redirect()
                ->route('admin.evenements.paiement', $evenement)
                ->with('error', 'Erreur lors du paiement: ' . $e->getMessage());
        }
    }
    
    /**
     * Afficher la page de confirmation
     */
    public function confirmation(Evenement $evenement)
    {
        $commande = Commande::where('client_id', auth()->id())
            ->where('evenement_id', $evenement->id)
            ->latest()
            ->first();
        
        if (!$commande) {
            return redirect()
                ->route('admin.evenements.show', $evenement)
                ->with('error', 'Aucune commande trouvée');
        }
        
        $billets = Billet::where('commande_id', $commande->id)
            ->with('typeBillet')
            ->get();
        
        return view('admin.evenements.confirmation', compact('evenement', 'commande', 'billets'));
    }

    /**
     * Télécharger tous les billets d'un événement en PDF (Admin)
     */
    public function downloadAllTickets(Evenement $evenement)
    {
        $billets = $evenement->billets()
            ->with(['typeBillet', 'commande.client'])
            ->orderBy('created_at')
            ->get();

        if ($billets->isEmpty()) {
            return back()->with('error', 'Aucun billet trouvé pour cet événement.');
        }

        $pdf = Pdf::loadView('pdf.tickets_table', compact('billets', 'evenement'))
                  ->setOption('isRemoteEnabled', true);

        return $pdf->download('billets_evenement_' . $evenement->id . '.pdf');
    }

    /**
     * Télécharger un billet individuel (Admin - sans restrictions)
     */
    public function downloadSingleTicket(Evenement $evenement, Billet $billet)
    {
        if ($billet->evenement_id != $evenement->id) {
            abort(404);
        }

        $billet->load(['evenement.lieu', 'typeBillet', 'commande.client']);

        // Generate QR code as SVG (doesn't require imagick extension)
        $qrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(200)->generate($billet->code_unique);
        $qrCode = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);

        $pdf = Pdf::loadView('pdf.ticket', compact('billet', 'qrCode'))
                  ->setOption('isRemoteEnabled', true);

        return $pdf->download('ticket_' . $billet->code_unique . '.pdf');
    }

    /**
     * Exporter les billets d'un événement
     */
    public function exportBillets(Evenement $evenement)
    {
        $billets = $evenement->billets()
            ->with(['typeBillet'])
            ->get();
        
        $csv = "Code,Type,Prix,Spectateur,Email,Statut,Date achat\n";
        
        foreach ($billets as $billet) {
            $csv .= "{$billet->code_unique},{$billet->typeBillet->nom},{$billet->prix},";
            $csv .= "{$billet->nom_spectateur},{$billet->email_spectateur},";
            $csv .= "{$billet->statut},{$billet->created_at->format('d/m/Y H:i')}\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="billets_event_' . $evenement->id . '_' . date('Y-m-d') . '.csv"');
    }

    /**
     * Exporter les événements
     */
    public function export(Request $request)
    {
        $query = Evenement::with(['organisateur', 'lieu', 'typesBillets']);
        
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        
        $evenements = $query->get();
        
        $csv = "ID,Titre,Type,Date,Organisateur,Lieu,Statut,Places vendues,Capacité,Prix min,Prix max,Date création\n";
        
        foreach ($evenements as $event) {
            $prixMin = $event->typesBillets->min('prix') ?? 0;
            $prixMax = $event->typesBillets->max('prix') ?? 0;
            $placesVendues = $event->billets()->count();
            $lieuNom = $event->lieu ? $event->lieu->nom : 'N/A';
            $organisateurNom = $event->organisateur ? $event->organisateur->prenom . ' ' . $event->organisateur->nom : 'Inconnu';
            
            $csv .= "{$event->id},{$event->titre},{$event->type},{$event->date->format('d/m/Y H:i')},";
            $csv .= "{$organisateurNom},";
            $csv .= "{$lieuNom},{$event->statut},";
            $csv .= "{$placesVendues},{$event->capacite},";
            $csv .= "{$prixMin},{$prixMax},{$event->created_at->format('d/m/Y H:i')}\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="evenements_' . date('Y-m-d_H-i-s') . '.csv"');
    }
    
    /**
     * Détecter le type de carte bancaire
     */
    private function detectCardBrand($cardNumber)
    {
        $cardNumber = preg_replace('/[^0-9]/', '', $cardNumber);
        
        $patterns = [
            'visa' => '/^4[0-9]{12}(?:[0-9]{3})?$/',
            'mastercard' => '/^5[1-5][0-9]{14}$/',
            'amex' => '/^3[47][0-9]{13}$/',
            'discover' => '/^6(?:011|5[0-9]{2})[0-9]{12}$/',
        ];
        
        foreach ($patterns as $brand => $pattern) {
            if (preg_match($pattern, $cardNumber)) {
                return ucfirst($brand);
            }
        }
        
        return 'Autre';
    }
    
    /**
     * Générer un code unique pour le billet
     */
    private function generateUniqueCode()
    {
        do {
            $code = 'TKT-' . strtoupper(Str::random(12));
        } while (Billet::where('code_unique', $code)->exists());
        
        return $code;
    }
    
    /**
     * Obtenir le prestataire de paiement
     */
    private function getPrestataire($methode)
    {
        $prestataires = [
            'carte' => 'Stripe',
            'paypal' => 'PayPal',
            'virement' => 'Virement',
            'especes' => 'Espèces'
        ];
        
        return $prestataires[$methode] ?? 'Autre';
    }
    
    /**
     * Obtenir le moyen de paiement
     */
    private function getMoyenPaiement($methode)
    {
        $moyens = [
            'carte' => 'Carte bancaire',
            'paypal' => 'PayPal',
            'virement' => 'Virement bancaire',
            'especes' => 'Espèces'
        ];
        
        return $moyens[$methode] ?? 'Autre';
    }
    
    /**
     * Créer une notification
     */
    private function createNotification($userId, $title, $message, $type = 'info', $route = null, $data = null)
    {
        try {
            \App\Models\Notification::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => $data,
                'is_read' => false
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur création notification', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'title' => $title
            ]);
        }
    }
}