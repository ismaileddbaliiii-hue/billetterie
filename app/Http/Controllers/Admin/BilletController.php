<?php

namespace App\Http\Controllers\Admin;

use App\Models\Billet;
use App\Models\Evenement;
use App\Models\Commande;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;

class BilletController extends Controller
{
    /**
     * Display a listing of tickets
     */
    public function index(Request $request)
    {
        $query = Billet::with([
            'commande',
            'commande.client',
            'typeBillet',
            'evenement',
        ]);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhereHas('commande', function ($subQ) use ($search) {
                      $subQ->where('numero_commande', 'like', "%{$search}%");
                  })
                  ->orWhereHas('commande.client', function ($subQ) use ($search) {
                      $subQ->where('email', 'like', "%{$search}%")
                          ->orWhere('prenom', 'like', "%{$search}%")
                          ->orWhere('nom', 'like', "%{$search}%");
                  });
            });
        }

        // Event filter
        if ($request->filled('evenement_id')) {
            $query->where('evenement_id', $request->evenement_id);
        }

        // Client filter
        if ($request->filled('client_id')) {
            $query->whereHas('commande', function ($q) use ($request) {
                $q->where('client_id', $request->client_id);
            });
        }

        // Status filter
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Validation filter
        if ($request->filled('valide')) {
            $valide = $request->valide;
            if ($valide == 'utilise') {
                $query->where('statut', 'Utilisé');
            } elseif ($valide == 'disponible') {
                $query->where('statut', 'Valide');
            } elseif ($valide == 'annule') {
                $query->where('statut', 'Annulé');
            }
        }

        $billets = $query->orderBy('created_at', 'desc')->paginate(15);
        $evenements = Evenement::all();
        $clients = User::whereHas('commandes')->orderBy('nom')->orderBy('prenom')->get();

        return view('admin.billets.index', compact('billets', 'evenements', 'clients'));
    }

    /**
     * Show ticket details
     */
    public function show(Billet $billet)
    {
        $billet->load(['commande.client', 'typeBillet', 'evenement', 'evenement.organisateur', 'evenement.lieu']);
        
        return view('admin.billets.show', compact('billet'));
    }

    /**
     * Validate/Mark ticket as used
     */
    public function valider(Billet $billet)
    {
        try {
            $billet->update([
                'statut' => 'Utilisé',
                'date_validation' => now(),
                'valide_par' => Auth::id(),
            ]);
            
            return redirect()->back()->with('success', 'Billet marqué comme utilisé avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la validation du billet : ' . $e->getMessage());
        }
    }

    /**
     * Delete ticket
     */
    public function destroy(Billet $billet)
    {
        try {
            $billetCode = $billet->code;
            $billet->delete();
            
            return redirect()->route('admin.billets.index')->with('success', "Billet {$billetCode} supprimé avec succès !");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la suppression du billet : ' . $e->getMessage());
        }
    }

    /**
     * Download ticket PDF
     */
    public function download(Billet $billet)
    {
        $billet->load(['evenement', 'commande.client', 'typeBillet']);
        
        $qrCode = QrCode::format('svg')
            ->size(200)
            ->generate($billet->code);

        $pdf = Pdf::loadView('pdf.ticket', [
            'billet' => $billet,
            'qrCode' => 'data:image/svg+xml;base64,' . base64_encode($qrCode),
        ]);

        return $pdf->download("billet-{$billet->code}.pdf");
    }

    /**
     * Get filtered ticket IDs for bulk selection
     */
    public function getFilteredIds(Request $request)
    {
        $query = Billet::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhereHas('commande', function ($subQ) use ($search) {
                      $subQ->where('numero_commande', 'like', "%{$search}%");
                  })
                  ->orWhereHas('commande.client', function ($subQ) use ($search) {
                      $subQ->where('email', 'like', "%{$search}%")
                          ->orWhere('prenom', 'like', "%{$search}%")
                          ->orWhere('nom', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('evenement_id')) {
            $query->where('evenement_id', $request->evenement_id);
        }

        if ($request->filled('client_id')) {
            $query->whereHas('commande', function ($q) use ($request) {
                $q->where('client_id', $request->client_id);
            });
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $ids = $query->pluck('id')->toArray();

        return response()->json(['ids' => $ids]);
    }

    /**
     * Export tickets as CSV
     */
    public function export(Request $request)
    {
        $query = Billet::with([
            'commande',
            'commande.client',
            'typeBillet',
            'evenement',
        ]);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhereHas('commande', function ($subQ) use ($search) {
                      $subQ->where('numero_commande', 'like', "%{$search}%");
                  })
                  ->orWhereHas('commande.client', function ($subQ) use ($search) {
                      $subQ->where('email', 'like', "%{$search}%")
                          ->orWhere('prenom', 'like', "%{$search}%")
                          ->orWhere('nom', 'like', "%{$search}%");
                  });
            });
        }

        // Event filter
        if ($request->filled('evenement_id')) {
            $query->where('evenement_id', $request->evenement_id);
        }

        // Client filter
        if ($request->filled('client_id')) {
            $query->whereHas('commande', function ($q) use ($request) {
                $q->where('client_id', $request->client_id);
            });
        }

        // Status filter
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Validation filter
        if ($request->filled('valide')) {
            $valide = $request->valide;
            if ($valide == 'utilise') {
                $query->where('statut', 'Utilisé');
            } elseif ($valide == 'disponible') {
                $query->where('statut', 'Valide');
            } elseif ($valide == 'annule') {
                $query->where('statut', 'Annulé');
            }
        }

        $billets = $query->orderBy('created_at', 'desc')->get();

        // Generate CSV
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=billets_export.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('Code', 'Événement', 'Client', 'Email', 'Type', 'Statut', 'Validé', 'Date Création');

        $callback = function() use ($billets, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($billets as $billet) {
                fputcsv($file, array(
                    $billet->code,
                    $billet->evenement?->titre ?? 'N/A',
                    trim(($billet->commande?->client?->prenom ?? '') . ' ' . ($billet->commande?->client?->nom ?? '')),
                    $billet->commande?->client?->email ?? 'N/A',
                    $billet->typeBillet?->nom ?? 'N/A',
                    $billet->statut ?? 'N/A',
                    $billet->statut === 'Utilisé' ? 'Oui' : 'Non',
                    $billet->created_at?->format('d/m/Y H:i') ?? 'N/A',
                ));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk validate tickets
     */
    public function validateMultiple(Request $request)
    {
        $request->validate([
            'billets' => 'required|array',
            'billets.*' => 'exists:billets,id'
        ]);

        try {
            Billet::whereIn('id', $request->billets)->update([
                'valide' => true,
                'valide_at' => now()
            ]);

            $count = count($request->billets);
            return redirect()->back()->with('success', "{$count} billet(s) marqué(s) comme utilisé(s) !");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la validation : ' . $e->getMessage());
        }
    }

    /**
     * Bulk validate selected tickets
     */
    public function bulkValidate(Request $request)
    {
        $request->validate([
            'billet_ids' => 'required|string'
        ]);

        try {
            $billetIds = json_decode($request->billet_ids, true);
            
            if (empty($billetIds)) {
                return redirect()->back()->with('error', 'Aucun billet sélectionné.');
            }

            Billet::whereIn('id', $billetIds)->update([
                'statut' => 'Utilisé',
                'date_validation' => now()
            ]);

            $count = count($billetIds);
            return redirect()->back()->with('success', "{$count} billet(s) marqué(s) comme validé(s) avec succès !");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la validation groupée : ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete selected tickets
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'billet_ids' => 'required|string'
        ]);

        try {
            $billetIds = json_decode($request->billet_ids, true);
            
            if (empty($billetIds)) {
                return redirect()->back()->with('error', 'Aucun billet sélectionné.');
            }

            $count = Billet::whereIn('id', $billetIds)->delete();
            
            return redirect()->back()->with('success', "{$count} billet(s) supprimé(s) avec succès !");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la suppression groupée : ' . $e->getMessage());
        }
    }

    /**
     * Validate all valid tickets
     */
    public function validateAll(Request $request)
    {
        try {
            $count = Billet::where('statut', 'Valide')->update([
                'statut' => 'Utilisé',
                'date_validation' => now()
            ]);
            
            return redirect()->back()->with('success', "{$count} billet(s) validé(s) avec succès ! Tous les billets valides ont été marqués comme utilisés.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la validation de tous les billets : ' . $e->getMessage());
        }
    }
}
