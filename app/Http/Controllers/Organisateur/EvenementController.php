<?php

namespace App\Http\Controllers\Organisateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Evenement;
use App\Models\Lieu;
use App\Models\Billet;
use App\Models\TypeBillet;
use Illuminate\Support\Str;

class EvenementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $evenements = Evenement::where('user_id', Auth::id())
            ->with('lieu')
            ->withCount('billets as billets_vendus')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('organisateur.evenements.index', compact('evenements'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkPermissionEvenement('perm_creation_event');
        
        $lieux = Lieu::orderBy('nom')->get();
        
        // Types de billets disponibles depuis la base de données
        $availableTypes = \App\Models\TicketTypeOption::active()
            ->ordered()
            ->pluck('label', 'name')
            ->toArray();
        
        return view('organisateur.evenements.create', compact('lieux', 'availableTypes'));
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkPermissionEvenement('perm_creation_event');
        
        // Get available ticket types for validation
        $availableTypeNames = \App\Models\TicketTypeOption::active()
            ->pluck('name')
            ->toArray();
        
        $rules = [
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:100',
            'date' => 'required|date|after:now',
            'lieu_id' => 'nullable|exists:lieux,id',
            'capacite' => 'required|integer|min:1',
            'statut' => 'required|in:Brouillon,Publié',
            'affiche' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'types' => 'required|array|min:1|max:3',
            'types.*.id' => 'nullable|exists:types_billets,id',
            'types.*.type' => 'required|string|in:' . implode(',', $availableTypeNames),
            'types.*.prix' => 'required|numeric|min:0',
            'types.*.quantite' => 'nullable|numeric|min:0',
            'types.*._destroy' => 'nullable|in:0,1',
        ];

        // Validation for new venue if no existing venue selected
        if (!$request->filled('lieu_id')) {
            $rules['nouveau_lieu_nom'] = 'required|string|max:255';
            $rules['nouveau_lieu_adresse'] = 'required|string|max:255';
            $rules['nouveau_lieu_ville'] = 'required|string|max:100';
            $rules['nouveau_lieu_capacite'] = 'nullable|integer|min:1';
            $rules['nouveau_lieu_code_postal'] = 'nullable|string|max:20';
        }

        $validated = $request->validate($rules, [
            'types.*.type.in' => 'Le type de billet sélectionné n\'est pas valide.',
        ]);

        // Filter out types marked for deletion (soft delete flag)
        $validated['types'] = collect($validated['types'] ?? [])
            ->reject(fn($type) => isset($type['_destroy']) && $type['_destroy'] === '1')
            ->values()
            ->toArray();

        // Filter out empty/invalid types (only keep those with non-empty names)
        $validated['types'] = collect($validated['types'] ?? [])
            ->filter(fn($type) => !empty($type['type']) && trim($type['type']) !== '')
            ->values()
            ->toArray();

        // Check if we have at least one valid type
        if (empty($validated['types'])) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['types' => 'Vous devez configurer au moins un type de billet valide.']);
        }

        // Validate that type names are unique within this event
        $typeNames = collect($validated['types'])->pluck('type')->toArray();
        if (count($typeNames) !== count(array_unique($typeNames))) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['types' => 'Chaque type de billet doit avoir un nom unique.']);
        }

        // Convert null quantities to 0
        foreach ($validated['types'] as &$type) {
            $type['quantite'] = $type['quantite'] ?? 0;
            $type['prix'] = floatval($type['prix']);
            $type['quantite'] = intval($type['quantite']);
        }
        unset($type);

        // Check total quantity vs capacity
        $totTypeQuantite = collect($validated['types'])->sum('quantite');
        if ($totTypeQuantite > $validated['capacite']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['types' => 'Le total des quantités de billets ne peut pas dépasser la capacité totale de l\'événement.']);
        }

        // Handle venue: existing or new
        $lieuId = $validated['lieu_id'] ?? null;
        
        if (!$lieuId && $request->filled('nouveau_lieu_nom')) {
            $lieu = Lieu::create([
                'nom' => $request->nouveau_lieu_nom,
                'adresse' => $request->nouveau_lieu_adresse,
                'ville' => $request->nouveau_lieu_ville,
                'code_postal' => $request->nouveau_lieu_code_postal,
                'capacite' => $request->nouveau_lieu_capacite,
            ]);
            $lieuId = $lieu->id;
        }

        if (!$lieuId) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['lieu_id' => 'Veuillez sélectionner un lieu existant ou créer un nouveau lieu.']);
        }

        // Handle image upload
        $affichePath = null;
        if ($request->hasFile('affiche')) {
            $affichePath = $request->file('affiche')->store('events/affiches', 'public');
        }

        // Create the event
        $evenement = Evenement::create([
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'date' => $validated['date'],
            'lieu_id' => $lieuId,
            'capacite' => $validated['capacite'],
            'statut' => $validated['statut'],
            'user_id' => Auth::id(),
            'affiche' => $affichePath,
        ]);
        
        // Create ticket types
        foreach ($validated['types'] as $type) {
            TypeBillet::create([
                'evenement_id' => $evenement->id,
                'nom' => $type['type'],
                'prix' => $type['prix'],
                'quantite' => $type['quantite'],
                'quantite_vendue' => 0,
                'actif' => true,
            ]);
        }
        
        return redirect()->route('organisateur.evenements.show', $evenement)
            ->with('success', '✅ Événement créé avec succès !');
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Evenement $evenement)
    {
        $this->authorizeEvent($evenement);
        
        $evenement->load('lieu');
        $evenement->loadCount('billets as billets_vendus');
        
        // ✅ Get ticket types grouped by name for display (NO DELETION!)
        $typesBillets = TypeBillet::where('evenement_id', $evenement->id)
            ->orderBy('id')
            ->get()
            ->groupBy('nom')
            ->map(function($group) {
                $first = $group->first();
                // Merge quantities for display purposes only
                $first->quantite = $group->sum('quantite');
                $first->quantite_vendue = $group->sum('quantite_vendue');
                // ⚠️ IMPORTANT: Do NOT delete duplicates here!
                return $first;
            })
            ->values();
        
        return view('organisateur.evenements.show', compact('evenement', 'typesBillets'));
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evenement $evenement)
    {
        $this->checkPermissionEvenement('perm_modification_event');
        $this->authorizeEvent($evenement);
        
        if ($evenement->statut === 'Terminé') {
            return redirect()->route('organisateur.evenements.show', $evenement)
                ->with('error', 'Impossible de modifier un événement terminé.');
        }
        
        $lieux = Lieu::orderBy('nom')->get();
        
        // Available ticket types from database
        $availableTypes = \App\Models\TicketTypeOption::active()
            ->ordered()
            ->pluck('label', 'name')
            ->toArray();
        
        // Load ticket types for this event, deduplicated by name for the form
        $allTypes = TypeBillet::where('evenement_id', $evenement->id)
            ->orderBy('id')
            ->get();
        
        // Keep only first occurrence of each type name for editing
        $seenNames = [];
        $typeArray = [];
        
        foreach ($allTypes as $type) {
            if (!in_array($type->nom, $seenNames)) {
                $typeArray[] = [
                    'id' => $type->id,
                    'type' => $type->nom,
                    'prix' => (float) $type->prix,
                    'quantite' => (int) $type->quantite,
                    '_destroy' => '0',
                    'key' => 'type_' . $type->id
                ];
                $seenNames[] = $type->nom;
            }
        }
        
        // Ensure at least one type exists
        if (empty($typeArray)) {
            $typeArray[] = [
                'id' => null,
                'type' => 'Standard',
                'prix' => 0,
                'quantite' => 0,
                '_destroy' => '0',
                'key' => 'new_standard'
            ];
        }
        
        $typesBillets = $typeArray;
        
        return view('organisateur.evenements.edit', compact('evenement', 'lieux', 'typesBillets', 'availableTypes'));
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evenement $evenement)
    {
        $this->checkPermission('perm_modification_event');
        $this->authorizeEvent($evenement);
        
        if ($evenement->statut === 'Terminé') {
            return redirect()->back()->with('error', 'Impossible de modifier un événement terminé.');
        }
        
        // Get available ticket types for validation (more flexible for updates)
        $availableTypeNames = \App\Models\TicketTypeOption::active()
            ->pluck('name')
            ->toArray();
        
        $rules = [
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:100',
            'lieu_id' => 'required|exists:lieux,id',
            'capacite' => 'required|integer|min:1',
            'statut' => 'required|in:Brouillon,Publié,Annulé',
            'affiche' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'types' => 'nullable|array|min:1|max:3',
            'types.*.id' => 'nullable|exists:types_billets,id',
            'types.*.type' => 'required_with:types|string|in:' . implode(',', $availableTypeNames),
            'types.*.prix' => 'required_with:types|numeric|min:0',
            'types.*.quantite' => 'nullable|numeric|min:0',
            'types.*._destroy' => 'nullable|in:0,1',
        ];

        $validated = $request->validate($rules, [
            'types.*.type.in' => 'Le type de billet sélectionné n\'est pas valide.',
        ]);

        // Handle ticket type deletions first
        $allTypes = $request->input('types', []);
        $typesToDelete = collect($allTypes)
            ->filter(fn($type) => isset($type['_destroy']) && $type['_destroy'] === '1' && !empty($type['id']))
            ->pluck('id')
            ->toArray();
        
        if (!empty($typesToDelete)) {
            TypeBillet::whereIn('id', $typesToDelete)->delete();
        }
        
        // Filter validated types: remove destroyed and empty
        $validated['types'] = collect($validated['types'] ?? [])
            ->reject(fn($type) => isset($type['_destroy']) && $type['_destroy'] === '1')
            ->filter(fn($type) => !empty($type['type']) && trim($type['type']) !== '')
            ->values()
            ->toArray();

        // Check if we have at least one valid type
        if (empty($validated['types'])) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['types' => 'Vous devez configurer au moins un type de billet valide.']);
        }

        // Validate unique type names
        $typeNames = collect($validated['types'])->pluck('type')->toArray();
        if (count($typeNames) !== count(array_unique($typeNames))) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['types' => 'Chaque type de billet doit avoir un nom unique.']);
        }

        // Sanitize and convert values
        foreach ($validated['types'] as &$type) {
            $type['quantite'] = $type['quantite'] ?? 0;
            $type['prix'] = floatval($type['prix']);
            $type['quantite'] = intval($type['quantite']);
        }
        unset($type);

        // Check capacity vs sold tickets
        $billetsVendus = Billet::where('evenement_id', $evenement->id)
            ->whereNotNull('commande_id')
            ->count();

        if ($validated['capacite'] < $billetsVendus) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['capacite' => 'La capacité ne peut pas être inférieure au nombre de billets déjà vendus ('.$billetsVendus.').']);
        }
        
        // Check total quantity vs capacity
        $totTypeQuantite = collect($validated['types'])->sum('quantite');
        if ($totTypeQuantite > $validated['capacite']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['types' => 'Le total des quantités de billets ne peut pas dépasser la capacité.']);
        }

        // Update event basic info
        $evenement->update([
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'lieu_id' => $validated['lieu_id'],
            'capacite' => $validated['capacite'],
            'statut' => $validated['statut'],
        ]);

        // Handle image update
        if ($request->hasFile('affiche')) {
            if ($evenement->affiche) {
                Storage::disk('public')->delete($evenement->affiche);
            }
            $evenement->update([
                'affiche' => $request->file('affiche')->store('events/affiches', 'public')
            ]);
        }

        // Update ticket types
        if (!empty($validated['types'])) {
            $toUpdate = [];
            $toCreate = [];
            
            foreach ($validated['types'] as $type) {
                if (!empty($type['id'])) {
                    $toUpdate[] = $type;
                } else {
                    $toCreate[] = $type;
                }
            }
            
            // Update existing types
            foreach ($toUpdate as $type) {
                TypeBillet::where('id', $type['id'])->update([
                    'nom' => $type['type'],
                    'prix' => $type['prix'],
                    'quantite' => $type['quantite'],
                ]);
            }
            
            // Create new types
            foreach ($toCreate as $type) {
                TypeBillet::create([
                    'evenement_id' => $evenement->id,
                    'nom' => $type['type'],
                    'prix' => $type['prix'],
                    'quantite' => $type['quantite'],
                    'quantite_vendue' => 0,
                    'actif' => true,
                ]);
            }
        }
        
        return redirect()->route('organisateur.evenements.show', $evenement)
            ->with('success', '✅ Événement mis à jour avec succès !');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evenement $evenement)
    {
        $this->checkPermissionEvenement('perm_suppression_event');
        $this->authorizeEvent($evenement);
        
        // Prevent deletion if tickets have been sold
        $billetsVendus = Billet::where('evenement_id', $evenement->id)
            ->whereNotNull('commande_id')
            ->count();
        
        if ($billetsVendus > 0) {
            return redirect()->back()->with('error', '❌ Impossible de supprimer un événement avec des billets vendus.');
        }
        
        // Delete associated ticket types
        TypeBillet::where('evenement_id', $evenement->id)->delete();
        
        // Delete image if exists
        if ($evenement->affiche) {
            Storage::disk('public')->delete($evenement->affiche);
        }
        
        // Delete the event
        $evenement->delete();
        
        return redirect()->route('organisateur.evenements.index')
            ->with('success', '✅ Événement supprimé avec succès.');
    }
    
    /**
     * Check user permission.
     */
    protected function checkPermissionEvenement($permission)
    {
        if (!Auth::check() || !Auth::user()->$permission) {
            abort(403, '❌ Vous n\'avez pas la permission d\'effectuer cette action.');
        }
    }
    
    /**
     * Authorize event ownership.
     */
    protected function authorizeEvent($evenement)
    {
        if ($evenement->user_id !== Auth::id()) {
            abort(403, '❌ Vous n\'êtes pas autorisé à accéder à cet événement.');
        }
    }
}