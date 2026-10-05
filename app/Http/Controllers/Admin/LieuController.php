<?php
// app/Http/Controllers/Admin/LieuController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lieu;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LieuController extends Controller
{
    /**
     * Afficher la liste des lieux
     */
    public function index(Request $request)
    {
        $query = Lieu::query();
        
        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('ville', 'LIKE', "%{$search}%")
                  ->orWhere('adresse', 'LIKE', "%{$search}%");
            });
        }
        
        $lieux = $query->withCount('evenements')->orderBy('nom')->paginate(15);
        
        $stats = [
            'total' => Lieu::count(),
            'avec_evenements' => Lieu::has('evenements')->count(),
            'sans_evenements' => Lieu::doesntHave('evenements')->count(),
            'capacite_totale' => Lieu::sum('capacite'),
        ];
        
        $this->createNotification(
            auth()->id(),
            'Consultation des lieux',
            "Vous avez consulté la liste des lieux. Total: {$stats['total']} lieux",
            'info',
            'admin.lieux.index'
        );
        
        return view('admin.lieux.index', compact('lieux', 'stats'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.lieux.create');
    }

    /**
     * Enregistrer un nouveau lieu
     */
    public function store(Request $request)
    {
        if (!auth()->check() || auth()->user()->role !== 'Admin') {
            // Sécurité : si le rôle est incorrect, on force la connexion
            return redirect()->route('login');
        }

        $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string',
            'ville' => 'required|string|max:100',
            'code_postal' => 'nullable|string|max:20',
            'capacite' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);
        
        try {
            DB::beginTransaction();
            
            $lieu = Lieu::create([
                'nom' => $request->nom,
                'adresse' => $request->adresse,
                'ville' => $request->ville,
                'code_postal' => $request->code_postal,
                'capacite' => $request->capacite,
                'description' => $request->description,
            ]);
            
            $this->createNotification(
                auth()->id(),
                'Lieu créé',
                "Vous avez créé le lieu \"{$lieu->nom}\" avec succès",
                'success',
                'admin.lieux.show',
                ['lieu_id' => $lieu->id]
            );
            
            DB::commit();

            return redirect()
                ->to('/admin/lieux')
                ->with('success', 'Lieu créé avec succès !');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->createNotification(
                auth()->id(),
                'Erreur création lieu',
                "Une erreur est survenue lors de la création: " . $e->getMessage(),
                'error',
                null,
                ['error' => $e->getMessage()]
            );
            
            return back()
                ->with('error', 'Erreur lors de la création: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Afficher le détail d'un lieu
     */
    public function show(Lieu $lieu)
    {
        $lieu->load(['evenements' => function($query) {
            $query->with(['organisateur', 'typesBillets'])
                  ->withCount('billets')
                  ->latest()
                  ->limit(10);
        }]);
        
        $stats = [
            'total_evenements' => $lieu->evenements()->count(),
            'evenements_futurs' => $lieu->evenements()->where('date', '>=', now())->count(),
            'evenements_passes' => $lieu->evenements()->where('date', '<', now())->count(),
            'total_billets' => $lieu->evenements()->withCount('billets')->get()->sum('billets_count'),
        ];
        
        $this->createNotification(
            auth()->id(),
            'Consultation lieu',
            "Vous avez consulté le lieu \"{$lieu->nom}\"",
            'info',
            'admin.lieux.show',
            ['lieu_id' => $lieu->id]
        );
        
        return view('admin.lieux.show', compact('lieu', 'stats'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(Lieu $lieu)
    {
        return view('admin.lieux.edit', compact('lieu'));
    }

    /**
     * Mettre à jour un lieu
     */
    public function update(Request $request, Lieu $lieu)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string',
            'ville' => 'required|string|max:100',
            'code_postal' => 'nullable|string|max:20',
            'capacite' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);
        
        try {
            DB::beginTransaction();
            
            $ancienNom = $lieu->nom;
            
            $lieu->update([
                'nom' => $request->nom,
                'adresse' => $request->adresse,
                'ville' => $request->ville,
                'code_postal' => $request->code_postal,
                'capacite' => $request->capacite,
                'description' => $request->description,
            ]);
            
            $this->createNotification(
                auth()->id(),
                'Lieu modifié',
                "Vous avez modifié le lieu \"{$ancienNom}\" en \"{$lieu->nom}\"",
                'info',
                'admin.lieux.show',
                ['lieu_id' => $lieu->id]
            );
            
            DB::commit();
            
            return redirect()
                ->route('admin.lieux.index')
                ->with('success', 'Lieu modifié avec succès !');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Erreur lors de la modification: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Supprimer un lieu
     */
    public function destroy(Lieu $lieu)
    {
        $nombreEvenements = $lieu->evenements()->count();
        
        if ($nombreEvenements > 0) {
            return back()->with('error', "Impossible de supprimer ce lieu car il est utilisé par {$nombreEvenements} événement(s).");
        }
        
        try {
            DB::beginTransaction();
            
            $nom = $lieu->nom;
            $lieu->delete();
            
            $this->createNotification(
                auth()->id(),
                'Lieu supprimé',
                "Vous avez supprimé le lieu \"{$nom}\"",
                'warning',
                'admin.lieux.index'
            );
            
            DB::commit();
            
            return redirect()
                ->route('admin.lieux.index')
                ->with('success', 'Lieu supprimé avec succès !');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }
    
    /**
     * Exporter les lieux
     */
    public function export()
    {
        $lieux = Lieu::withCount('evenements')->get();
        
        $csv = "ID,Nom,Adresse,Ville,Code postal,Capacité,Événements,Date création\n";
        
        foreach ($lieux as $lieu) {
            $csv .= "{$lieu->id},{$lieu->nom},{$lieu->adresse},{$lieu->ville},";
            $csv .= "{$lieu->code_postal},{$lieu->capacite},{$lieu->evenements_count},";
            $csv .= "{$lieu->created_at->format('d/m/Y H:i')}\n";
        }
        
        $this->createNotification(
            auth()->id(),
            'Export des lieux',
            "Vous avez exporté " . $lieux->count() . " lieux",
            'info',
            'admin.lieux.index'
        );
        
        return response($csv)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="lieux_' . date('Y-m-d') . '.csv"');
    }
    
    /**
     * Créer une notification
     */
    private function createNotification($userId, $title, $message, $type = 'info', $route = null, $data = null)
    {
        try {
            Notification::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => $data,
                'is_read' => false
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur création notification', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'title' => $title
            ]);
        }
    }
}