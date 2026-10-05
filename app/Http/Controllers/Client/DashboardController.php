<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Billet;
use App\Models\Commande;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $clientId = Auth::id();
            
            // Vérifier si l'utilisateur est connecté
            if (!$clientId) {
                Log::warning('Tentative d\'accès au dashboard sans authentification');
                return redirect()->route('login');
            }

            // Récupérer l'utilisateur
            /** @var \App\Models\User|null $user */
            $user = Auth::user();
            if (!$user) {
                Log::warning('Utilisateur authentifié introuvable après Auth::id()=%s', [$clientId]);
                return redirect()->route('login');
            }
            Log::info('Dashboard chargé pour l\'utilisateur ID: ' . $clientId);

            // 1. Commandes récentes
            $commandesRecentes = $user->commandes()
                ->with(['billets.evenement.lieu', 'billets.typeBillet'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // 2. Total des commandes
            $totalCommandes = $user->commandes()->count();

            // 3. Commandes confirmées
            $commandesConfirmees = $user->commandes()
                ->where('statut', 'Confirmée')
                ->count();

            // 4. Total des billets
            $totalBillets = $user->billets()->count();

            // 5. Billets valides (statut dans la table billets, éviter l'ambiguïté SQL)
            $billetsValides = $user->billets()
                ->where('billets.statut', 'Valide')
                ->count();

            // 6. Total dépensé
            $totalDepense = $user->commandes()
                ->where('statut', 'Confirmée')
                ->sum('montant_total');

            // 7. Événements à venir
            // Inclure les événements publiés du jour et au-delà
            $evenementsRecents = Evenement::where('date', '>=', now()->startOfDay())
                ->where('statut', 'Publié')
                ->with(['lieu', 'typesBillets'])
                ->orderBy('date', 'asc')
                ->take(6)
                ->get();

            // Si aucun événement à venir, afficher les événements déjà achetés par le client
            if ($evenementsRecents->isEmpty()) {
                $evenementsAchetes = Evenement::whereHas('billets.commande', function ($query) use ($clientId) {
                        $query->where('client_id', $clientId);
                    })
                    ->with(['lieu', 'typesBillets'])
                    ->orderBy('date', 'asc')
                    ->take(6)
                    ->get();

                if ($evenementsAchetes->isNotEmpty()) {
                    $evenementsRecents = $evenementsAchetes;
                }
            }

            // Si toujours aucun, afficher tous les événements publiés
            if ($evenementsRecents->isEmpty()) {
                $evenementsRecents = Evenement::where('statut', 'Publié')
                    ->with(['lieu', 'typesBillets'])
                    ->orderBy('date', 'asc')
                    ->take(6)
                    ->get();
            }

            // Dernier recours: afficher quelques événements quelle que soit leur statut, pour débogage & visiblité
            if ($evenementsRecents->isEmpty()) {
                $evenementsRecents = Evenement::with(['lieu', 'typesBillets'])
                    ->orderBy('date', 'asc')
                    ->take(6)
                    ->get();
            }

            // Calculer les places restantes pour chaque événement
            foreach ($evenementsRecents as $evenement) {
                $billetsVendus = Billet::where('evenement_id', $evenement->id)
                    ->whereHas('commande', function($q) {
                        $q->where('statut', 'Confirmée');
                    })
                    ->count();
                
                $evenement->billets_vendus = $billetsVendus;
                $evenement->places_restantes = $evenement->capacite - $billetsVendus;
                $evenement->prix_min = $evenement->typesBillets->min('prix') ?? 0;
            }

            // Log des données pour débogage
            Log::info('Données du dashboard', [
                'totalCommandes' => $totalCommandes,
                'commandesConfirmees' => $commandesConfirmees,
                'totalBillets' => $totalBillets,
                'billetsValides' => $billetsValides,
                'totalDepense' => $totalDepense,
                'evenementsCount' => $evenementsRecents->count()
            ]);

            return view('client.dashboard.index', compact(
                'user',
                'commandesRecentes',
                'totalCommandes',
                'commandesConfirmees',
                'totalBillets',
                'billetsValides',
                'totalDepense',
                'evenementsRecents'
            ));

        } catch (\Exception $e) {
            Log::error('Erreur dans le dashboard: '.$e->getMessage(), ['exception' => $e]);
            
            // En cas d'erreur, retourner avec des valeurs par défaut (doit être temporaire en dev)
            return view('client.dashboard.index', [
                'user' => Auth::user(),
                'commandesRecentes' => collect([]),
                'totalCommandes' => 0,
                'commandesConfirmees' => 0,
                'totalBillets' => 0,
                'billetsValides' => 0,
                'totalDepense' => 0,
                'evenementsRecents' => collect([]),
                'error' => 'Erreur lors du chargement du dashboard : '.$e->getMessage().' ('.$e->getCode().')'."\n".$e->getTraceAsString(),
            ]);
        }
    }
}