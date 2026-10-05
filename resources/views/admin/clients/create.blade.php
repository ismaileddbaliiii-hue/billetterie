{{-- resources/views/admin/clients/create.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Ajouter un client')
@section('page-title', 'Ajouter un client')
@section('page-description', 'Créez un nouveau compte client sur la plateforme')

@section('page-actions')
    <a href="{{ route('admin.clients.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Retour
    </a>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- En-tête -->
    <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-xl shadow-lg mb-6 overflow-hidden">
        <div class="px-6 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="h-16 w-16 bg-white bg-opacity-20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">Nouveau client</h2>
                        <p class="text-green-100 mt-1">Créez un compte client pour un nouvel utilisateur</p>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="text-right">
                        <p class="text-green-100 text-sm">Formulaire de création</p>
                        <p class="text-white font-semibold">Tous les champs marqués * sont obligatoires</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire principal -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Informations personnelles</h3>
            </div>
        </div>
        
        <form action="{{ route('admin.clients.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
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
                            <input type="text" name="prenom" value="{{ old('prenom') }}" required
                                   class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   placeholder="Jean">
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
                            <input type="text" name="nom" value="{{ old('nom') }}" required
                                   class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   placeholder="Dupont">
                        </div>
                        @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
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
                               class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
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
                               class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                               placeholder="+33 6 12 34 56 78">
                    </div>
                    @error('telephone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Photo de profil -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Photo de profil
                    </label>
                    <input type="file" name="profile_image" accept="image/*"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    @error('profile_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-500 mt-1">Formats : JPG, PNG, GIF, WEBP. Max 2MB.</p>
                </div>
                
                <!-- Mot de passe -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex items-center mb-4">
                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6-4h12a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 4v2a2 2 0 01-2 2H8a2 2 0 01-2-2v-2"></path>
                        </svg>
                        <h4 class="text-md font-semibold text-gray-900">Sécurité du compte</h4>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">Créez un mot de passe sécurisé pour le client</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Mot de passe <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6-4h12a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 4v2a2 2 0 01-2 2H8a2 2 0 01-2-2v-2"></path>
                                </svg>
                                <input type="password" name="password" id="password" required
                                       class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       placeholder="••••••••">
                            </div>
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            <div class="mt-2">
                                <div class="flex items-center space-x-2">
                                    <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div id="passwordStrengthBar" class="h-full w-0 transition-all duration-300 rounded-full"></div>
                                    </div>
                                    <span id="passwordStrengthText" class="text-xs text-gray-500">Non défini</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Minimum 8 caractères</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Confirmer le mot de passe <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                       class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       placeholder="••••••••">
                            </div>
                            <div id="passwordMatch" class="text-xs mt-1 hidden">
                                <span id="passwordMatchText"></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Informations supplémentaires -->
                <div class="bg-blue-50 rounded-xl p-4 mt-4">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-800">Informations importantes</p>
                            <p class="text-xs text-blue-700 mt-1">
                                Un email de bienvenue sera automatiquement envoyé au client avec ses identifiants de connexion.
                                Le client pourra se connecter avec son email et le mot de passe que vous venez de créer.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Boutons d'action -->
            <div class="mt-8 flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.clients.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200 font-medium">
                    Annuler
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white rounded-lg transition-all duration-200 font-medium shadow-sm">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Créer le client
                </button>
            </div>
        </form>
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
            color = 'bg-gray-300';
            text = 'Non défini';
            width = '0%';
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