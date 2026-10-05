<?php
// app/Http/Controllers/Admin/CommandeController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CommandeController extends Controller
{
    /**
     * Afficher la liste des commandes
     */
    public function index(Request $request)
    {
        $query = Commande::with(['client', 'paiement']);
        
        if ($request->filled('search')) {
            $query->where('reference', 'LIKE', '%' . $request->search . '%');
        }
        
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        
        $commandes = $query->latest()->paginate(15);
        
        $stats = [
            'total' => Commande::count(),
            'confirmees' => Commande::where('statut', 'Confirmée')->count(),
            'en_attente' => Commande::where('statut', 'En attente')->count(),
            'annulees' => Commande::where('statut', 'Annulée')->count(),
            'montant_total' => Commande::where('statut', 'Confirmée')->sum('montant_total'),
        ];
        
        // Charger la liste des clients pour le filtre
        $clients = User::whereHas('commandes')->orderBy('nom')->orderBy('prenom')->get();
        
        return view('admin.commandes.index', compact('commandes', 'stats', 'clients'));
    }
    
    /**
     * Afficher les détails d'une commande
     */
    public function show(Commande $commande)
    {
        $commande->load(['client', 'billets.evenement', 'billets.typeBillet', 'paiement']);
        
        return view('admin.commandes.show', compact('commande'));
    }
    
    /**
     * Mettre à jour le statut d'une commande
     */
    public function updateStatus(Request $request, Commande $commande)
    {
        $request->validate([
            'statut' => 'required|in:En attente,Confirmée,Annulée,Remboursée'
        ]);
        
        $commande->update(['statut' => $request->statut]);
        
        return back()->with('success', 'Statut de la commande mis à jour avec succès !');
    }
    
    /**
     * Exporter les commandes
     */
    public function export(Request $request)
    {
        $query = Commande::with(['client']);
        
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        
        $commandes = $query->get();
        
        $csv = "ID,Référence,Client,Montant,Statut,Date\n";
        
        foreach ($commandes as $commande) {
            $client = $commande->client;
            $csv .= "{$commande->id},{$commande->reference},";
            $csv .= ($client ? $client->prenom . ' ' . $client->nom : 'N/A') . ",";
            $csv .= "{$commande->montant_total} DH,{$commande->statut},";
            $csv .= "{$commande->created_at->format('d/m/Y H:i')}\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="commandes_' . date('Y-m-d') . '.csv"');
    }

    /**
     * Télécharger tous les billets d'une commande en PDF
     */
    public function downloadBillets(Commande $commande)
    {
        $billets = $commande->billets()->with(['evenement.lieu', 'commande.client', 'typeBillet'])->get();

        if ($billets->isEmpty()) {
            return back()->with('error', 'Aucun billet trouvé dans cette commande.');
        }

        $pdf = Pdf::loadView('pdf.tickets_commande', compact('billets', 'commande'))
                  ->setOption('isRemoteEnabled', true);

        return $pdf->download('billets_commande_' . $commande->reference . '.pdf');
    }
}