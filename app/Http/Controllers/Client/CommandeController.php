<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Commande;
use App\Models\Billet;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    /**
     * Liste des commandes du client
     */
    public function index()
    {
        $commandes = Commande::where('client_id', Auth::id())
            ->with(['billets.evenement', 'paiement'])
            ->orderBy('date', 'desc')
            ->paginate(10);
        
        return view('client.commandes.index', compact('commandes'));
    }
    
    /**
     * Détails d'une commande
     */
    public function show($id)
    {
        $commande = Commande::where('client_id', Auth::id())
            ->with(['billets.evenement.lieu', 'billets.typeBillet', 'paiement'])
            ->findOrFail($id);
        
        return view('client.commandes.show', compact('commande'));
    }
    
    /**
     * Télécharger la facture
     */
    public function facture($id)
    {
        $commande = Commande::where('client_id', Auth::id())
            ->with(['billets.evenement', 'paiement'])
            ->findOrFail($id);
        
        // TODO: Générer PDF facture
        return view('client.commandes.facture', compact('commande'));
    }

    public function sendInvoice(Commande $commande)
    {
        if ($commande->client_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Commande non autorisée.'], 403);
        }

        $commande->load(['billets.evenement', 'billets.typeBillet', 'paiement']);

        if (! $commande->client || ! $commande->client->email) {
            return response()->json(['success' => false, 'message' => 'Client introuvable ou email manquant.'], 422);
        }

        $fontDir = storage_path('fonts');
        if (! file_exists($fontDir)) {
            mkdir($fontDir, 0755, true);
        }

        $pdf = Pdf::setOptions([
            'defaultFont' => 'sans-serif',
            'isRemoteEnabled' => true,
            'fontDir' => $fontDir,
            'fontCache' => $fontDir,
        ])->loadView('client.commandes.facture', compact('commande'))->setPaper('a4', 'portrait');

        Mail::raw('Veuillez trouver votre facture en pièce jointe.', function ($message) use ($commande, $pdf) {
            $message->to($commande->client->email, $commande->client->prenom . ' ' . $commande->client->nom)
                    ->subject('Facture commande #' . $commande->id)
                    ->attachData($pdf->output(), 'facture_' . $commande->id . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
        });

        return response()->json(['success' => true]);
    }
}