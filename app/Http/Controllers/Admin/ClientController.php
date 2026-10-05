<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ClientController extends Controller
{
    /**
     * Afficher la liste des clients
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'Client');
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('active', false);
            }
        }
        
        $clients = $query->withCount('commandes')->latest('date_creation')->paginate(10);
        
        // Stats
        $clientsActifs = User::where('role', 'Client')->where('active', true)->count();
        $clientsInactifs = User::where('role', 'Client')->where('active', false)->count();
        $nouveauxClientsMois = User::where('role', 'Client')
            ->where('date_creation', '>=', now()->startOfMonth())
            ->count();
        
        return view('admin.clients.index', compact(
            'clients', 
            'clientsActifs', 
            'clientsInactifs', 
            'nouveauxClientsMois'
        ));
    }
    
    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.clients.create');
    }
    
    /**
     * Enregistrer un nouveau client
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        
        try {
            DB::beginTransaction();
            
            $profileImagePath = null;
            if ($request->hasFile('profile_image')) {
                $profileImagePath = $request->file('profile_image')->store('uploads/profile_images', 'public');
            }

            $user = User::create([
                'prenom' => $validated['prenom'],
                'nom' => $validated['nom'],
                'email' => $validated['email'],
                'telephone' => $validated['telephone'] ?? null,
                'profile_image' => $profileImagePath,
                'password' => Hash::make($validated['password']),
                'role' => 'Client',
                'active' => true,
                'date_creation' => now(),
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.clients.index')
                ->with('success', 'Client créé avec succès !');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur création client', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);
            
            return back()
                ->with('error', 'Erreur lors de la création: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Afficher les détails d'un client
     */
    public function show(User $user)
    {
        if ($user->role !== 'Client') {
            abort(404);
        }
        
        // Load relations
        $user->load(['commandes' => function($q) {
            $q->latest('date')->take(5);
        }]);
        
        // Count tickets
        $user->billets_count = $user->billets()->count();
        $user->commandes_count = $user->commandes()->count();
        $user->total_depense = $user->commandes()
            ->where('statut', 'Confirmée')
            ->sum('montant_total');
        
        return view('admin.clients.show', compact('user'));
    }
    
    /**
     * Afficher le formulaire de modification
     */
    public function edit(User $user)
    {
        if ($user->role !== 'Client') {
            abort(404);
        }
        
        // Load stats
        $user->billets_count = $user->billets()->count();
        $user->commandes_count = $user->commandes()->count();
        $user->total_depense = $user->commandes()
            ->where('statut', 'Confirmée')
            ->sum('montant_total');
        
        return view('admin.clients.edit', compact('user'));
    }
    
    /**
     * Mettre à jour un client
     */
    public function update(Request $request, User $user)
    {
        if ($user->role !== 'Client') {
            abort(404);
        }
        
        $validated = $request->validate([
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telephone' => 'nullable|string|max:20',
            'active' => 'nullable|boolean',
            'password' => 'nullable|string|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        
        try {
            DB::beginTransaction();
            
            $user->prenom = $validated['prenom'];
            $user->nom = $validated['nom'];
            $user->email = $validated['email'];
            $user->telephone = $validated['telephone'] ?? null;
            $user->active = $request->has('active') ? $validated['active'] : $user->active;
            
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            if ($request->hasFile('profile_image')) {
                if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                    Storage::disk('public')->delete($user->profile_image);
                }
                $user->profile_image = $request->file('profile_image')->store('uploads/profile_images', 'public');
            }
            
            $user->save();
            
            DB::commit();
            
            return redirect()->route('admin.clients.index')
                ->with('success', 'Client modifié avec succès');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Erreur lors de la modification: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Suspendre un client
     */
    public function suspend(User $user)
    {
        if ($user->role !== 'Client') {
            abort(404);
        }

        $user->active = false;
        $user->save();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Client suspendu avec succès']);
        }

        return redirect()->back()
            ->with('success', 'Client suspendu avec succès');
    }
    
    /**
     * Activer un client
     */
    public function activate(User $user)
    {
        if ($user->role !== 'Client') {
            abort(404);
        }

        $user->active = true;
        $user->save();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Client activé avec succès']);
        }

        return redirect()->back()
            ->with('success', 'Client activé avec succès');
    }
    
    /**
     * Supprimer un client (soft delete)
     */
    public function destroy(User $user)
    {
        if ($user->role !== 'Client') {
            abort(404);
        }
        
        // Vérifier si le client a des commandes
        if ($user->commandes()->count() > 0) {
            return redirect()->route('admin.clients.index')
                ->with('error', 'Impossible de supprimer ce client car il a des commandes.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.clients.index')
            ->with('success', 'Client supprimé avec succès');
    }
    
    /**
     * Afficher les commandes d'un client
     */
    public function commandes(User $user)
    {
        if ($user->role !== 'Client') {
            abort(404);
        }
        
        $commandes = $user->commandes()
            ->with(['billets.evenement', 'paiement'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.clients.commandes', compact('user', 'commandes'));
    }

    /**
     * Télécharger tous les billets d'un client en PDF
     */
    public function downloadBillets(User $user)
    {
        if ($user->role !== 'Client') {
            abort(404);
        }

        $billets = $user->billets()
            ->with(['evenement.lieu', 'typeBillet', 'commande'])
            ->where('billets.statut', 'Valide')
            ->orderBy('billets.created_at')
            ->get();

        if ($billets->isEmpty()) {
            return back()->with('error', 'Aucun billet valide trouvé pour ce client.');
        }

        $client = $user;
        $pdf = Pdf::loadView('pdf.tickets_client', compact('billets', 'client'))
                  ->setOption('isRemoteEnabled', true);

        return $pdf->download('billets_client_' . $user->id . '.pdf');
    }

    /**
     * Exporter les clients en CSV
     */
    public function export(Request $request)
    {
        $query = User::where('role', 'Client');
        
        // Appliquer les mêmes filtres que dans l'index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('active', false);
            }
        }
        
        $clients = $query->withCount('commandes')->get();
        
        $csv = "ID,Prénom,Nom,Email,Téléphone,Statut,Date d'inscription,Nombre de commandes\n";
        
        foreach ($clients as $client) {
            $csv .= "{$client->id},{$client->prenom},{$client->nom},";
            $csv .= "{$client->email},{$client->telephone},";
            $csv .= ($client->active ? 'Actif' : 'Inactif') . ",";
            $csv .= "{$client->date_creation->format('d/m/Y H:i')},";
            $csv .= "{$client->commandes_count}\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="clients_' . date('Y-m-d') . '.csv"');
    }
}