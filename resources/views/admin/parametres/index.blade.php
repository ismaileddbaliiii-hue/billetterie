{{-- resources/views/admin/parametres/index.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Paramètres')
@section('page-title', 'Paramètres')
@section('page-description', 'Configuration de l\'application')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-gradient-to-r from-green-50 to-emerald-50 px-5 py-4 text-green-800 animate-fade-in shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500 text-lg mr-3"></i>
                </div>
                <div>
                    <strong class="font-semibold">Succès !</strong>
                    <span class="ml-1">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-gradient-to-r from-red-50 to-rose-50 px-5 py-4 text-red-800 animate-fade-in shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500 text-lg mr-3"></i>
                </div>
                <div>
                    <strong class="font-semibold">Erreur :</strong>
                    <span class="ml-1">{{ session('error') }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistiques globales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-5 text-white transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total Utilisateurs</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_users'] ?? 0 }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-5 text-white transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Tickets Actifs</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_tickets'] ?? 0 }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-ticket-alt text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-5 text-white transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Tickets Résolus</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['resolved_tickets'] ?? 0 }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl shadow-lg p-5 text-white transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">Tickets Urgents</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['urgent_tickets'] ?? 0 }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl shadow-lg p-5 text-white transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm">Tickets Expirés</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['expired_tickets'] ?? 0 }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Onglets de navigation -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="border-b border-gray-200">
            <nav class="flex flex-wrap -mb-px">
                <button class="tab-button active inline-flex items-center px-6 py-4 border-b-2 border-blue-600 text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors duration-200" data-tab="general">
                    <i class="fas fa-cog mr-2"></i>
                    Informations Générales
                </button>
                <button class="tab-button inline-flex items-center px-6 py-4 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors duration-200" data-tab="appearance">
                    <i class="fas fa-palette mr-2"></i>
                    Apparence & Logo
                </button>
                <button class="tab-button inline-flex items-center px-6 py-4 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors duration-200" data-tab="profile">
                    <i class="fas fa-user-circle mr-2"></i>
                    Mon Profil
                </button>
                <button class="tab-button inline-flex items-center px-6 py-4 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors duration-200" data-tab="security">
                    <i class="fas fa-shield-alt mr-2"></i>
                    Sécurité & Maintenance
                </button>
                <button class="tab-button inline-flex items-center px-6 py-4 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors duration-200" data-tab="system">
                    <i class="fas fa-server mr-2"></i>
                    Système
                </button>
            </nav>
        </div>

        <!-- Tab: Informations Générales -->
        <div id="tab-general" class="tab-content p-6">
            <form action="{{ route('admin.parametres.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="form_type" value="general">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-globe text-blue-500 mr-2"></i>
                            Nom du site
                        </label>
                        <input type="text" name="site_name" 
                               value="{{ old('site_name', $settings['site_name'] ?? config('app.name')) }}" 
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope text-blue-500 mr-2"></i>
                            Email du site
                        </label>
                        <input type="email" name="site_email" 
                               value="{{ old('site_email', $settings['site_email'] ?? 'contact@example.com') }}" 
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-phone text-blue-500 mr-2"></i>
                            Téléphone
                        </label>
                        <input type="text" name="site_phone" 
                               value="{{ old('site_phone', $settings['site_phone'] ?? '') }}" 
                               placeholder="+212 XXX-XXXXXX"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-money-bill-wave text-blue-500 mr-2"></i>
                            Monnaie par défaut
                        </label>
                        <select name="default_currency" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="MAD" {{ ($settings['default_currency'] ?? 'MAD') == 'MAD' ? 'selected' : '' }}>MAD - Dirham marocain</option>
                            <option value="EUR" {{ ($settings['default_currency'] ?? 'MAD') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                            <option value="USD" {{ ($settings['default_currency'] ?? 'MAD') == 'USD' ? 'selected' : '' }}>USD - Dollar US</option>
                        </select>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                            Adresse complète
                        </label>
                        <textarea name="site_address" rows="3" 
                                  placeholder="Adresse complète du site"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('site_address', $settings['site_address'] ?? '') }}</textarea>
                    </div>
                </div>
                
                <div class="flex justify-end mt-6 pt-4 border-t">
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-2.5 px-8 rounded-xl transition-all duration-200 flex items-center shadow-md">
                        <i class="fas fa-save mr-2"></i> 
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>

        <!-- Tab: Apparence & Logo -->
        <div id="tab-appearance" class="tab-content hidden p-6">
            <form action="{{ route('admin.parametres.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="form_type" value="appearance">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Logo Section -->
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-image text-blue-500 mr-2"></i>
                            Logo du site
                        </h4>
                        <div class="flex flex-col items-center">
                            @if(isset($settings['site_logo']) && !empty($settings['site_logo']) && Storage::disk('public')->exists($settings['site_logo']))
                                <div class="mb-4 p-4 bg-white rounded-xl border border-gray-200 shadow-sm">
                                    <img src="{{ Storage::url($settings['site_logo']) }}" alt="Logo" class="h-20 w-auto">
                                </div>
                            @else
                                <div class="mb-4 p-4 bg-white rounded-xl border border-gray-200 shadow-sm">
                                    <i class="fas fa-image text-gray-400 text-5xl"></i>
                                </div>
                            @endif
                            <input type="file" name="site_logo" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/svg+xml" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-2">Formats: JPG, PNG, GIF, WEBP, SVG. Max 2MB</p>
                        </div>
                    </div>

                    <!-- Favicon Section -->
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-star text-blue-500 mr-2"></i>
                            Favicon
                        </h4>
                        <div class="flex flex-col items-center">
                            @if(isset($settings['favicon']) && !empty($settings['favicon']) && Storage::disk('public')->exists($settings['favicon']))
                                <div class="mb-4 p-4 bg-white rounded-xl border border-gray-200 shadow-sm">
                                    <img src="{{ Storage::url($settings['favicon']) }}" alt="Favicon" class="h-12 w-auto">
                                </div>
                            @else
                                <div class="mb-4 p-4 bg-white rounded-xl border border-gray-200 shadow-sm">
                                    <i class="fas fa-star text-gray-400 text-3xl"></i>
                                </div>
                            @endif
                            <input type="file" name="favicon" accept="image/x-icon,image/png" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-2">Formats: ICO, PNG. Max 512KB</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end mt-6 pt-4 border-t">
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-2.5 px-8 rounded-xl transition-all duration-200 flex items-center shadow-md">
                        <i class="fas fa-save mr-2"></i> 
                        Mettre à jour l'apparence
                    </button>
                </div>
            </form>
        </div>

        <!-- Tab: Mon Profil -->
        <div id="tab-profile" class="tab-content hidden p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Profile Image Section -->
                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 text-center border border-blue-100">
                        <div class="relative inline-block">
                            @if(Auth::user()->profile_image && Storage::disk('public')->exists(Auth::user()->profile_image))
                                <img src="{{ Storage::url(Auth::user()->profile_image) }}" 
                                     alt="Profil admin" 
                                     class="h-32 w-32 rounded-full object-cover border-4 border-blue-500 shadow-lg mx-auto">
                                <button type="button" onclick="removeProfileImage()" 
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-2 text-xs hover:bg-red-600 transition-all duration-200 shadow-md">
                                    <i class="fas fa-times"></i>
                                </button>
                            @else
                                <div class="h-32 w-32 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg mx-auto">
                                    <i class="fas fa-user text-white text-5xl"></i>
                                </div>
                            @endif
                        </div>
                        <h3 class="mt-4 text-xl font-bold text-gray-800">{{ Auth::user()->name }}</h3>
                        <p class="text-gray-600">{{ Auth::user()->email }}</p>
                        <p class="text-sm text-gray-500 mt-2">Rôle: Administrateur</p>
                    </div>
                </div>

                <!-- Profile Info Form -->
                <div class="lg:col-span-2">
                    <form action="{{ route('admin.parametres.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <input type="hidden" name="form_type" value="profile">
                            <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-user-edit text-blue-500 mr-2"></i>
                                Informations personnelles
                            </h4>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nom complet</label>
                                    <input type="text" name="name" value="{{ Auth::user()->name }}" 
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" value="{{ Auth::user()->email }}" 
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Photo de profil</label>
                                    <input type="file" name="profile_image" accept="image/*" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700">
                                </div>
                            </div>
                            
                            <div class="flex justify-end mt-6 pt-4 border-t">
                                <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-2.5 px-8 rounded-xl transition-all duration-200">
                                    <i class="fas fa-save mr-2"></i> 
                                    Mettre à jour le profil
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Change Password Form -->
                    <form action="{{ route('admin.parametres.update') }}" method="POST" class="mt-6">
                        @csrf
                        <input type="hidden" name="change_password" value="1">
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-key text-blue-500 mr-2"></i>
                                Changer le mot de passe
                            </h4>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mot de passe actuel</label>
                                    <input type="password" name="current_password" 
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nouveau mot de passe</label>
                                    <input type="password" name="new_password" 
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Confirmer le mot de passe</label>
                                    <input type="password" name="new_password_confirmation" 
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            
                            <div class="flex justify-end mt-6 pt-4 border-t">
                                <button type="submit" class="bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 text-white font-semibold py-2.5 px-8 rounded-xl transition-all duration-200">
                                    <i class="fas fa-key mr-2"></i> 
                                    Changer le mot de passe
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tab: Sécurité & Maintenance -->
        <div id="tab-security" class="tab-content hidden p-6">
            <form action="{{ route('admin.parametres.update') }}" method="POST">
                @csrf
                <input type="hidden" name="form_type" value="security">
                <div class="grid grid-cols-1 gap-6">
                    <!-- Maintenance Mode -->
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <div>
                                <h4 class="text-md font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-tools text-orange-500 mr-2"></i>
                                    Mode maintenance
                                </h4>
                                <p class="text-sm text-gray-600 mt-1">Activer le mode maintenance pour effectuer des travaux sur le site</p>
                                <p class="text-xs text-gray-500 mt-2">⚠️ Les utilisateurs non-administrateurs ne pourront pas accéder au site</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="maintenance_mode" value="1" class="sr-only peer" 
                                       {{ (isset($settings['maintenance_mode']) && $settings['maintenance_mode'] == 1) ? 'checked' : '' }}>
                                <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-orange-600"></div>
                                <span class="ml-3 text-sm font-medium text-gray-700" id="maintenance-status">
                                    {{ (isset($settings['maintenance_mode']) && $settings['maintenance_mode'] == 1) ? 'Activé' : 'Désactivé' }}
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Security Settings -->
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                            Paramètres de sécurité
                        </h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-700">Session timeout</p>
                                    <p class="text-xs text-gray-500">Déconnexion automatique après inactivité</p>
                                </div>
                                <select name="session_timeout" class="px-3 py-2 border border-gray-300 rounded-lg">
                                    <option value="15">15 minutes</option>
                                    <option value="30" selected>30 minutes</option>
                                    <option value="60">1 heure</option>
                                    <option value="120">2 heures</option>
                                </select>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-700">2FA (Double authentification)</p>
                                    <p class="text-xs text-gray-500">Sécuriser votre compte</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="two_factor" value="1" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end mt-6 pt-4 border-t">
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-2.5 px-8 rounded-xl transition-all duration-200">
                        <i class="fas fa-save mr-2"></i> 
                        Enregistrer les paramètres
                    </button>
                </div>
            </form>
        </div>

        <!-- Tab: Système -->
        <div id="tab-system" class="tab-content hidden p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
                    <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-server text-purple-500 mr-2"></i>
                        Informations système
                    </h4>
                    <div class="space-y-3">
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Version PHP</span>
                            <span class="font-semibold text-gray-800">{{ phpversion() }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Version Laravel</span>
                            <span class="font-semibold text-gray-800">{{ app()->version() }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Environnement</span>
                            <span class="font-semibold text-gray-800">{{ app()->environment() }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Dernière mise à jour</span>
                            <span class="font-semibold text-gray-800">{{ now()->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
                    <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-database text-green-500 mr-2"></i>
                        Cache & Performance
                    </h4>
                    <div class="space-y-3">
                        <button onclick="clearCache()" class="w-full bg-white hover:bg-gray-50 text-gray-700 font-semibold py-2 px-4 rounded-lg border border-gray-300 transition-all duration-200 flex items-center justify-center">
                            <i class="fas fa-trash-alt mr-2 text-red-500"></i>
                            Vider le cache
                        </button>
                        <button onclick="optimizeApp()" class="w-full bg-white hover:bg-gray-50 text-gray-700 font-semibold py-2 px-4 rounded-lg border border-gray-300 transition-all duration-200 flex items-center justify-center">
                            <i class="fas fa-tachometer-alt mr-2 text-blue-500"></i>
                            Optimiser l'application
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Tab switching functionality
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', () => {
        const tabId = button.getAttribute('data-tab');
        setActiveTab(tabId);
        const newUrl = new URL(window.location.href);
        newUrl.searchParams.set('tab', tabId);
        window.history.replaceState({}, '', newUrl);
    });
});

// Maintenance mode toggle text update
document.querySelector('input[name="maintenance_mode"]')?.addEventListener('change', function() {
    const statusSpan = document.getElementById('maintenance-status');
    if (this.checked) {
        statusSpan.textContent = 'Activé';
        statusSpan.classList.remove('text-gray-700');
        statusSpan.classList.add('text-orange-600', 'font-semibold');
    } else {
        statusSpan.textContent = 'Désactivé';
        statusSpan.classList.remove('text-orange-600', 'font-semibold');
        statusSpan.classList.add('text-gray-700');
    }
});

function removeProfileImage() {
    if (confirm('Voulez-vous vraiment supprimer votre photo de profil ?')) {
        fetch('{{ route("admin.profile.remove-image") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  location.reload();
              }
          });
    }
}

function clearCache() {
    if (confirm('Voulez-vous vider le cache ?')) {
        fetch('{{ route("admin.parametres.clear-cache") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  alert('Cache vidé avec succès !');
              }
          });
    }
}

function optimizeApp() {
    if (confirm('Optimiser l\'application ?')) {
        fetch('{{ route("admin.parametres.optimize") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  alert('Application optimisée avec succès !');
              }
          });
    }
}

function setActiveTab(tabId) {
    const validTabs = ['general', 'appearance', 'profile', 'security', 'system'];
    if (!validTabs.includes(tabId)) {
        tabId = 'general';
    }

    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active', 'border-blue-600', 'text-blue-600');
        btn.classList.remove('border-transparent', 'text-gray-500');
        btn.classList.add('border-transparent', 'text-gray-500');
    });

    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });

    const activeBtn = document.querySelector(`.tab-button[data-tab="${tabId}"]`);
    const activeContent = document.getElementById(`tab-${tabId}`);

    if (activeBtn && activeContent) {
        activeBtn.classList.add('active', 'border-blue-600', 'text-blue-600');
        activeBtn.classList.remove('border-transparent', 'text-gray-500');
        activeContent.classList.remove('hidden');
        activeContent.classList.add('block');
    }
}

// auto-hold success/error messages
function hideAlerts() {
    document.querySelectorAll('.animate-fade-in').forEach(el => {
        setTimeout(() => {
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 300);
        }, 5000);
    });
}

function getQueryParam(name) {
    return new URLSearchParams(window.location.search).get(name);
}

const initialTab = getQueryParam('tab') || 'general';
setActiveTab(initialTab);
hideAlerts();
</script>
@endpush
@endsection