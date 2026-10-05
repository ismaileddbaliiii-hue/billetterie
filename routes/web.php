<?php
// routes/web.php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrganisateurController;
use App\Http\Controllers\Admin\EvenementController as AdminEvenementController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\PaiementController as AdminPaiementController;
use App\Http\Controllers\Admin\BilletController as AdminBilletController;
use App\Http\Controllers\Admin\LogController as AdminLogController;
use App\Http\Controllers\Admin\ParametreController as AdminParametreController;


use App\Http\Controllers\Organisateur\DashboardController as OrganisateurDashboardController;
use App\Http\Controllers\Organisateur\EvenementController as OrganisateurEvenementController;
use App\Http\Controllers\Organisateur\VenteController as OrganisateurVenteController;
use App\Http\Controllers\Organisateur\BilletController as OrganisateurBilletController;
use App\Http\Controllers\Organisateur\ProfilController as OrganisateurProfilController;

use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Client\EvenementController as ClientEvenementController;
use App\Http\Controllers\Client\CommandeController as ClientCommandeController;
use App\Http\Controllers\Client\BilletController as ClientBilletController;
use App\Http\Controllers\Client\ProfileController as ClientProfileController;
use App\Http\Controllers\Client\PanierController;
use App\Http\Controllers\PaiementController;

// use App\Http\Controllers\EvenementController; // not used because client and admin have their own controllers
use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== ROUTES PUBLIQUES ====================
Route::get('/', function () {
    // Get raw values
    $rawSiteName = \App\Models\Setting::get('site_name', config('app.name', 'TicketFlow'));
    $rawSiteDesc = \App\Models\Setting::get('site_description', 'Votre billetterie');
    
    // Aggressive sanitization: strip tags, remove all non-alphanumeric except spaces/hyphens/dots/accents
    $siteName = preg_replace('/[^a-zA-Z0-9\s\-\.éèêëàâäùûüôöîïœçñ]/u', '', strip_tags($rawSiteName));
    $siteName = trim($siteName) ?: config('app.name', 'TicketFlow');
    if (mb_strlen($siteName) > 50) {
        $siteName = mb_substr($siteName, 0, 50) . '...';
    }

    // Sanitize site description
    $siteDescription = preg_replace('/[^a-zA-Z0-9\s\-\.éèêëàâäùûüôöîïœçñ]/u', '', strip_tags($rawSiteDesc));
    $siteDescription = trim($siteDescription) ?: 'Votre billetterie';
    if (mb_strlen($siteDescription) > 160) {
        $siteDescription = mb_substr($siteDescription, 0, 160) . '...';
    }

    $siteLogoUrl = \App\Models\Setting::getUrl('site_logo');

    // Load all published upcoming events with related data
    $evenements = \App\Models\Evenement::with(['organisateur', 'lieu', 'typesBillets', 'billets'])
        ->where('statut', 'Publié')
        ->whereDate('date', '>=', today())
        ->orderBy('date', 'asc')
        ->get();

    // Calculate prices and availability
    foreach ($evenements as $evenement) {
        $evenement->prix_min = $evenement->typesBillets->min('prix') ?? 0;
        $billets_vendus = $evenement->billets->where('statut', 'Valide')->count();
        $evenement->places_restantes = max(0, $evenement->capacite - $billets_vendus);
    }

    return view('welcome', [
        'siteName' => $siteName,
        'siteLogoUrl' => $siteLogoUrl,
        'siteDescription' => $siteDescription,
        'evenements' => $evenements,
    ]);
})->name('home');

Route::get('/evenements', [ClientEvenementController::class, 'index'])->name('evenements.index');
Route::get('/evenements/{evenement}', [ClientEvenementController::class, 'show'])->name('evenements.show');

// ==================== ROUTES D'AUTHENTIFICATION ====================
require __DIR__.'/auth.php';

// ==================== ROUTES GÉNÉRIQUES (REDIRECTS PAR RÔLE) ====================
Route::middleware('auth')->get('/dashboard', function () {
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('login');
    }

    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->isOrganisateur()) {
        return redirect()->route('organisateur.dashboard');
    }

    if ($user->isClient()) {
        return redirect()->route('client.dashboard');
    }

    abort(403);
})->name('dashboard');

// ==================== ROUTES PROTÉGÉES ====================
// ==================== ROUTES ADMIN (UNIFIÉES) ====================
Route::middleware(['auth', 'role:Admin', 'log.activity'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/ventes-data', [AdminDashboardController::class, 'ventesData'])->name('dashboard.ventes-data');
        
        // ========== GESTION DES ORGANISATEURS ==========
        Route::get('/organisateurs', [OrganisateurController::class, 'index'])->name('organisateurs.index');
        Route::get('/organisateurs/create', [OrganisateurController::class, 'create'])->name('organisateurs.create');
        Route::post('/organisateurs', [OrganisateurController::class, 'store'])->name('organisateurs.store');
        Route::get('/organisateurs/export', [OrganisateurController::class, 'export'])->name('organisateurs.export');
        Route::get('/organisateurs/{user}', [OrganisateurController::class, 'show'])->name('organisateurs.show');
        Route::get('/organisateurs/{user}/edit', [OrganisateurController::class, 'edit'])->name('organisateurs.edit');
        Route::put('/organisateurs/{user}', [OrganisateurController::class, 'update'])->name('organisateurs.update');
        Route::delete('/organisateurs/{user}', [OrganisateurController::class, 'destroy'])->name('organisateurs.destroy');
        
        Route::post('/organisateurs/{user}/toggle-status', [OrganisateurController::class, 'toggleStatus'])->name('organisateurs.toggle-status');
        Route::get('/organisateurs/{user}/permissions', [OrganisateurController::class, 'permissions'])->name('organisateurs.permissions');
        Route::put('/organisateurs/{user}/permissions', [OrganisateurController::class, 'updatePermissions'])->name('organisateurs.permissions.update');
        Route::get('/organisateurs/{user}/permissions-modal', [OrganisateurController::class, 'permissionsModal'])->name('organisateurs.permissions-modal');
        Route::post('/organisateurs/send-reminder', [OrganisateurController::class, 'sendReminder'])->name('organisateurs.send-reminder');
        
        // ========== GESTION DES ÉVÉNEMENTS ==========
        Route::get('/evenements', [AdminEvenementController::class, 'index'])->name('evenements.index');
        Route::get('/evenements/create', [AdminEvenementController::class, 'create'])->name('evenements.create');
        Route::post('/evenements', [AdminEvenementController::class, 'store'])->name('evenements.store');
        Route::get('/evenements/export', [AdminEvenementController::class, 'export'])->name('evenements.export');
        Route::get('/evenements/{evenement}', [AdminEvenementController::class, 'show'])->name('evenements.show');
        Route::get('/evenements/{evenement}/edit', [AdminEvenementController::class, 'edit'])->name('evenements.edit');
        Route::put('/evenements/{evenement}', [AdminEvenementController::class, 'update'])->name('evenements.update');
        Route::patch('/evenements/{evenement}/status', [AdminEvenementController::class, 'updateStatus'])->name('evenements.status');
        Route::delete('/evenements/{evenement}', [AdminEvenementController::class, 'destroy'])->name('evenements.destroy');
        Route::post('/evenements/{evenement}/duplicate', [AdminEvenementController::class, 'duplicate'])->name('evenements.duplicate');
        Route::get('/evenements/{evenement}/get-title', [AdminEvenementController::class, 'getTitle'])->name('evenements.get-title');
        Route::get('/evenements/{evenement}/billets', [AdminEvenementController::class, 'billets'])->name('evenements.billets');
        Route::get('/evenements/{evenement}/billets/export', [AdminEvenementController::class, 'exportBillets'])->name('evenements.billets.export');
        
        // Routes pour les tickets
        Route::get('/evenements/{evenement}/acheter', [AdminEvenementController::class, 'acheterTickets'])->name('evenements.acheter');
        Route::post('/evenements/{evenement}/acheter/confirm', [AdminEvenementController::class, 'confirmAchat'])->name('evenements.acheter.confirm');
        Route::get('/evenements/{evenement}/acheter/confirm', [AdminEvenementController::class, 'confirmAchat'])->name('evenements.confirm-achat');
        Route::post('/evenements/{evenement}/acheter', [AdminEvenementController::class, 'processAchat'])->name('evenements.acheter.process');
        Route::get('/evenements/{evenement}/paiement', [AdminEvenementController::class, 'paiement'])->name('evenements.paiement');
        Route::post('/evenements/{evenement}/paiement', [AdminEvenementController::class, 'processPaiement'])->name('evenements.paiement.process');
        Route::get('/evenements/{evenement}/confirmation', [AdminEvenementController::class, 'confirmation'])->name('evenements.confirmation');
        Route::get('/evenements/{evenement}/tickets/{billet}/download', [AdminEvenementController::class, 'downloadTicket'])->name('evenements.tickets.download');
        Route::get('/evenements/{evenement}/download-all-tickets', [AdminEvenementController::class, 'downloadAllTickets'])->name('evenements.download-all-tickets');
        Route::get('/evenements/{evenement}/tickets/{billet}/download-single', [AdminEvenementController::class, 'downloadSingleTicket'])->name('evenements.tickets.download-single');
        
        // ========== GESTION DES COMMANDES ==========
        Route::get('/commandes', [App\Http\Controllers\Admin\CommandeController::class, 'index'])->name('commandes.index');
        Route::get('/commandes/export', [App\Http\Controllers\Admin\CommandeController::class, 'export'])->name('commandes.export');
        Route::get('/commandes/{commande}', [App\Http\Controllers\Admin\CommandeController::class, 'show'])->name('commandes.show');
        Route::get('/commandes/{commande}/download-billets', [App\Http\Controllers\Admin\CommandeController::class, 'downloadBillets'])->name('commandes.download-billets');
        Route::patch('/commandes/{commande}/status', [App\Http\Controllers\Admin\CommandeController::class, 'updateStatus'])->name('commandes.status');
        
        // ========== GESTION DES CLIENTS ==========
        Route::get('/clients', [AdminClientController::class, 'index'])->name('clients.index');
        Route::get('/clients/create', [AdminClientController::class, 'create'])->name('clients.create');
        Route::post('/clients', [AdminClientController::class, 'store'])->name('clients.store');
        Route::get('/clients/export', [AdminClientController::class, 'export'])->name('clients.export');
        Route::get('/clients/{user}', [AdminClientController::class, 'show'])->name('clients.show');
        Route::get('/clients/{user}/edit', [AdminClientController::class, 'edit'])->name('clients.edit');
        Route::put('/clients/{user}', [AdminClientController::class, 'update'])->name('clients.update');
        Route::patch('/clients/{user}/toggle-status', [AdminClientController::class, 'toggleStatus'])->name('clients.toggle');
        Route::delete('/clients/{user}', [AdminClientController::class, 'destroy'])->name('clients.destroy');
        Route::post('/clients/{user}/suspend', [AdminClientController::class, 'suspend'])->name('clients.suspend');
        Route::post('/clients/{user}/activate', [AdminClientController::class, 'activate'])->name('clients.activate');
        Route::get('/clients/{user}/commandes', [AdminClientController::class, 'commandes'])->name('clients.commandes');
        Route::get('/clients/{user}/download-billets', [AdminClientController::class, 'downloadBillets'])->name('clients.download-billets');
        // ========== GESTION DES PAIEMENTS ==========
        Route::get('/paiements', [AdminPaiementController::class, 'index'])->name('paiements.index');
        Route::get('/paiements/export', [AdminPaiementController::class, 'export'])->name('paiements.export');
        Route::get('/paiements/{paiement}', [AdminPaiementController::class, 'show'])->name('paiements.show');
        Route::post('/paiements/{paiement}/refund', [AdminPaiementController::class, 'refund'])->name('paiements.refund');
        
        // ========== GESTION DES BILLETS ==========
        Route::get('/billets', [AdminBilletController::class, 'index'])->name('billets.index');
        Route::get('/billets/export', [AdminBilletController::class, 'export'])->name('billets.export');
        Route::get('/billets/{billet}', [AdminBilletController::class, 'show'])->name('billets.show');
        Route::get('/billets/{billet}/download', [AdminBilletController::class, 'download'])->name('billets.download');
        Route::patch('/billets/{billet}/valider', [AdminBilletController::class, 'valider'])->name('billets.valider');
        Route::delete('/billets/{billet}', [AdminBilletController::class, 'destroy'])->name('billets.destroy');
        Route::post('/billets/bulk-validate', [AdminBilletController::class, 'bulkValidate'])->name('billets.bulk-validate');
        Route::delete('/billets/bulk-delete', [AdminBilletController::class, 'bulkDelete'])->name('billets.bulk-delete');
        Route::post('/billets/validate-all', [AdminBilletController::class, 'validateAll'])->name('billets.validate-all');
        Route::post('/billets/validate-multiple', [AdminBilletController::class, 'validateMultiple'])->name('billets.validate-multiple');
        Route::get('/billets/filtered-ids', [AdminBilletController::class, 'getFilteredIds'])->name('billets.filtered-ids');
        
        // ========== GESTION DES LOGS ==========
        Route::get('/logs', [AdminLogController::class, 'index'])->name('logs.index');
        Route::get('/logs/export', [AdminLogController::class, 'export'])->name('logs.export');
        Route::get('/logs/{log}', [AdminLogController::class, 'show'])->name('logs.show');
        Route::delete('/logs/{log}', [AdminLogController::class, 'destroy'])->name('logs.destroy');
        Route::post('/logs/clean', [AdminLogController::class, 'clean'])->name('logs.clean');
        Route::delete('/logs/level/{niveau}', [AdminLogController::class, 'deleteByLevel'])->name('logs.delete-level');
        
        // ========== PARAMÈTRES ==========
        Route::get('/parametres', [AdminParametreController::class, 'index'])->name('parametres.index');
        Route::post('/parametres', [AdminParametreController::class, 'update'])->name('parametres.update');
        Route::post('/parametres/remove-image', [AdminParametreController::class, 'removeProfileImage'])->name('profile.remove-image');
        Route::post('/parametres/clear-cache', [AdminParametreController::class, 'clearCache'])->name('parametres.clear-cache');
        Route::post('/parametres/optimize', [AdminParametreController::class, 'optimize'])->name('parametres.optimize');
        Route::post('/parametres/clean-database', [AdminParametreController::class, 'cleanDatabase'])->name('parametres.clean-database');
        Route::get('/parametres/export-database', [AdminParametreController::class, 'exportDatabase'])->name('parametres.export-database');
        
        // ========== GESTION DES NOTIFICATIONS ==========
        Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/mark-read', function () {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['message' => 'Non authentifié'], 401);
            }
            \App\Models\Notification::where('user_id', $user->id)->where('is_read', false)->update(['is_read' => true, 'read_at' => now()]);
            return response()->json(['success' => true]);
        })->name('notifications.mark-read');
        // Admin profile shortcut (redirect to global settings profile tab)
        Route::get('/profile', function () {
            return redirect()->route('admin.parametres.index', ['tab' => 'profile']);
        })->name('profile.edit');

        Route::get('/profile/password', function () {
            return redirect()->route('admin.parametres.index', ['tab' => 'profile']);
        })->name('profile.password');

        // ========== GESTION DES LIEUX ==========
        Route::get('/lieux', [App\Http\Controllers\Admin\LieuController::class, 'index'])->name('lieux.index');
        Route::get('/lieux/create', [App\Http\Controllers\Admin\LieuController::class, 'create'])->name('lieux.create');
        Route::post('/lieux', [App\Http\Controllers\Admin\LieuController::class, 'store'])->name('lieux.store');
        Route::get('/lieux/export', [App\Http\Controllers\Admin\LieuController::class, 'export'])->name('lieux.export');
        Route::get('/lieux/{lieu}', [App\Http\Controllers\Admin\LieuController::class, 'show'])->name('lieux.show');
        Route::get('/lieux/{lieu}/edit', [App\Http\Controllers\Admin\LieuController::class, 'edit'])->name('lieux.edit');
        Route::put('/lieux/{lieu}', [App\Http\Controllers\Admin\LieuController::class, 'update'])->name('lieux.update');
        Route::delete('/lieux/{lieu}', [App\Http\Controllers\Admin\LieuController::class, 'destroy'])->name('lieux.destroy');
    });
// ==================== ROUTES ORGANISATEUR ====================
Route::middleware(['auth', 'role:Organisateur'])
    ->prefix('organisateur')
    ->name('organisateur.')
    ->group(function () {
        
        Route::get('/dashboard', [OrganisateurDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profil', [OrganisateurProfilController::class, 'edit'])->name('profil');
        Route::get('/profil/edit', [OrganisateurProfilController::class, 'edit'])->name('profil.edit');
        Route::put('/profil', [OrganisateurProfilController::class, 'update'])->name('profil.update');
        Route::put('/profil/password', [OrganisateurProfilController::class, 'updatePassword'])->name('profil.password');
        
        Route::resource('evenements', OrganisateurEvenementController::class);
        Route::patch('/evenements/{evenement}/publish', [OrganisateurEvenementController::class, 'publish'])->name('evenements.publish');
        Route::get('/evenements/{evenement}/participants', [OrganisateurEvenementController::class, 'participants'])->name('evenements.participants');
        Route::post('/evenements/{evenement}/participants/export', [OrganisateurEvenementController::class, 'exportParticipants'])->name('evenements.participants.export');
        
        Route::get('/statistiques', [OrganisateurDashboardController::class, 'statistiques'])->name('statistiques');
        Route::get('/scan', [OrganisateurDashboardController::class, 'scan'])->name('scan');
        Route::post('/scan/verify', [OrganisateurDashboardController::class, 'verifyTicket'])->name('scan.verify');
        
        Route::get('/ventes', [OrganisateurVenteController::class, 'index'])->name('ventes.index');
        Route::get('/ventes/evenement/{evenement}', [OrganisateurVenteController::class, 'byEvent'])->name('ventes.event');
        Route::get('/ventes/{commande}', [OrganisateurVenteController::class, 'show'])->name('ventes.show');
        Route::get('/ventes/{commande}/facture', [OrganisateurVenteController::class, 'facture'])->name('ventes.facture');
        Route::get('/ventes/{commande}/download', [OrganisateurVenteController::class, 'downloadFacture'])->name('ventes.download');
        Route::get('/ventes/{commande}/billets', [OrganisateurVenteController::class, 'billets'])->name('ventes.billets');
        Route::post('/ventes/{commande}/send-invoice', [OrganisateurVenteController::class, 'sendInvoice'])->name('ventes.send-invoice');
        Route::get('/ventes/export', [OrganisateurVenteController::class, 'export'])->name('ventes.export');

        Route::get('/billets', [OrganisateurBilletController::class, 'index'])->name('billets.index');
        Route::get('/billets/{billet}/download', [OrganisateurBilletController::class, 'download'])->name('billets.download');
        Route::post('/billets/verify', [OrganisateurBilletController::class, 'verify'])->name('billets.verify');
        Route::post('/billets/{billet}/valider', [OrganisateurBilletController::class, 'valider'])->name('billets.valider');
        Route::get('/billets/event/{evenement}/tickets', [OrganisateurBilletController::class, 'getTicketsByEvent'])->name('billets.get-by-event');
        Route::post('/billets/validate-multiple', [OrganisateurBilletController::class, 'validateMultiple'])->name('billets.validate-multiple');
    });

// ==================== ROUTES CLIENT ====================
Route::middleware(['auth', 'role:Client'])->prefix('client')->name('client.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
    
    // Événements
    Route::prefix('evenements')->name('evenements.')->group(function () {
        Route::get('/', [ClientEvenementController::class, 'index'])->name('index');
        Route::get('/{evenement}', [ClientEvenementController::class, 'show'])->name('show');
    });
    
    // Panier
    Route::prefix('panier')->name('panier.')->group(function () {
        Route::post('/ajouter', [PanierController::class, 'ajouter'])->name('ajouter');
        Route::get('/', [PanierController::class, 'index'])->name('index');
        Route::post('/modifier-quantite/{index}', [PanierController::class, 'modifierQuantite'])->name('modifier-quantite');
        Route::delete('/supprimer/{id}', [PanierController::class, 'supprimer'])->name('supprimer');
        Route::delete('/vider', [PanierController::class, 'vider'])->name('vider');
        Route::get('/paiement', [PanierController::class, 'paiement'])->name('paiement');
        Route::post('/paiement', [PanierController::class, 'processPaiement'])->name('paiement.process');
        Route::get('/confirmation/{commande}', [PanierController::class, 'confirmation'])->name('confirmation');
    });
    
    // Commandes
    Route::prefix('commandes')->name('commandes.')->group(function () {
        Route::get('/', [ClientCommandeController::class, 'index'])->name('index');
        Route::get('/{commande}', [ClientCommandeController::class, 'show'])->name('show');
        Route::get('/{commande}/facture', [ClientCommandeController::class, 'facture'])->name('facture');
        Route::post('/{commande}/send-invoice', [ClientCommandeController::class, 'sendInvoice'])->name('send-invoice');
    });
    
    // Billets
    Route::prefix('billets')->name('billets.')->group(function () {
        Route::get('/', [ClientBilletController::class, 'index'])->name('index');
        Route::get('/{billet}', [ClientBilletController::class, 'show'])->name('show');
        Route::get('/{billet}/download', [ClientBilletController::class, 'download'])->name('download');
        Route::get('/evenement/{evenement}', [ClientBilletController::class, 'byEvent'])->name('event');
        Route::get('/evenement/{evenement}/download-all', [ClientBilletController::class, 'downloadAllByEvent'])->name('event.download-all');
        Route::get('/download-all', [ClientBilletController::class, 'downloadAll'])->name('download-all');
    });
    
    // Profil
    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('/', [ClientProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ClientProfileController::class, 'update'])->name('update');
        Route::put('/password', [ClientProfileController::class, 'updatePassword'])->name('password');
        Route::get('/export', [ClientProfileController::class, 'export'])->name('export');
        Route::delete('/', [ClientProfileController::class, 'destroy'])->name('destroy');
    });
});

// Webhook pour les paiements (public)
Route::post('/webhook/paiement', [PaiementController::class, 'webhook'])->name('webhook.paiement');
// ==================== API ROUTES ====================
Route::prefix('api')->group(function () {
    Route::post('/webhooks/stripe', [App\Http\Controllers\Api\WebhookController::class, 'handleStripe']);
    Route::post('/webhooks/paypal', [App\Http\Controllers\Api\WebhookController::class, 'handlePaypal']);
    Route::get('/verify/{code}', [App\Http\Controllers\Api\BilletVerificationController::class, 'verify'])->name('api.verify');
});

// ==================== ROUTES DE TEST ====================
Route::get('/test-qr', function() {
    try {
        $qr = QrCode::size(200)->generate('test');
        return $qr;
    } catch (\Exception $e) {
        return 'Erreur: ' . $e->getMessage();
    }
});