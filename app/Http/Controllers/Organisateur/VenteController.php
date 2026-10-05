<?php

namespace App\Http\Controllers\Organisateur;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Evenement;
use App\Models\TypeBillet;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class VenteController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        $query = Commande::whereHas('billets.evenement', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('statut', 'Confirmée')
          ->with(['client', 'billets.evenement']);
        
        if ($request->filled('evenement_id')) {
            $query->whereHas('billets', function($q) use ($request) {
                $q->where('evenement_id', $request->evenement_id);
            });
        }
        
        if ($request->filled('date_debut')) {
            $query->whereDate('date', '>=', $request->date_debut);
        }
        
        if ($request->filled('date_fin')) {
            $query->whereDate('date', '<=', $request->date_fin);
        }
        
        $ventes = $query->orderBy('date', 'desc')->paginate(20);
        
        $totalVentes = $query->count();
        $caTotal = $query->sum('montant_total');
        $totalBilletsVendus = $ventes->sum(function($vente) {
            return $vente->billets->count();
        });
        
        $evenements = Evenement::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->get();
        
        return view('organisateur.ventes.index', compact(
            'ventes', 'totalVentes', 'caTotal', 'totalBilletsVendus', 'evenements'
        ));
    }
    
    public function byEvent($evenementId)
    {
        $evenement = Evenement::where('user_id', Auth::id())->findOrFail($evenementId);
        
        $ventes = Commande::whereHas('billets', function($q) use ($evenementId) {
            $q->where('evenement_id', $evenementId);
        })->where('statut', 'Confirmée')
          ->with('client', 'billets')
          ->orderBy('date', 'desc')
          ->paginate(20);
        
        $totalVentes = $ventes->total();
        $caTotal = $ventes->sum('montant_total');
        $totalBillets = $ventes->sum(function($vente) {
            return $vente->billets->count();
        });

        $typesBillets = TypeBillet::where('evenement_id', $evenementId)->get();

        return view('organisateur.ventes.event', compact(
            'evenement', 'ventes', 'totalVentes', 'caTotal', 'totalBillets', 'typesBillets'
        ));
    }

    public function show(Commande $commande)
    {
        $userId = Auth::id();

        $isOwner = $commande->billets()->whereHas('evenement', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->exists();

        if (! $isOwner) {
            abort(403, 'Commande non autorisée.');
        }

        $commande->load('client', 'evenement', 'billets.evenement', 'billets.typeBillet');

        return view('organisateur.ventes.show', compact('commande'));
    }

    public function facture(Commande $commande)
    {
        $userId = Auth::id();

        $isOwner = $commande->billets()->whereHas('evenement', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->exists();

        if (! $isOwner) {
            abort(403, 'Commande non autorisée.');
        }

        $commande->load('client', 'evenement', 'billets.evenement', 'billets.typeBillet', 'paiement');

        return view('organisateur.ventes.facture', compact('commande'));
    }

    public function billets(Commande $commande)
    {
        $userId = Auth::id();

        $isOwner = $commande->billets()->whereHas('evenement', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->exists();

        if (! $isOwner) {
            abort(403, 'Commande non autorisée.');
        }

        $commande->load('client', 'evenement', 'billets.evenement', 'billets.typeBillet');

        return view('organisateur.ventes.show', compact('commande'));
    }
    
    public function sendInvoice(Commande $commande)
    {
        $userId = Auth::id();

        $isOwner = $commande->billets()->whereHas('evenement', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->exists();

        if (! $isOwner) {
            return response()->json(['success' => false, 'message' => 'Commande non autorisée.'], 403);
        }

        $commande->load('client', 'evenement', 'billets.evenement', 'billets.typeBillet', 'paiement');

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
        ])->loadView('organisateur.ventes.facture', compact('commande'))->setPaper('a4', 'portrait');

        Mail::send('emails.organisateur.invoice', ['commande' => $commande], function ($message) use ($commande, $pdf) {
            $message->to($commande->client->email, $commande->client->prenom . ' ' . $commande->client->nom)
                    ->subject('Facture commande #' . $commande->id)
                    ->attachData($pdf->output(), 'facture_' . $commande->id . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
        });

        return response()->json(['success' => true]);
    }

    public function downloadFacture(Commande $commande)
    {
        $userId = Auth::id();

        $isOwner = $commande->billets()->whereHas('evenement', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->exists();

        if (! $isOwner) {
            abort(403, 'Commande non autorisée.');
        }

        $commande->load('client', 'evenement', 'billets.evenement', 'billets.typeBillet', 'paiement');

        // Ensure font cache directory exists for Dompdf
        $fontDir = storage_path('fonts');
        if (! file_exists($fontDir)) {
            mkdir($fontDir, 0755, true);
        }

        $pdf = Pdf::setOptions([
            'defaultFont' => 'sans-serif',
            'isRemoteEnabled' => true,
            'fontDir' => $fontDir,
            'fontCache' => $fontDir,
        ])->loadView('organisateur.ventes.facture_pdf', compact('commande'))->setPaper('a4', 'portrait');

        return $pdf->download('facture_' . $commande->id . '.pdf');
    }

    public function export(Request $request)
    {
        $this->checkPermission('perm_view_stats');
        
        $userId = Auth::id();
        
        $query = Commande::whereHas('billets.evenement', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('statut', 'Confirmée')
          ->with(['client', 'billets.evenement']);
        
        if ($request->filled('evenement_id')) {
            $query->whereHas('billets', function($q) use ($request) {
                $q->where('evenement_id', $request->evenement_id);
            });
        }
        
        if ($request->filled('date_debut')) {
            $query->whereDate('date', '>=', $request->date_debut);
        }
        
        if ($request->filled('date_fin')) {
            $query->whereDate('date', '<=', $request->date_fin);
        }
        
        $ventes = $query->orderBy('date', 'desc')->get();
        
        $csv = "ID Commande,Date,Client,Email,Événement,Type Billet,Prix,Total\n";
        
        foreach ($ventes as $vente) {
            foreach ($vente->billets as $billet) {
                $csv .= sprintf(
                    "%d,%s,%s,%s,%s,%s,%.2f,%.2f\n",
                    $vente->id,
                    $vente->date,
                    $vente->client->prenom . ' ' . $vente->client->nom,
                    $vente->client->email,
                    $billet->evenement->titre,
                    $billet->typeBillet->nom ?? 'Standard',
                    $billet->prix,
                    $vente->montant_total
                );
            }
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="ventes_' . date('Y-m-d') . '.csv"');
    }
}