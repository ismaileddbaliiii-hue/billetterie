<?php

namespace App\Http\Controllers\Organisateur;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Evenement;
use App\Models\Commande;
use App\Models\Billet;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // Statistiques
        $totalEvenements = Evenement::where('user_id', $userId)->count();
        $evenementsPublies = Evenement::where('user_id', $userId)->where('statut', 'Publié')->count();
        $evenementsBrouillon = Evenement::where('user_id', $userId)->where('statut', 'Brouillon')->count();
        
        $totalBilletsVendus = Billet::whereHas('evenement', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->whereNotNull('commande_id')->count();
        
        $caTotal = Commande::whereHas('billets.evenement', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('statut', 'Confirmée')->sum('montant_total');
        
        // Événements récents
        $evenementsRecents = Evenement::where('user_id', $userId)
            ->with('lieu')
            ->withCount('billets as billets_vendus')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        // Top événements
        $topEvenements = Evenement::where('user_id', $userId)
            ->withCount(['billets as billets_vendus' => function($q) {
                $q->whereNotNull('commande_id');
            }])
            ->orderBy('billets_vendus', 'desc')
            ->limit(5)
            ->get()
            ->map(function($evenement) {
                $evenement->ca = $evenement->billets_vendus * ($evenement->billets->avg('prix') ?? 0);
                return $evenement;
            });
        
        // 🔧 FIX: Ventes par mois avec données par défaut
        $ventesParMois = $this->getVentesParMois($userId);
        
        // S'assurer qu'il y a toujours des données
        if ($ventesParMois->isEmpty()) {
            $moisLabels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'];
            $ventesData = [0, 0, 0, 0, 0, 0];
        } else {
            $moisLabels = $ventesParMois->keys()->map(function($mois) {
                return Carbon::createFromFormat('Y-m', $mois)->format('M');
            })->toArray();
            $ventesData = $ventesParMois->values()->toArray();
        }
        
        return view('organisateur.dashboard.index', compact(
            'totalEvenements',
            'evenementsPublies',
            'evenementsBrouillon',
            'totalBilletsVendus',
            'caTotal',
            'evenementsRecents',
            'topEvenements',
            'moisLabels',
            'ventesData'
        ));
    }

    private function getVentesParMois($userId)
    {
        $ventes = Commande::whereHas('billets.evenement', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->where('statut', 'Confirmée')
        ->whereYear('date', Carbon::now()->year)
        ->selectRaw('DATE_FORMAT(date, "%Y-%m") as mois, COUNT(DISTINCT commandes.id) as total')
        ->groupBy('mois')
        ->orderBy('mois')
        ->pluck('total', 'mois');
        
        // 🔧 FIX: Remplir les mois manquants avec 0
        $allMonths = collect();
        for ($i = 1; $i <= 6; $i++) {
            $date = Carbon::now()->subMonths(6 - $i);
            $key = $date->format('Y-m');
            $allMonths[$key] = $ventes[$key] ?? 0;
        }
        
        return $allMonths;
    }
}