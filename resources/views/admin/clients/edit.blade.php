{{-- resources/views/admin/clients/edit.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Modifier client - ' . $user->prenom . ' ' . $user->nom)
@section('page-title', 'Modifier le client')
@section('page-description', $user->prenom . ' ' . $user->nom)

@section('page-actions')
    <a href="{{ route('admin.clients.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Retour
    </a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- En-tête -->
    <div class="bg-gradient-to-r from-amber-600 to-orange-600 rounded-xl shadow-lg mb-6 overflow-hidden">
        <div class="px-6 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="h-16 w-16 bg-white bg-opacity-20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">Modifier le client</h2>
                        <p class="text-amber-100 mt-1">Client #{{ $user->id }} - {{ $user->prenom }} {{ $user->nom }}</p>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="text-right">
                        <p class="text-amber-100 text-sm">Formulaire de modification</p>
                        <p class="text-white font-semibold">Modifiez les informations du client</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulaire principal -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Informations personnelles</h3>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('admin.clients.update', $user->id) }}" enctype="multipart/form-data" class="p-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <!-- Prénom et Nom -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Prénom <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <input type="text" 
                                           name="prenom" 
                                           value="{{ old('prenom', $user->prenom) }}"
                                           class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('prenom') border-red-500 @enderror">
                                </div>
                                @error('prenom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nom <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <input type="text" 
                                           name="nom" 
                                           value="{{ old('nom', $user->nom) }}"
                                           class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('nom') border-red-500 @enderror">
                                </div>
                                @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        
                        <!-- Email et Téléphone -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Adresse email <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <input type="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email) }}"
                                           class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('email') border-red-500 @enderror">
                                </div>
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <input type="tel" 
                                           name="telephone" 
                                           value="{{ old('telephone', $user->telephone) }}"
                                           class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Photo de profil -->
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-md font-semibold text-gray-900 mb-3">Photo de profil</h4>
                            <div class="flex items-center gap-4">
                                <div class="w-20 h-20 rounded-full overflow-hidden border border-gray-200 bg-gray-100">
                                    @if($user->profile_image)
                                        <img src="{{ Storage::disk('public')->exists($user->profile_image) ? Storage::url($user->profile_image) : asset('storage/'.$user->profile_image) }}" alt="Profile" class="w-full h-full object-cover">
                                    @else
                                        <div class="flex items-center justify-center w-full h-full text-gray-500">Aucun</div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="profile_image" accept="image/*" class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('profile_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    <p class="text-xs text-gray-500 mt-1">Formats : JPG, PNG, GIF, WEBP. Max 2MB.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Statut du compte -->
                        <div class="border-t border-gray-200 pt-6">
                            <div class="flex items-center mb-4">
                                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h4 class="text-md font-semibold text-gray-900">Statut du compte</h4>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-all duration-200 {{ $user->active ? 'bg-green-50 border-green-200' : '' }}">
                                    <input type="radio" name="active" value="1" {{ $user->active ? 'checked' : '' }} class="h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500">
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">Actif</span>
                                        <span class="block text-xs text-gray-500">Le client peut se connecter et acheter des billets</span>
                                    </div>
                                </label>
                                <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-all duration-200 {{ !$user->active ? 'bg-red-50 border-red-200' : '' }}">
                                    <input type="radio" name="active" value="0" {{ !$user->active ? 'checked' : '' }} class="h-4 w-4 text-red-600 border-gray-300 focus:ring-red-500">
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">Suspendu</span>
                                        <span class="block text-xs text-gray-500">Le compte est désactivé et l'accès est bloqué</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Mot de passe -->
                        <div class="border-t border-gray-200 pt-6">
                            <div class="flex items-center mb-4">
                                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6-4h12a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 4v2a2 2 0 01-2 2H8a2 2 0 01-2-2v-2"></path>
                                </svg>
                                <h4 class="text-md font-semibold text-gray-900">Changer le mot de passe</h4>
                            </div>
                            <p class="text-sm text-gray-500 mb-4">Laissez vide pour conserver le mot de passe actuel</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe</label>
                                    <div class="relative">
                                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6-4h12a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 4v2a2 2 0 01-2 2H8a2 2 0 01-2-2v-2"></path>
                                        </svg>
                                        <input type="password" 
                                               name="password" 
                                               id="password"
                                               class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                    </div>
                                    <div class="mt-2">
                                        <div class="flex items-center space-x-2">
                                            <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                                <div id="passwordStrengthBar" class="h-full w-0 transition-all duration-300 rounded-full"></div>
                                            </div>
                                            <span id="passwordStrengthText" class="text-xs text-gray-500"></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirmer le mot de passe</label>
                                    <div class="relative">
                                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                        <input type="password" 
                                               name="password_confirmation" 
                                               id="password_confirmation"
                                               class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                    </div>
                                    <div id="passwordMatch" class="text-xs mt-1 hidden">
                                        <span id="passwordMatchText"></span>
                                    </div>
                                </div>
                            </div>
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="mt-8 flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.clients.index') }}" 
                           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200 font-medium">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg transition-all duration-200 font-medium shadow-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Colonne droite - Statistiques et infos -->
        <div class="space-y-6">
            <!-- Statistiques -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Statistiques</h3>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                        <span class="text-gray-600">ID Client</span>
                        <span class="font-mono text-sm font-semibold text-gray-900">#{{ $user->id }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                        <span class="text-gray-600">Date d'inscription</span>
                        <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                        <span class="text-gray-600">Dernière connexion</span>
                        <span class="font-medium text-gray-900">{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y H:i') : 'Jamais' }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                        <span class="text-gray-600">Commandes</span>
                        <span class="text-2xl font-bold text-blue-600">{{ $user->commandes_count ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                        <span class="text-gray-600">Billets achetés</span>
                        <span class="text-2xl font-bold text-green-600">{{ $user->billets_count ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total dépensé</span>
                        <span class="text-2xl font-bold text-purple-600">{{ number_format($user->total_depense ?? 0, 2, ',', ' ') }} DH</span>
                    </div>
                </div>
            </div>
            
            <!-- Informations supplémentaires -->
            <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-800">Note importante</p>
                        <p class="text-xs text-blue-700 mt-1">
                            La modification du statut du client affecte immédiatement sa capacité à se connecter.
                            Un email de notification sera envoyé en cas de changement de statut ou de mot de passe.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Actions supplémentaires -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Actions rapides</h3>
                    </div>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.clients.show', $user->id) }}" 
                       class="flex items-center justify-center w-full px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg transition-all duration-200 font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Voir les détails
                    </a>
                    <a href="{{ route('admin.clients.commandes', $user->id) }}" 
                       class="flex items-center justify-center w-full px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg transition-all duration-200 font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Voir les commandes
                    </a>
                    @if($user->active)
                        <button onclick="toggleStatus({{ $user->id }}, 'suspend')" 
                                class="flex items-center justify-center w-full px-4 py-2 bg-white border border-orange-300 hover:bg-orange-50 text-orange-600 rounded-lg transition-all duration-200 font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                            </svg>
                            Suspendre le compte
                        </button>
                    @else
                        <button onclick="toggleStatus({{ $user->id }}, 'activate')" 
                                class="flex items-center justify-center w-full px-4 py-2 bg-white border border-green-300 hover:bg-green-50 text-green-600 rounded-lg transition-all duration-200 font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Réactiver le compte
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Indicateur de force du mot de passe
const passwordInput = document.getElementById('password');
const confirmPasswordInput = document.getElementById('password_confirmation');
const strengthBar = document.getElementById('passwordStrengthBar');
const strengthText = document.getElementById('passwordStrengthText');

function getPasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]+/)) strength++;
    if (password.match(/[A-Z]+/)) strength++;
    if (password.match(/[0-9]+/)) strength++;
    if (password.match(/[$@#&!]+/)) strength++;
    return strength;
}

function updatePasswordStrength() {
    const password = passwordInput.value;
    if (password.length === 0) {
        strengthBar.style.width = '0%';
        strengthBar.className = 'h-full transition-all duration-300 rounded-full bg-gray-300';
        strengthText.textContent = '';
        return;
    }
    
    const strength = getPasswordStrength(password);
    
    let color, text, width;
    switch(strength) {
        case 0:
        case 1:
            color = 'bg-red-500';
            text = 'Très faible';
            width = '20%';
            break;
        case 2:
            color = 'bg-orange-500';
            text = 'Faible';
            width = '40%';
            break;
        case 3:
            color = 'bg-yellow-500';
            text = 'Moyen';
            width = '60%';
            break;
        case 4:
            color = 'bg-blue-500';
            text = 'Fort';
            width = '80%';
            break;
        case 5:
            color = 'bg-green-500';
            text = 'Très fort';
            width = '100%';
            break;
        default:
            return;
    }
    
    strengthBar.className = `h-full transition-all duration-300 rounded-full ${color}`;
    strengthBar.style.width = width;
    strengthText.textContent = text;
    strengthText.className = `text-xs ${strength >= 4 ? 'text-green-600' : (strength >= 2 ? 'text-orange-500' : 'text-red-500')}`;
}

function checkPasswordMatch() {
    const password = passwordInput.value;
    const confirm = confirmPasswordInput.value;
    const matchDiv = document.getElementById('passwordMatch');
    const matchText = document.getElementById('passwordMatchText');
    
    if (confirm.length > 0) {
        matchDiv.classList.remove('hidden');
        if (password === confirm) {
            matchText.innerHTML = '✓ Les mots de passe correspondent';
            matchText.className = 'text-green-600';
        } else {
            matchText.innerHTML = '✗ Les mots de passe ne correspondent pas';
            matchText.className = 'text-red-600';
        }
    } else {
        matchDiv.classList.add('hidden');
    }
}

function toggleStatus(id, action) {
    let url = action === 'suspend' 
        ? `/admin/clients/${id}/suspend`
        : `/admin/clients/${id}/activate`;
    
    let message = action === 'suspend' 
        ? 'Suspendre ce client ?' 
        : 'Activer ce client ?';
    
    if(confirm(message)) {
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
        }).then(response => response.json())
          .then(data => {
              if(data.success) {
                  location.reload();
              } else {
                  alert('Erreur lors du changement de statut');
              }
          }).catch(error => {
              console.error('Error:', error);
              alert('Une erreur est survenue');
          });
    }
}

if (passwordInput) {
    passwordInput.addEventListener('input', updatePasswordStrength);
    passwordInput.addEventListener('input', checkPasswordMatch);
}

if (confirmPasswordInput) {
    confirmPasswordInput.addEventListener('input', checkPasswordMatch);
}
</script>
@endpush
@endsection