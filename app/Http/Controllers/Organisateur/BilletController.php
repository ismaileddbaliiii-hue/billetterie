<?php

namespace App\Http\Controllers\Organisateur;

use App\Http\Controllers\Controller;
use App\Models\Billet;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BilletController extends Controller
{
    public function index()
    {
        // Get tickets verified today by current organizer
        $verifiesAujourdhui = Billet::whereHas('evenement', function ($q) {
            $q->where('user_id', Auth::id());
        })
        ->whereNotNull('date_validation')
        ->whereDate('date_validation', today())
        ->count();

        // Get total validated entries for current organizer
        $entreesValidees = Billet::whereHas('evenement', function ($q) {
            $q->where('user_id', Auth::id());
        })
        ->whereNotNull('date_validation')
        ->count();

        // Calculate occupation rate
        $evenements = \App\Models\Evenement::where('user_id', Auth::id())->get();
        $capaciteTotale = $evenements->sum('capacite');
        $billetValides = Billet::whereHas('evenement', function ($q) {
            $q->where('user_id', Auth::id());
        })->where('statut', 'Valide')->count();
        
        $tauxOccupation = $capaciteTotale > 0 ? round(($billetValides / $capaciteTotale) * 100) : 0;
        
        // Get organizer events for filtering
        $myEvenements = \App\Models\Evenement::where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->get();

        return view('organisateur.billets.index', compact('verifiesAujourdhui', 'entreesValidees', 'tauxOccupation', 'myEvenements'));
    }
    
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);
        
        $billet = Billet::where('code_unique', $request->code)
            ->orWhere('qr_content', $request->code)
            ->with(['evenement', 'commande.client'])
            ->first();
        
        if (!$billet) {
            return redirect()->back()->with('verification', [
                'valid' => false,
                'message' => 'Billet introuvable. Vérifiez le code saisi.'
            ]);
        }
        
        // Vérifier que l'organisateur est propriétaire de l'événement
        if ($billet->evenement->user_id !== Auth::id()) {
            return redirect()->back()->with('verification', [
                'valid' => false,
                'message' => 'Ce billet ne correspond pas à un de vos événements.'
            ]);
        }
        
        // Vérifier si le billet est valide
        if ($billet->statut === 'Annulé') {
            return redirect()->back()->with('verification', [
                'valid' => false,
                'message' => 'Ce billet a été annulé.'
            ]);
        }

        // Vérifier si le billet a déjà été utilisé
        if ($billet->statut === 'Utilisé') {
            return redirect()->back()->with('verification', [
                'valid' => false,
                'message' => 'Ce billet a déjà été utilisé.'
            ]);
        }
        
        // Vérifier si l'événement est passé
        if ($billet->evenement->date < now()) {
            return redirect()->back()->with('verification', [
                'valid' => false,
                'message' => 'Cet événement est déjà terminé.'
            ]);
        }
        
        // Vérifier si l'événement est annulé
        if ($billet->evenement->statut === 'Annulé') {
            return redirect()->back()->with('verification', [
                'valid' => false,
                'message' => 'Cet événement a été annulé.'
            ]);
        }
        
        return redirect()->back()->with('verification', [
            'valid' => true,
            'message' => 'Billet valide. Accès autorisé.',
            'billet' => $billet
        ]);
    }

    public function download($id)
    {
        $billet = Billet::whereHas('evenement', function ($q) {
            $q->where('user_id', Auth::id());
        })->with(['evenement.lieu', 'commande.client', 'typeBillet'])->findOrFail($id);

        // Generate QR code URL - DomPDF will embed it
        $qrCode = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($billet->code_unique);

        $pdf = Pdf::loadView('pdf.ticket', compact('billet', 'qrCode'))
                  ->setOption('isRemoteEnabled', true);

        return $pdf->download('ticket_' . $billet->code_unique . '.pdf');
    }

    public function valider($id)
    {
        $billet = Billet::whereHas('evenement', function ($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        // Mark the ticket as validated/scanned and update status to "Utilisé"
        $billet->update([
            'statut' => 'Utilisé',
            'date_validation' => now(),
            'valide_par' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Entrée validée avec succès!');
    }

    public function getTicketsByEvent($evenementId)
    {
        $evenement = \App\Models\Evenement::where('user_id', Auth::id())->findOrFail($evenementId);
        
        $billets = Billet::where('evenement_id', $evenementId)
            ->with(['typeBillet', 'commande.client'])
            ->get();
        
        return response()->json([
            'success' => true,
            'billets' => $billets,
            'evenement' => $evenement
        ]);
    }

    public function validateMultiple(Request $request)
    {
        $billetIds = $request->input('billet_ids', []);
        
        // Ensure it's an array
        if (!is_array($billetIds)) {
            $billetIds = [$billetIds];
        }

        if (empty($billetIds)) {
            return back()->with('error', 'Veuillez sélectionner au moins un billet.');
        }

        // Validate all billets belong to current organizer
        $billets = Billet::whereHas('evenement', function ($q) {
            $q->where('user_id', Auth::id());
        })->whereIn('id', $billetIds)->get();

        if ($billets->count() !== count($billetIds)) {
            return back()->with('error', 'Certains billets ne vous appartiennent pas.');
        }

        // Update all billets
        Billet::whereIn('id', $billetIds)->update([
            'statut' => 'Utilisé',
            'date_validation' => now(),
            'valide_par' => Auth::id()
        ]);

        return back()->with('success', $billets->count() . ' billet(s) validé(s) avec succès!');
    }
}