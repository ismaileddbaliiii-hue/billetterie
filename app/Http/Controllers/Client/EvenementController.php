<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use App\Models\Lieu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class EvenementController extends Controller
{
    public function index(Request $request)
    {
        $query = Evenement::where(function($q) {
                    $q->where('statut', 'Publié');
                    if (Auth::check()) {
                        $q->orWhere('user_id', Auth::id());
                    }
                })
                ->whereDate('date', '>=', today())
                ->with(['lieu', 'typesBillets'])
                ->withCount('billets as billets_vendus');
        
        // Filtres
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('titre', 'like', '%' . $request->search . '%')
                  ->orWhereHas('lieu', function($q2) use ($request) {
                      $q2->where('nom', 'like', '%' . $request->search . '%')
                         ->orWhere('ville', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }
        
        if ($request->filled('lieu_id')) {
            $query->where('lieu_id', $request->lieu_id);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        $evenements = $query->orderBy('date', 'asc')->paginate(12);
        $lieux = Lieu::orderBy('nom')->get();

        foreach ($evenements as $evenement) {
            $evenement->places_restantes = max(0, $evenement->capacite - ($evenement->billets_vendus ?? 0));
            $evenement->prix_min = $evenement->typesBillets->min('prix') ?? 0;
        }

        $evenements_disponibles = $evenements->filter(function ($evenement) {
            return $evenement->places_restantes > 0;
        })->count();
        
        return view('client.evenements.index', compact('evenements', 'lieux', 'evenements_disponibles'));
    }
    
    public function show(Evenement $evenement)
    {
        if ($evenement->statut !== 'Publié' || Carbon::parse($evenement->date)->lt(today())) {
            abort(404);
        }
        
        $evenement->load(['lieu', 'typesBillets']);
        $evenement->loadCount('billets as billets_vendus');

        // calcul du prix min à partir des types de billets
        $evenement->prix_min = $evenement->typesBillets->min('prix') ?? 0;
        $evenement->places_restantes = max(0, $evenement->capacite - ($evenement->billets_vendus ?? 0));

        // Définir les types de billets à afficher : uniquement les types encore disponibles
        $typesBillets = $evenement->typesBillets
            ->filter(fn($type) => $type->places_disponibles > 0 && $type->vente_active)
            ->mapWithKeys(fn($type) => [
                $type->nom => [
                    'prix' => $type->prix,
                    'disponible' => $type->places_disponibles,
                ],
            ])
            ->toArray();

        if ($evenement->typesBillets->isEmpty() && empty($typesBillets)) {
            $eventType = $evenement->type ?? 'Standard';
            $typesBillets = [
                $eventType => [
                    'prix' => $evenement->prix_min,
                    'disponible' => $evenement->places_restantes,
                ],
            ];
        }

        return view('client.evenements.show', compact('evenement', 'typesBillets'));
    }
}