<?php
// app/Http/Controllers/Admin/ParametreController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ParametreController extends Controller
{
    /**
     * Afficher la page des paramètres
     */
    public function index()
    {
        // Récupérer les paramètres depuis la base de données
        $settings = Setting::pluck('value', 'key')->toArray();
        
        // Récupérer les informations système
        $phpVersion = phpversion();
        $laravelVersion = app()->version();
        $dbConnection = config('database.default');
        $dbName = config('database.connections.' . $dbConnection . '.database');
        $appEnv = config('app.env');
        $appDebug = config('app.debug');
        $appUrl = config('app.url');
        $timezone = config('app.timezone');
        $diskFree = disk_free_space('/');
        $diskTotal = disk_total_space('/');
        
        // Statistiques globales
        $stats = [
            'total_events' => \App\Models\Evenement::count(),
            'total_tickets' => \App\Models\Billet::count(),
            'total_users' => \App\Models\User::count(),
            'total_organizers' => \App\Models\User::where('role', 'Organisateur')->count(),
            'total_revenue' => \App\Models\Billet::sum('prix'),
            'resolved_tickets' => 0,
            'urgent_tickets' => 0,
            'expired_tickets' => 0,
        ];
        
        return view('admin.parametres.index', compact(
            'settings', 'phpVersion', 'laravelVersion', 'dbConnection', 'dbName',
            'appEnv', 'appDebug', 'appUrl', 'timezone', 'diskFree', 'diskTotal', 'stats'
        ));
    }
    
    /**
     * Mettre à jour les paramètres généraux
     */
    public function update(Request $request)
    {
        // Changement de mot de passe
        if ($request->filled('change_password')) {
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            $admin = auth()->user();

            if (!Hash::check($request->current_password, $admin->password)) {
                return back()->with('error', 'Le mot de passe actuel est incorrect.');
            }

            $admin->password = Hash::make($request->new_password);
            $admin->save();

            $this->createNotification(
                $admin->id,
                'Mot de passe modifié',
                'Votre mot de passe a été mis à jour avec succès.',
                'success',
                'admin.parametres.index'
            );

            return redirect()->route('admin.parametres.index', ['tab' => 'profile'])->with('success', 'Mot de passe mis à jour avec succès');
        }

        // Mise à jour du profil utilisateur
        if ($request->hasAny(['name', 'email', 'profile_image'])) {
            $request->validate([
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|email|unique:users,email,' . auth()->id(),
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            $admin = auth()->user();

            if ($request->filled('name')) {
                $admin->name = $request->name;
            }

            if ($request->filled('email')) {
                $admin->email = $request->email;
            }

            if ($request->hasFile('profile_image')) {
                if ($admin->profile_image && Storage::disk('public')->exists($admin->profile_image)) {
                    Storage::disk('public')->delete($admin->profile_image);
                }

                $admin->profile_image = $request->file('profile_image')->store('uploads/profile_images', 'public');
                Setting::updateOrCreate(['key' => 'profile_image'], ['value' => $admin->profile_image]);
            }

            $admin->save();

            $this->createNotification(
                $admin->id,
                'Profil mis à jour',
                'Vos informations de profil ont été mises à jour avec succès.',
                'success',
                'admin.parametres.index'
            );

            return redirect()->route('admin.parametres.index', ['tab' => 'profile'])->with('success', 'Profil mis à jour avec succès !');
        }

        // Mise à jour des paramètres généraux
        $request->validate([
            'site_name' => 'sometimes|required|string|max:255',
            'site_email' => 'sometimes|required|email',
            'site_phone' => 'nullable|string|max:20',
            'site_address' => 'nullable|string|max:500',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'favicon' => 'nullable|mimes:ico,png,svg|max:512',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'maintenance_mode' => 'boolean',
            'ticket_expiry_days' => 'integer|min:1|max:365',
            'max_tickets_per_order' => 'integer|min:1|max:100',
            'enable_notifications' => 'boolean',
            'enable_email_verification' => 'boolean',
            'default_currency' => 'string|in:MAD,EUR,USD',
        ]);

        $currentSettings = Setting::pluck('value', 'key')->toArray();
        
        try {
            DB::beginTransaction();
            
            // Gestion du logo
            if ($request->hasFile('site_logo')) {
                $logoPath = $request->file('site_logo')->store('settings', 'public');
                Setting::updateOrCreate(['key' => 'site_logo'], ['value' => $logoPath]);
            }
            
            // Gestion du favicon
            if ($request->hasFile('favicon')) {
                $faviconPath = $request->file('favicon')->store('settings', 'public');
                Setting::updateOrCreate(['key' => 'favicon'], ['value' => $faviconPath]);
            }

            // Gestion de la photo de profil de l'admin connecté
            if ($request->hasFile('profile_image') && auth()->user()) {
                $admin = auth()->user();
                if ($admin->profile_image && Storage::disk('public')->exists($admin->profile_image)) {
                    Storage::disk('public')->delete($admin->profile_image);
                }
                $admin->profile_image = $request->file('profile_image')->store('uploads/profile_images', 'public');
                $admin->save();
                $admin->refresh(); // forcer le rafraîchissement de l'utilisateur courant

                // Enregistrer également dans les paramètres si souhaité pour la configuration globale
                Setting::updateOrCreate(['key' => 'profile_image'], ['value' => $admin->profile_image]);
            }

            // Changement de mot de passe
            if ($request->filled('change_password')) {
                $request->validate([
                    'current_password' => 'required|string',
                    'new_password' => 'required|string|min:8|confirmed',
                ]);

                $admin = auth()->user();

                if (!Hash::check($request->current_password, $admin->password)) {
                    return back()->with('error', 'Le mot de passe actuel est incorrect.');
                }

                $admin->password = Hash::make($request->new_password);
                $admin->save();

                $this->createNotification(
                    $admin->id,
                    'Mot de passe modifié',
                    'Votre mot de passe a été mis à jour avec succès.',
                    'success',
                    'admin.parametres.index'
                );

                return redirect()->route('admin.parametres.index')->with('success', 'Mot de passe mis à jour avec succès');
            }
            
            // Mettre à jour les paramètres
            $settings = [
                'site_name' => $request->filled('site_name') ? $request->site_name : ($currentSettings['site_name'] ?? config('app.name')),
                'site_email' => $request->filled('site_email') ? $request->site_email : ($currentSettings['site_email'] ?? config('mail.from.address')),
                'site_phone' => $request->filled('site_phone') ? $request->site_phone : ($currentSettings['site_phone'] ?? null),
                'site_address' => $request->filled('site_address') ? $request->site_address : ($currentSettings['site_address'] ?? null),
                'maintenance_mode' => $request->has('maintenance_mode') ? 1 : ($currentSettings['maintenance_mode'] ?? 0),
                'ticket_expiry_days' => $request->filled('ticket_expiry_days') ? $request->ticket_expiry_days : ($currentSettings['ticket_expiry_days'] ?? 30),
                'max_tickets_per_order' => $request->filled('max_tickets_per_order') ? $request->max_tickets_per_order : ($currentSettings['max_tickets_per_order'] ?? 10),
                'enable_notifications' => $request->has('enable_notifications') ? 1 : ($currentSettings['enable_notifications'] ?? 0),
                'enable_email_verification' => $request->has('enable_email_verification') ? 1 : ($currentSettings['enable_email_verification'] ?? 0),
                'default_currency' => $request->filled('default_currency') ? $request->default_currency : ($currentSettings['default_currency'] ?? 'MAD'),
            ];
            
            foreach ($settings as $key => $value) {
                Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
            
            // Mettre à jour le fichier .env
            $this->updateEnv([
                'APP_NAME' => $request->site_name,
                'APP_URL' => $request->site_url ?? config('app.url'),
                'MAIL_FROM_ADDRESS' => $request->site_email,
            ]);
            
            // Activer/désactiver le mode maintenance
            if ($request->maintenance_mode) {
                Artisan::call('down', ['--retry' => 60, '--secret' => 'maintenance-secret']);
            } else {
                Artisan::call('up');
            }
            
            // Nettoyer le cache
            Cache::flush();
            
            $this->createNotification(
                auth()->id(),
                'Paramètres mis à jour',
                'Les paramètres de l\'application ont été mis à jour avec succès',
                'success',
                'admin.parametres.index'
            );
            
            DB::commit();
            
            return redirect()->route('admin.parametres.index')
                ->with('success', 'Paramètres mis à jour avec succès !');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur mise à jour paramètres', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }
    
    /**
     * Gérer les utilisateurs
     */
    public function users(Request $request)
    {
        $users = \App\Models\User::withCount('evenements', 'commandes')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.parametres.users', compact('users'));
    }
    
    /**
     * Nettoyer la base de données
     */
    public function cleanDatabase()
    {
        try {
            DB::beginTransaction();
            
            // Supprimer les logs de plus de 30 jours
            $deletedLogs = \App\Models\ActivityLog::where('created_at', '<', now()->subDays(30))->delete();
            
            // Supprimer les notifications de plus de 30 jours
            $deletedNotifications = \App\Models\Notification::where('created_at', '<', now()->subDays(30))->delete();
            
            // Nettoyer les sessions expirées
            $deletedSessions = DB::table('sessions')->where('last_activity', '<', time() - 3600 * 24)->delete();
            
            // Supprimer les événements non publiés de plus de 6 mois
            $deletedEvents = \App\Models\Evenement::where('statut', 'Brouillon')
                ->where('created_at', '<', now()->subMonths(6))
                ->delete();
            
            DB::commit();
            
            $this->createNotification(
                auth()->id(),
                'Base de données nettoyée',
                "Nettoyage effectué: {$deletedLogs} logs, {$deletedNotifications} notifications, {$deletedSessions} sessions, {$deletedEvents} événements supprimés",
                'success',
                'admin.parametres.index'
            );
            
            return back()->with('success', "Base de données nettoyée avec succès ! ($deletedLogs logs, $deletedNotifications notifications, $deletedSessions sessions, $deletedEvents événements)");
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors du nettoyage: ' . $e->getMessage());
        }
    }
    
    /**
     * Exporter la base de données
     */
    public function exportDatabase()
    {
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPassword = config('database.connections.mysql.password');
        $dbHost = config('database.connections.mysql.host');
        
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('app/backups/' . $filename);
        
        // Créer le dossier s'il n'existe pas
        if (!File::exists(storage_path('app/backups'))) {
            File::makeDirectory(storage_path('app/backups'), 0755, true);
        }
        
        // Commande mysqldump
        $command = sprintf(
            'mysqldump --host=%s --user=%s --password=%s %s > %s',
            escapeshellarg($dbHost),
            escapeshellarg($dbUser),
            escapeshellarg($dbPassword),
            escapeshellarg($dbName),
            escapeshellarg($path)
        );
        
        system($command, $output);
        
        if (file_exists($path)) {
            $this->createNotification(
                auth()->id(),
                'Base de données exportée',
                "La base de données a été exportée avec succès vers {$filename}",
                'success',
                'admin.parametres.index'
            );
            
            return response()->download($path)->deleteFileAfterSend(true);
        }
        
        return back()->with('error', 'Erreur lors de l\'export de la base de données');
    }
    
    /**
     * Vider le cache
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            Artisan::call('optimize:clear');
            
            $this->createNotification(
                auth()->id(),
                'Cache vidé',
                'Le cache de l\'application a été vidé avec succès',
                'success',
                'admin.parametres.index'
            );
            
            return back()->with('success', 'Cache vidé avec succès !');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du vidage du cache: ' . $e->getMessage());
        }
    }
    
    /**
     * Supprimer la photo de profil admin
     */
    public function removeProfileImage(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
        }

        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $user->profile_image = null;
        $user->save();

        // optionnel : vider le setting global
        Setting::where('key', 'profile_image')->delete();

        return response()->json(['success' => true, 'message' => 'Photo de profil supprimée avec succès']);
    }

    /**
     * Optimiser l'application
     */
    public function optimize()
    {
        try {
            Artisan::call('optimize');
            
            $this->createNotification(
                auth()->id(),
                'Application optimisée',
                'L\'application a été optimisée avec succès',
                'success',
                'admin.parametres.index'
            );
            
            return back()->with('success', 'Application optimisée avec succès !');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'optimisation: ' . $e->getMessage());
        }
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
     * Mettre à jour le fichier .env
     */
    private function updateEnv($data)
    {
        $path = base_path('.env');
        
        if (File::exists($path)) {
            $content = File::get($path);
            
            foreach ($data as $key => $value) {
                if (str_contains($content, $key . '=')) {
                    $pattern = '/^' . preg_quote($key, '/') . '=.*/m';
                    $replacement = $key . '=' . $value;
                    $content = preg_replace($pattern, $replacement, $content);
                } else {
                    $content .= "\n" . $key . '=' . $value;
                }
            }
            
            File::put($path, $content);
        }
    }
}