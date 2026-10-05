<?php
// app/Http/Controllers/Admin/OrganisateurController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Mail\OrganisateurWelcomeMail;
use App\Mail\OrganisateurStatusChangedMail;
use App\Mail\OrganisateurPermissionsUpdatedMail;
use App\Mail\OrganisateurProfileUpdatedMail;

class OrganisateurController extends Controller
{
    /**
     * Afficher la liste des organisateurs
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'Organisateur')
            ->withCount('evenements');
        
        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('nom', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('active', $request->status == 'active');
        }
        
        $organisateurs = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Statistiques
        $totalOrganisateurs = User::where('role', 'Organisateur')->count();
        $activeOrganisateurs = User::where('role', 'Organisateur')->where('active', true)->count();
        
        // Croissance mensuelle des organisateurs actifs
        $activeThisMonth = User::where('role', 'Organisateur')
            ->where('active', true)
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();
        
        $activeLastMonth = User::where('role', 'Organisateur')
            ->where('active', true)
            ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->count();
        
        $activeGrowth = $activeLastMonth > 0 ? round((($activeThisMonth - $activeLastMonth) / $activeLastMonth) * 100, 1) : 0;
        
        // Total événements organisés par les organisateurs
        $totalEvenements = \App\Models\Evenement::whereHas('organisateur', function($q) {
            $q->where('role', 'Organisateur');
        })->count();
        
        // Taux d'activité (organisateurs actifs / total organisateurs * 100)
        $activiteRate = $totalOrganisateurs > 0 ? round(($activeOrganisateurs / $totalOrganisateurs) * 100, 1) : 0;
        
        // Créer une notification de consultation pour l'admin
        $this->createNotification(
            auth()->id(),
            'Consultation de la liste des organisateurs',
            "Vous avez consulté la liste des organisateurs",
            'info',
            'admin.organisateurs.index'
        );
        
        return view('admin.organisateurs.index', compact(
            'organisateurs', 
            'totalOrganisateurs', 
            'activeOrganisateurs', 
            'activeGrowth', 
            'totalEvenements', 
            'activiteRate'
        ));
    }
    
    /**
     * Afficher les détails d'un organisateur
     */
    public function show(User $user)
    {
        if ($user->role !== 'Organisateur') {
            abort(404, 'Organisateur non trouvé');
        }

        // Notification de consultation du profil organisateur
        $this->createNotification(
            auth()->id(),
            'Consultation du profil organisateur',
            "Vous consultez le profil de {$user->prenom} {$user->nom}",
            'info',
            'admin.organisateurs.show',
            ['user_id' => $user->id]
        );

        return view('admin.organisateurs.show', compact('user'));
    }
    
    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.organisateurs.create');
    }
    
    /**
     * Enregistrer un nouvel organisateur
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'password' => 'required|string|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'perm_creation_event' => 'sometimes|boolean',
            'perm_modification_event' => 'sometimes|boolean',
            'perm_suppression_event' => 'sometimes|boolean',
            'perm_view_stats' => 'sometimes|boolean',
        ], [
            'prenom.required' => 'Le prénom est obligatoire',
            'nom.required' => 'Le nom est obligatoire',
            'email.required' => 'L\'email est obligatoire',
            'email.unique' => 'Cet email est déjà utilisé',
            'password.required' => 'Le mot de passe est obligatoire',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
            'password.confirmed' => 'Les mots de passe ne correspondent pas',
        ]);
        
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            DB::beginTransaction();
            
            $profileImagePath = null;
            if ($request->hasFile('profile_image')) {
                $profileImagePath = $request->file('profile_image')->store('uploads/profile_images', 'public');
            }

            $user = User::create([
                'prenom' => trim($request->prenom),
                'nom' => trim($request->nom),
                'email' => strtolower(trim($request->email)),
                'telephone' => $request->telephone ? trim($request->telephone) : null,
                'bio' => $request->bio ? trim($request->bio) : null,
                'profile_image' => $profileImagePath,
                'password' => Hash::make($request->password),
                'role' => 'Organisateur',
                'active' => true,
                'perm_creation_event' => $request->has('perm_creation_event'),
                'perm_modification_event' => $request->has('perm_modification_event'),
                'perm_suppression_event' => $request->has('perm_suppression_event'),
                'perm_view_stats' => $request->has('perm_view_stats'),
            ]);
            
            // 1. Notification pour l'admin
            $this->createNotification(
                auth()->id(),
                'Organisateur créé avec succès',
                "Vous avez créé l'organisateur {$user->prenom} {$user->nom} ({$user->email})",
                'success',
                'admin.organisateurs.edit',
                ['user_id' => $user->id]
            );
            
            // 2. Notification pour l'organisateur
            $this->createNotification(
                $user->id,
                'Bienvenue sur la plateforme',
                "Votre compte organisateur a été créé avec succès. Vous pouvez maintenant vous connecter.",
                'success',
                'organisateur.dashboard',
                ['email' => $user->email]
            );
            
            // 3. Envoi d'email de bienvenue
            try {
                Mail::to($user->email)->send(new OrganisateurWelcomeMail($user, $request->password));
                $emailStatus = 'Email de bienvenue envoyé';
            } catch (\Exception $e) {
                $emailStatus = 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage();
                Log::error('Erreur envoi email bienvenue', ['error' => $e->getMessage(), 'user_id' => $user->id]);
            }
            
            // 4. Log de l'action
            Log::channel('admin')->info('Nouvel organisateur créé', [
                'admin_id' => auth()->id(),
                'admin_email' => auth()->user()->email,
                'organisateur_id' => $user->id,
                'organisateur_email' => $user->email,
                'email_status' => $emailStatus,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            DB::commit();
            
            return redirect()
                ->route('admin.organisateurs.index')
                ->with('success', "Organisateur créé avec succès ! {$emailStatus}");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Notification d'erreur pour l'admin
            $this->createNotification(
                auth()->id(),
                'Erreur lors de la création',
                "Une erreur est survenue lors de la création de l'organisateur: " . $e->getMessage(),
                'error',
                null,
                ['error' => $e->getMessage()]
            );
            
            Log::channel('admin')->error('Erreur création organisateur', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
                'admin_id' => auth()->id()
            ]);
            
            return back()
                ->with('error', 'Une erreur est survenue lors de la création de l\'organisateur. Veuillez réessayer.')
                ->withInput();
        }
    }
    
    /**
     * Afficher le formulaire de modification
     */
    public function edit(User $user)
    {
        if ($user->role !== 'Organisateur') {
            abort(404, 'Organisateur non trouvé');
        }
        
        // Notification de consultation du profil
        $this->createNotification(
            auth()->id(),
            'Consultation du profil organisateur',
            "Vous consultez le profil de {$user->prenom} {$user->nom}",
            'info',
            'admin.organisateurs.edit',
            ['user_id' => $user->id]
        );
        
        return view('admin.organisateurs.edit', compact('user'));
    }
    
    /**
     * Mettre à jour un organisateur
     */
    public function update(Request $request, User $user)
    {
        if ($user->role !== 'Organisateur') {
            abort(404, 'Organisateur non trouvé');
        }
        
        $validator = Validator::make($request->all(), [
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telephone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'active' => 'sometimes|boolean',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            DB::beginTransaction();
            
            $oldData = [
                'prenom' => $user->prenom,
                'nom' => $user->nom,
                'email' => $user->email,
                'active' => $user->active
            ];
            
            $updateData = [
                'prenom' => trim($request->prenom),
                'nom' => trim($request->nom),
                'email' => strtolower(trim($request->email)),
                'telephone' => $request->telephone ? trim($request->telephone) : null,
                'bio' => $request->bio ? trim($request->bio) : null,
                'active' => $request->has('active'),
            ];
            
            // Si un nouveau mot de passe est fourni
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            // Si une nouvelle image de profil est téléchargée
            if ($request->hasFile('profile_image')) {
                // Supprimer l'ancienne image si présente
                if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                    Storage::disk('public')->delete($user->profile_image);
                }

                $updateData['profile_image'] = $request->file('profile_image')->store('uploads/profile_images', 'public');
            }
            
            $user->update($updateData);
            
            // Notification pour l'admin
            $this->createNotification(
                auth()->id(),
                'Organisateur modifié',
                "Vous avez modifié le profil de {$user->prenom} {$user->nom}",
                'info',
                'admin.organisateurs.edit',
                ['user_id' => $user->id]
            );
            
            // Notification pour l'organisateur
            $this->createNotification(
                $user->id,
                'Votre profil a été modifié',
                "Un administrateur a modifié votre profil. Connectez-vous pour voir les changements.",
                'info',
                'organisateur.profil',
                ['admin_id' => auth()->id()]
            );
            
            // Envoyer un email si des informations importantes ont changé
            if ($oldData['email'] != $updateData['email'] || $request->filled('password')) {
                try {
                    Mail::to($user->email)->send(new OrganisateurProfileUpdatedMail($user, $oldData));
                } catch (\Exception $e) {
                    Log::error('Erreur envoi email modification profil', ['error' => $e->getMessage()]);
                }
            }
            
            // Log de l'action
            Log::channel('admin')->info('Organisateur modifié', [
                'admin_id' => auth()->id(),
                'organisateur_id' => $user->id,
                'changes' => array_keys($updateData),
                'ip' => $request->ip()
            ]);
            
            DB::commit();
            
            return redirect()
                ->route('admin.organisateurs.index')
                ->with('success', 'Organisateur modifié avec succès !');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->createNotification(
                auth()->id(),
                'Erreur lors de la modification',
                "Une erreur est survenue lors de la modification de {$user->prenom} {$user->nom}",
                'error',
                null,
                ['error' => $e->getMessage()]
            );
            
            Log::channel('admin')->error('Erreur modification organisateur', [
                'error' => $e->getMessage(),
                'organisateur_id' => $user->id,
                'admin_id' => auth()->id()
            ]);
            
            return back()
                ->with('error', 'Une erreur est survenue lors de la modification.')
                ->withInput();
        }
    }
    
    /**
     * Supprimer un organisateur
     */
    public function destroy(Request $request, User $user)
    {
        if ($user->role !== 'Organisateur') {
            if ($request->ajax()) {
                return response()->json(['error' => 'Organisateur non trouvé'], 404);
            }
            abort(404);
        }
        
        // Vérifier si l'organisateur a des événements
        $evenementsCount = $user->evenements()->count();
        if ($evenementsCount > 0) {
            $message = "Impossible de supprimer cet organisateur car il a {$evenementsCount} événement(s).";
            
            $this->createNotification(
                auth()->id(),
                'Suppression impossible',
                $message,
                'warning',
                null,
                ['user_id' => $user->id, 'evenements_count' => $evenementsCount]
            );
            
            if ($request->ajax()) {
                return response()->json(['error' => $message], 400);
            }
            return back()->with('error', $message);
        }
        
        try {
            DB::beginTransaction();
            
            $userData = [
                'id' => $user->id,
                'email' => $user->email,
                'nom' => $user->nom,
                'prenom' => $user->prenom
            ];
            
            $userEmail = $user->email;
            $userName = $user->prenom . ' ' . $user->nom;
            
            $user->delete();
            
            // Notification pour l'admin
            $this->createNotification(
                auth()->id(),
                'Organisateur supprimé',
                "Vous avez supprimé l'organisateur {$userName}",
                'warning',
                'admin.organisateurs.index',
                ['deleted_user' => $userData]
            );
            
            // Log de l'action
            Log::channel('admin')->info('Organisateur supprimé', [
                'admin_id' => auth()->id(),
                'organisateur' => $userData,
                'ip' => $request->ip()
            ]);
            
            DB::commit();
            
            if ($request->ajax()) {
                return response()->json(['success' => 'Organisateur supprimé avec succès !']);
            }
            
            return redirect()
                ->route('admin.organisateurs.index')
                ->with('success', 'Organisateur supprimé avec succès !');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->createNotification(
                auth()->id(),
                'Erreur lors de la suppression',
                "Une erreur est survenue lors de la suppression de l'organisateur",
                'error',
                null,
                ['error' => $e->getMessage()]
            );
            
            Log::channel('admin')->error('Erreur suppression organisateur', [
                'error' => $e->getMessage(),
                'organisateur_id' => $user->id,
                'admin_id' => auth()->id()
            ]);
            
            if ($request->ajax()) {
                return response()->json(['error' => 'Erreur lors de la suppression'], 500);
            }
            return back()->with('error', 'Erreur lors de la suppression');
        }
    }
    
    /**
     * Activer/Désactiver un organisateur
     */
    public function toggleStatus(Request $request, User $user)
    {
        if ($user->role !== 'Organisateur') {
            if ($request->ajax()) {
                return response()->json(['error' => 'Organisateur non trouvé'], 404);
            }
            abort(404);
        }
        
        try {
            $newStatus = !$user->active;
            $user->update(['active' => $newStatus]);
            
            $statusText = $newStatus ? 'activé' : 'désactivé';
            $statusMessage = $newStatus ? 'activé' : 'désactivé';
            
            // Notification pour l'admin
            $this->createNotification(
                auth()->id(),
                "Organisateur {$statusText}",
                "Vous avez {$statusMessage} l'organisateur {$user->prenom} {$user->nom}",
                $newStatus ? 'success' : 'warning',
                'admin.organisateurs.edit',
                ['user_id' => $user->id, 'new_status' => $newStatus]
            );
            
            // Notification pour l'organisateur
            $this->createNotification(
                $user->id,
                "Votre compte a été {$statusText}",
                "Votre compte organisateur a été {$statusMessage} par un administrateur.",
                $newStatus ? 'success' : 'warning',
                'organisateur.dashboard',
                ['admin_id' => auth()->id()]
            );
            
            // Envoyer un email de notification
            try {
                Mail::to($user->email)->send(new OrganisateurStatusChangedMail($user, $newStatus));
            } catch (\Exception $e) {
                Log::error('Erreur envoi email changement statut', ['error' => $e->getMessage()]);
            }
            
            // Log de l'action
            Log::channel('admin')->info("Organisateur {$statusText}", [
                'admin_id' => auth()->id(),
                'organisateur_id' => $user->id,
                'new_status' => $newStatus,
                'ip' => $request->ip()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Organisateur {$statusText} avec succès !",
                    'active' => $newStatus
                ]);
            }
            
            return back()->with('success', "Organisateur {$statusText} avec succès !");
            
        } catch (\Exception $e) {
            Log::channel('admin')->error('Erreur changement statut', [
                'error' => $e->getMessage(),
                'organisateur_id' => $user->id
            ]);
            
            $this->createNotification(
                auth()->id(),
                'Erreur lors du changement de statut',
                "Une erreur est survenue lors du changement de statut de {$user->prenom} {$user->nom}",
                'error',
                null,
                ['error' => $e->getMessage()]
            );
            
            if ($request->ajax()) {
                return response()->json(['error' => 'Erreur lors du changement de statut'], 500);
            }
            return back()->with('error', 'Erreur lors du changement de statut');
        }
    }
    
    /**
     * Afficher la page de gestion des permissions
     */
    public function permissions(User $user)
    {
        if ($user->role !== 'Organisateur') {
            abort(404, 'Organisateur non trouvé');
        }
        
        // Notification de consultation des permissions
        $this->createNotification(
            auth()->id(),
            'Consultation des permissions',
            "Vous consultez les permissions de {$user->prenom} {$user->nom}",
            'info',
            'admin.organisateurs.permissions',
            ['user_id' => $user->id]
        );
        
        return view('admin.organisateurs.permissions', compact('user'));
    }
    
    /**
     * Mettre à jour les permissions d'un organisateur
     */
    public function updatePermissions(Request $request, User $user)
{
    if ($user->role !== 'Organisateur') {
        abort(404, 'Organisateur non trouvé');
    }
    
    $validator = Validator::make($request->all(), [
        'perm_creation_event' => 'sometimes|boolean',
        'perm_modification_event' => 'sometimes|boolean',
        'perm_suppression_event' => 'sometimes|boolean',
        'perm_view_stats' => 'sometimes|boolean',
    ]);
    
    if ($validator->fails()) {
        if ($request->ajax()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        return redirect()->back()->withErrors($validator);
    }
    
    try {
        DB::beginTransaction();
        
        $oldPermissions = [
            'perm_creation_event' => $user->perm_creation_event,
            'perm_modification_event' => $user->perm_modification_event,
            'perm_suppression_event' => $user->perm_suppression_event,
            'perm_view_stats' => $user->perm_view_stats,
        ];
        
        $newPermissions = [
            'perm_creation_event' => $request->has('perm_creation_event'),
            'perm_modification_event' => $request->has('perm_modification_event'),
            'perm_suppression_event' => $request->has('perm_suppression_event'),
            'perm_view_stats' => $request->has('perm_view_stats'),
        ];
        
        $user->update($newPermissions);
        
        // Liste des permissions modifiées
        $changedPermissions = [];
        foreach ($newPermissions as $perm => $value) {
            if ($oldPermissions[$perm] != $value) {
                $changedPermissions[] = $this->getPermissionLabel($perm) . ': ' . ($value ? 'Activée' : 'Désactivée');
            }
        }
        
        if (!empty($changedPermissions)) {
            // Notification pour l'admin
            $this->createNotification(
                auth()->id(),
                'Permissions mises à jour',
                "Vous avez mis à jour les permissions de {$user->prenom} {$user->nom}: " . implode(', ', $changedPermissions),
                'info',
                'admin.organisateurs.permissions',
                ['user_id' => $user->id, 'changes' => $changedPermissions]
            );
            
            // Notification pour l'organisateur
            $this->createNotification(
                $user->id,
                'Vos permissions ont été modifiées',
                "Un administrateur a modifié vos permissions. Voici les changements: " . implode(', ', $changedPermissions),
                'info',
                'organisateur.dashboard',
                ['admin_id' => auth()->id(), 'changes' => $changedPermissions]
            );
            
            // Envoyer un email de notification (avec vérification de l'existence de la classe)
            try {
                if (class_exists(\App\Mail\OrganisateurPermissionsUpdatedMail::class)) {
                    Mail::to($user->email)->send(new \App\Mail\OrganisateurPermissionsUpdatedMail($user, $changedPermissions));
                } else {
                    Log::warning('Classe OrganisateurPermissionsUpdatedMail non trouvée, email non envoyé');
                }
            } catch (\Exception $e) {
                Log::error('Erreur envoi email modification permissions', ['error' => $e->getMessage()]);
            }
        }
        
        // Log des changements de permissions
        $changes = [];
        foreach ($newPermissions as $perm => $value) {
            if ($oldPermissions[$perm] != $value) {
                $changes[$perm] = [
                    'old' => $oldPermissions[$perm],
                    'new' => $value
                ];
            }
        }
        
        if (!empty($changes)) {
            Log::channel('admin')->info('Permissions organisateur mises à jour', [
                'admin_id' => auth()->id(),
                'organisateur_id' => $user->id,
                'organisateur_email' => $user->email,
                'changes' => $changes,
                'ip' => $request->ip()
            ]);
        }
        
        DB::commit();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Permissions mises à jour avec succès !'
            ]);
        }
        
        return redirect()
            ->route('admin.organisateurs.index')
            ->with('success', 'Permissions mises à jour avec succès !');
            
    } catch (\Exception $e) {
        DB::rollBack();
        
        $this->createNotification(
            auth()->id(),
            'Erreur lors de la mise à jour des permissions',
            "Une erreur est survenue lors de la mise à jour des permissions de {$user->prenom} {$user->nom}",
            'error',
            null,
            ['error' => $e->getMessage()]
        );
        
        Log::channel('admin')->error('Erreur mise à jour permissions', [
            'error' => $e->getMessage(),
            'organisateur_id' => $user->id,
            'admin_id' => auth()->id()
        ]);
        
        if ($request->ajax()) {
            return response()->json(['error' => 'Erreur lors de la mise à jour des permissions'], 500);
        }
        
        return back()->with('error', 'Erreur lors de la mise à jour des permissions');
    }
}

/**
 * Exporter la liste des organisateurs
 */
public function export(Request $request)
{
    $organisateurs = User::where('role', 'Organisateur')
        ->withCount('evenements')
        ->get();
        
        // Notification d'export
        $this->createNotification(
            auth()->id(),
            'Export des organisateurs',
            "Vous avez exporté la liste des organisateurs (" . $organisateurs->count() . " enregistrements)",
            'info',
            'admin.organisateurs.index',
            ['count' => $organisateurs->count()]
        );
        
        $csv = "ID,Prénom,Nom,Email,Téléphone,Statut,Événements,Date création,Permissions\n";
        
        foreach ($organisateurs as $org) {
            $permissions = [];
            if ($org->perm_creation_event) $permissions[] = 'Création';
            if ($org->perm_modification_event) $permissions[] = 'Modification';
            if ($org->perm_suppression_event) $permissions[] = 'Suppression';
            if ($org->perm_view_stats) $permissions[] = 'Stats';
            
            // Échapper les virgules et guillemets dans les données
            $prenom = str_replace('"', '""', $org->prenom ?? '');
            $nom = str_replace('"', '""', $org->nom ?? '');
            $email = str_replace('"', '""', $org->email ?? '');
            $telephone = str_replace('"', '""', $org->telephone ?? '');
            
            $csv .= "\"{$org->id}\",\"{$prenom}\",\"{$nom}\",\"{$email}\",\"{$telephone}\",";
            $csv .= ($org->active ? 'Actif' : 'Inactif') . ",";
            $csv .= "\"{$org->evenements_count}\",\"{$org->created_at->format('d/m/Y H:i')}\",";
            $csv .= '"' . implode(' | ', $permissions) . '"' . "\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="organisateurs_' . date('Y-m-d_H-i-s') . '.csv"');
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
    
    /**
     * Obtenir le libellé d'une permission
     */
    private function getPermissionLabel($permission)
    {
        $labels = [
            'perm_creation_event' => 'Création d\'événements',
            'perm_modification_event' => 'Modification d\'événements',
            'perm_suppression_event' => 'Suppression d\'événements',
            'perm_view_stats' => 'Visualisation des statistiques',
        ];
        
        return $labels[$permission] ?? $permission;
    }
    
    /**
     * Envoyer une notification de rappel à tous les organisateurs
     */
    public function sendReminder(Request $request)
    {
        $organisateurs = User::where('role', 'Organisateur')
            ->where('active', true)
            ->get();
        
        $count = 0;
        foreach ($organisateurs as $org) {
            $this->createNotification(
                $org->id,
                'Rappel: Mettez à jour vos événements',
                "N'oubliez pas de mettre à jour vos événements et de vérifier vos ventes.",
                'info',
                'organisateur.dashboard'
            );
            $count++;
        }
        
        $this->createNotification(
            auth()->id(),
            'Rappel envoyé',
            "Un rappel a été envoyé à {$count} organisateur(s)",
            'success',
            'admin.organisateurs.index'
        );
        
        return back()->with('success', "Rappel envoyé à {$count} organisateur(s)");
    }

/**
 * Afficher le modal de gestion des permissions (AJAX)
 */
public function permissionsModal(User $user)
{
    if ($user->role !== 'Organisateur') {
        return response()->json(['error' => 'Organisateur non trouvé'], 404);
    }
    
    return view('admin.organisateurs.partials.permissions-modal', compact('user'));
}
}
