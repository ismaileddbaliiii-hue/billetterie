<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Billet;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BilletController extends Controller
{
    /**
     * Liste des billets du client
     */
    public function index(Request $request)
    {
        $baseQuery = Billet::whereHas('commande', function($q) {
            $q->where('client_id', Auth::id());
        });

        // Collecte les stats à partir de TOUS les billets
        $totalBillets = (clone $baseQuery)->count();
        $validBillets = (clone $baseQuery)->where('statut', 'Valide')->count();
        $evenementsAVenir = Evenement::whereHas('billets.commande', function($q) {
            $q->where('client_id', Auth::id());
        })->where('date', '>=', now())->count();

        $evenements = Evenement::whereHas('billets.commande', function($q) {
            $q->where('client_id', Auth::id());
        })->distinct()->get();

        if ($request->filled('evenement_id')) {
            $billets = (clone $baseQuery)
                ->where('evenement_id', $request->evenement_id)
                ->with(['evenement.lieu', 'commande', 'typeBillet'])
                ->orderBy('created_at', 'desc')
                ->paginate(12);
        } else {
            $billets = (clone $baseQuery)
                ->with(['evenement.lieu', 'commande', 'typeBillet'])
                ->orderBy('created_at', 'desc')
                ->paginate(12);
        }

        return view('client.billets.index', compact('billets', 'evenements', 'totalBillets', 'validBillets', 'evenementsAVenir'));
    }
    
    /**
     * Affiche les détails d'un billet
     */
    public function show($id)
    {
        $billet = Billet::whereHas('commande', function($q) {
            $q->where('client_id', Auth::id());
        })->with(['evenement.lieu', 'commande', 'typeBillet'])->findOrFail($id);

        return view('client.billets.show', compact('billet'));
    }

    /**
     * Télécharger un billet en PDF
     */
    public function download($id)
    {
        $billet = Billet::whereHas('commande', function($q) {
            $q->where('client_id', Auth::id());
        })->with(['evenement.lieu', 'commande.client', 'typeBillet'])->findOrFail($id);

        // Generate QR code URL - DomPDF will embed it
        $qrCode = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($billet->code_unique);

        $pdf = Pdf::loadView('pdf.ticket', compact('billet', 'qrCode'))
                  ->setOption('isRemoteEnabled', true);

        return $pdf->download('ticket_' . $billet->code_unique . '.pdf');
    }

    /**
     * Télécharger tous les billets valides d'un événement en un seul PDF
     */
    public function downloadAllByEvent($evenementId)
    {
        $billets = Billet::whereHas('commande', function($q) {
                $q->where('client_id', Auth::id());
            })
            ->where('evenement_id', $evenementId)
            ->where('statut', 'Valide')
            ->with(['evenement.lieu', 'commande.client', 'typeBillet'])
            ->get();

        $evenement = Evenement::findOrFail($evenementId);

        if ($billets->isEmpty()) {
            abort(404, 'Aucun billet valide trouvé pour cet événement.');
        }

        $pdf = Pdf::loadView('pdf.tickets_table', compact('billets', 'evenement'))
                  ->setOption('isRemoteEnabled', true);

        return $pdf->download('billets_' . $evenement->id . '.pdf');
    }

    public function downloadAll()
    {
        $billets = Billet::whereHas('commande', function($q) {
                $q->where('client_id', Auth::id());
            })
            ->where('statut', 'Valide')
            ->with(['evenement.lieu', 'commande.client', 'typeBillet'])
            ->orderBy('evenement_id')
            ->orderBy('created_at')
            ->get();

        if ($billets->isEmpty()) {
            abort(404, 'Aucun billet valide trouvé.');
        }

        $events = $billets->groupBy(function ($billet) {
            return $billet->evenement->id;
        });

        $pdf = Pdf::loadView('pdf.tickets_all_events', compact('billets', 'events'))
                  ->setOption('isRemoteEnabled', true);

        return $pdf->download('billets_tous_evenements.pdf');
    }
    
    /**
     * Billets par événement
     */
    public function byEvent($evenementId)
    {
        $billets = Billet::whereHas('commande', function($q) {
            $q->where('client_id', Auth::id());
        })->where('evenement_id', $evenementId)
          ->with(['evenement.lieu', 'typeBillet'])
          ->get();
        
        $evenement = Evenement::with('lieu')->findOrFail($evenementId);
        
        return view('client.billets.event', compact('billets', 'evenement'));
    }
}