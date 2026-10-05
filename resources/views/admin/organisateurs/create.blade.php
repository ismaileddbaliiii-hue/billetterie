{{-- resources/views/admin/organisateurs/create.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Ajouter un organisateur')
@section('page-title', 'Ajouter un organisateur')
@section('page-description', 'Créez un nouveau compte organisateur sur la plateforme')

@section('page-actions')
    <a href="{{ route('admin.organisateurs.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Retour
    </a>
@endsection

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- En-tête avec icône -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg mb-6 overflow-hidden">
        <div class="px-6 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="h-16 w-16 bg-white bg-opacity-20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">Nouvel organisateur</h2>
                        <p class="text-blue-100 mt-1">Remplissez les informations pour créer un nouveau compte</p>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="text-right">
                        <p class="text-blue-100 text-sm">Formulaire de création</p>
                        <p class="text-white font-semibold">Tous les champs marqués * sont obligatoires</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire principal -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <form action="{{ route('admin.organisateurs.store') }}" method="POST" enctype="multipart/form-data" class="divide-y divide-gray-200">
            @csrf
            
            <!-- Section 1: Informations personnelles -->
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 ml-3">Informations personnelles</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Prénom -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Prénom <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <input type="text" name="prenom" value="{{ old('prenom') }}" required
                                   class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   placeholder="Jean">
                        </div>
                        @error('prenom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Nom -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nom <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <input type="text" name="nom" value="{{ old('nom') }}" required
                                   class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   placeholder="Dupont">
                        </div>
                        @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Adresse email <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   placeholder="jean.dupont@email.com">
                        </div>
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Téléphone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Téléphone
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <input type="tel" name="telephone" value="{{ old('telephone') }}"
                                   class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   placeholder="+33 6 12 34 56 78">
                        </div>
                        @error('telephone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Photo de profil
                        </label>
                        <input type="file" name="profile_image" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        @error('profile_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <p class="text-xs text-gray-500 mt-1">Formats : JPG, PNG, GIF, WEBP. Max 2MB.</p>
                    </div>
                    
                    <!-- Mot de passe -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Mot de passe <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6-4h12a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 4v2a2 2 0 01-2 2H8a2 2 0 01-2-2v-2"></path>
                            </svg>
                            <input type="password" name="password" id="password" required
                                   class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   placeholder="••••••••">
                        </div>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <p class="text-xs text-gray-500 mt-1">Minimum 8 caractères</p>
                    </div>
                    
                    <!-- Confirmation mot de passe -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmer le mot de passe <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <input type="password" name="password_confirmation" required
                                   class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   placeholder="••••••••">
                        </div>
                    </div>
                </div>
                
                <!-- Bio -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Biographie</label>
                    <textarea name="bio" rows="4" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                              placeholder="Présentation de l'organisateur...">{{ old('bio') }}</textarea>
                    @error('bio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-500 mt-1">Maximum 1000 caractères</p>
                </div>
            </div>
            
            <!-- Section 2: Permissions -->
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="h-10 w-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6-4h12a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 4v2a2 2 0 01-2 2H8a2 2 0 01-2-2v-2"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 ml-3">Permissions</h3>
                </div>
                
                <p class="text-sm text-gray-600 mb-4">Définissez les droits d'accès accordés à cet organisateur</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Création d'événements -->
                    <div class="relative">
                        <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-green-500 to-green-600 rounded-l-lg"></div>
                        <label class="flex items-start p-4 ml-2 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-all duration-200 border border-gray-200 hover:border-green-200">
                            <input type="checkbox" name="perm_creation_event" value="1" class="mt-1 h-4 w-4 text-green-600 rounded border-gray-300 focus:ring-green-500" {{ old('perm_creation_event') ? 'checked' : '' }}>
                            <div class="ml-3 flex-1">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <p class="font-medium text-gray-900">Créer des événements</p>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">L'organisateur peut créer de nouveaux événements sur la plateforme</p>
                            </div>
                        </label>
                    </div>
                    
                    <!-- Modification d'événements -->
                    <div class="relative">
                        <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-blue-500 to-blue-600 rounded-l-lg"></div>
                        <label class="flex items-start p-4 ml-2 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-all duration-200 border border-gray-200 hover:border-blue-200">
                            <input type="checkbox" name="perm_modification_event" value="1" class="mt-1 h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500" {{ old('perm_modification_event') ? 'checked' : '' }}>
                            <div class="ml-3 flex-1">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <p class="font-medium text-gray-900">Modifier des événements</p>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">L'organisateur peut modifier ses événements existants</p>
                            </div>
                        </label>
                    </div>
                    
                    <!-- Suppression d'événements -->
                    <div class="relative">
                        <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-red-500 to-red-600 rounded-l-lg"></div>
                        <label class="flex items-start p-4 ml-2 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-all duration-200 border border-gray-200 hover:border-red-200">
                            <input type="checkbox" name="perm_suppression_event" value="1" class="mt-1 h-4 w-4 text-red-600 rounded border-gray-300 focus:ring-red-500" {{ old('perm_suppression_event') ? 'checked' : '' }}>
                            <div class="ml-3 flex-1">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <p class="font-medium text-gray-900">Supprimer des événements</p>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">L'organisateur peut supprimer ses événements (si aucune vente)</p>
                            </div>
                        </label>
                    </div>
                    
                    <!-- Visualisation des statistiques -->
                    <div class="relative">
                        <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-purple-500 to-purple-600 rounded-l-lg"></div>
                        <label class="flex items-start p-4 ml-2 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-all duration-200 border border-gray-200 hover:border-purple-200">
                            <input type="checkbox" name="perm_view_stats" value="1" class="mt-1 h-4 w-4 text-purple-600 rounded border-gray-300 focus:ring-purple-500" {{ old('perm_view_stats') ? 'checked' : '' }}>
                            <div class="ml-3 flex-1">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    <p class="font-medium text-gray-900">Voir les statistiques</p>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">L'organisateur peut consulter ses statistiques de ventes</p>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-800">Informations importantes</p>
                            <p class="text-xs text-blue-700 mt-1">Ces permissions peuvent être modifiées ultérieurement depuis la page de gestion des organisateurs. Un email de bienvenue sera automatiquement envoyé à l'organisateur.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Boutons d'action -->
            <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                <a href="{{ route('admin.organisateurs.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200 font-medium">
                    Annuler
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg transition-all duration-200 font-medium shadow-sm">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Créer l'organisateur
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Ajout d'un indicateur de force du mot de passe
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = getPasswordStrength(password);
            
            // Vous pouvez ajouter un indicateur de force ici
        });
        
        function getPasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]+/)) strength++;
            if (password.match(/[A-Z]+/)) strength++;
            if (password.match(/[0-9]+/)) strength++;
            if (password.match(/[$@#&!]+/)) strength++;
            return strength;
        }
    }
</script>
@endpush
@endsection