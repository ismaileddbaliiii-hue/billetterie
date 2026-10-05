<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Evenement;
use App\Models\Commande;
use App\Models\Billet;
use App\Models\Paiement;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ========== STATISTIQUES GÉNÉRALES ==========
        $totalOrganisateurs = User::where('role', 'Organisateur')->count();
        $totalClients = User::where('role', 'Client')->count();
        $totalEvenements = Evenement::count();
        $evenementsPublies = Evenement::where('statut', 'Publié')->count();
        $evenementsBrouillon = Evenement::where('statut', 'Brouillon')->count();
        
        // Chiffre d'affaires total
        $caTotal = Commande::where('statut', 'Confirmée')->sum('montant_total');
        
        // Nouveaux inscrits
        $nouveauxOrganisateurs = User::where('role', 'Organisateur')
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->count();
        $nouveauxClients = User::where('role', 'Client')
            ->where('created_at', '>=', Carbon::now()->startOfWeek())
            ->count();
        
        // Billets vendus (from confirmed orders)
        $totalBilletsVendus = Billet::whereHas('commande', function($q) {
            $q->where('statut', 'Confirmée');
        })->count();
        
        // Revenus du mois
        $revenusMois = Commande::where('statut', 'Confirmée')
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('montant_total');
        
        // Revenus du mois dernier pour calculer la croissance
        $revenusMoisDernier = Commande::where('statut', 'Confirmée')
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->sum('montant_total');
        
        // Objectif du mois (20% de plus que le mois dernier)
        $objectifMois = $revenusMoisDernier * 1.2;
        
        // Croissance CA
        $croissanceCA = $revenusMoisDernier > 0 
            ? round((($revenusMois - $revenusMoisDernier) / $revenusMoisDernier) * 100, 1)
            : 0;
        
        // Transactions du jour
        $transactionsAujourdhui = Commande::whereDate('created_at', Carbon::today())->count();
        $montantAujourdhui = Commande::whereDate('created_at', Carbon::today())
            ->where('statut', 'Confirmée')
            ->sum('montant_total');
        
        // ========== DONNÉES POUR GRAPHIQUES ==========
        // Ventes sur 30 jours
        $ventesLabels = [];
        $ventesData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $ventesLabels[] = $date->format('d/m');
            $montantJour = Commande::whereDate('created_at', $date)
                ->where('statut', 'Confirmée')
                ->sum('montant_total');
            $ventesData[] = $montantJour > 0 ? $montantJour : 0;
        }
        
        // Top événements
        $topEvenements = Evenement::withCount('billets')
            ->orderBy('billets_count', 'desc')
            ->limit(5)
            ->get();
        $topEvenementsLabels = $topEvenements->pluck('titre')->toArray();
        $topEvenementsData = $topEvenements->pluck('billets_count')->toArray();
        
        // Statut événements
        $statutEvenementsLabels = ['Publiés', 'Brouillons', 'Terminés', 'Annulés'];
        $statutEvenementsData = [
            Evenement::where('statut', 'Publié')->count(),
            Evenement::where('statut', 'Brouillon')->count(),
            Evenement::where('statut', 'Terminé')->count(),
            Evenement::where('statut', 'Annulé')->count()
        ];
        
        // Méthodes de paiement
        $methodesPaiementLabels = ['Carte bancaire', 'PayPal', 'Virement', 'Autre'];
        $methodesPaiementData = [
            Paiement::where('prestataire', 'Stripe')->count(),
            Paiement::where('prestataire', 'PayPal')->count(),
            Paiement::where('prestataire', 'Virement')->count(),
            Paiement::whereNotIn('prestataire', ['Stripe', 'PayPal', 'Virement'])->count()
        ];
        
        // ========== LISTES RÉCENTES ==========
        // Derniers organisateurs
        $derniersOrganisateurs = User::where('role', 'Organisateur')
            ->withCount('evenements')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Dernières transactions
        $dernieresTransactions = Commande::with(['paiement', 'client'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($commande) {
                $commande->client_nom = $commande->client ? $commande->client->prenom . ' ' . $commande->client->nom : 'Client inconnu';
                $commande->montant = $commande->montant_total;
                $commande->prestataire = $commande->paiement ? $commande->paiement->prestataire : 'Carte';
                $commande->reference = $commande->reference ?? 'CMD-' . str_pad($commande->id, 6, '0', STR_PAD_LEFT);
                return $commande;
            });
        
        // Événements à venir
        $evenementsAVenir = Evenement::where('date', '>=', Carbon::now())
            ->where('statut', 'Publié')
            ->with('lieu', 'organisateur')
            ->orderBy('date', 'asc')
            ->limit(5)
            ->get()
            ->map(function($event) {
                $event->organisateur_nom = $event->organisateur ? $event->organisateur->prenom . ' ' . $event->organisateur->nom : 'Inconnu';
                $event->lieu_nom = $event->lieu ? $event->lieu->nom : 'Lieu non défini';
                $event->places_vendues = Billet::where('evenement_id', $event->id)->count();
                return $event;
            });
        
        // Activités récentes (exemples pour démonstration)
        $activitesRecentes = collect([
            (object)[
                'id' => 1,
                'description' => 'Nouvel organisateur inscrit: Jean Dupont',
                'user_nom' => 'Système',
                'created_at' => Carbon::now()->subHours(2),
                'icon' => 'fas fa-user-plus',
                'icon_bg' => 'bg-green-100',
                'icon_color' => 'text-green-600'
            ],
            (object)[
                'id' => 2,
                'description' => 'Nouvel événement créé: Concert Rock',
                'user_nom' => 'Jean Dupont',
                'created_at' => Carbon::now()->subHours(5),
                'icon' => 'fas fa-calendar-plus',
                'icon_bg' => 'bg-blue-100',
                'icon_color' => 'text-blue-600'
            ],
            (object)[
                'id' => 3,
                'description' => 'Paiement réussi: 450 DH',
                'user_nom' => 'Client',
                'created_at' => Carbon::now()->subHours(8),
                'icon' => 'fas fa-check-circle',
                'icon_bg' => 'bg-green-100',
                'icon_color' => 'text-green-600'
            ],
        ]);
        
        // ========== STATISTIQUES RAPIDES ==========
        $tauxConversion = $this->calculerTauxConversion();
        $panierMoyen = Commande::where('statut', 'Confirmée')->avg('montant_total') ?? 0;
        $tauxSatisfaction = 98.5;
        $tempsReponse = 2.3;
        
        return view('admin.dashboard.index', compact(
            'totalOrganisateurs',
            'totalClients',
            'totalEvenements',
            'evenementsPublies',
            'evenementsBrouillon',
            'caTotal',
            'nouveauxOrganisateurs',
            'nouveauxClients',
            'totalBilletsVendus',
            'revenusMois',
            'objectifMois',
            'croissanceCA',
            'transactionsAujourdhui',
            'montantAujourdhui',
            'ventesLabels',
            'ventesData',
            'topEvenementsLabels',
            'topEvenementsData',
            'statutEvenementsLabels',
            'statutEvenementsData',
            'methodesPaiementLabels',
            'methodesPaiementData',
            'derniersOrganisateurs',
            'dernieresTransactions',
            'evenementsAVenir',
            'activitesRecentes',
            'tauxConversion',
            'panierMoyen',
            'tauxSatisfaction',
            'tempsReponse'
        ));
    }
    
    private function calculerTauxConversion()
    {
        // Nombre de visiteurs uniques (à implémenter avec Google Analytics)
        $visiteurs = 1000;
        $achats = Commande::where('statut', 'Confirmée')->count();
        
        return $visiteurs > 0 ? round(($achats / $visiteurs) * 100, 1) : 0;
    }
    
    public function ventesData(Request $request)
    {
        $period = $request->get('period', 30);
        $labels = [];
        $values = [];
        
        for ($i = $period - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d/m');
            $values[] = Commande::whereDate('created_at', $date)
                ->where('statut', 'Confirmée')
                ->sum('montant_total');
        }
        
        return response()->json([
            'success' => true,
            'labels' => $labels,
            'values' => $values
        ]);
    }
}