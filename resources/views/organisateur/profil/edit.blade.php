@extends('organisateur.layouts.master')

@section('title', 'Mon Profil')
@section('page-title', 'Mon Profil')
@section('page-description', 'Gérez vos informations personnelles et sécurisez votre compte')

@section('content')
<div class="space-y-6">
    <!-- Profile Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-lg overflow-hidden">
        <div class="relative">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mt-32 -mr-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -mb-24 -ml-24"></div>
            <div class="relative z-10 p-6">
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <!-- Avatar -->
                    <div class="relative group">
                        <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-white/20 to-white/10 backdrop-blur-sm border-2 border-white/30 flex items-center justify-center shadow-xl">
                            @if(Auth::user()->profile_image && Storage::disk('public')->exists(Auth::user()->profile_image))
                                <img src="{{ Storage::url(Auth::user()->profile_image) }}" alt="Profil" class="w-full h-full object-cover rounded-2xl">
                            @else
                                <span class="text-white text-3xl font-bold">
                                    {{ strtoupper(substr(Auth::user()->prenom ?? 'U', 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom ?? 'U', 0, 1)) }}
                                </span>
                            @endif
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>
                    
                    <div class="text-center md:text-left flex-1">
                        <h1 class="text-2xl font-bold text-white">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</h1>
                        <p class="text-blue-100 text-sm mt-1">{{ Auth::user()->email }}</p>
                        <div class="flex flex-wrap items-center gap-3 mt-3 justify-center md:justify-start">
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-white/20 rounded-lg text-xs text-white">
                                <i class="fas fa-calendar-alt"></i>
                                Membre depuis {{ \Carbon\Carbon::parse(Auth::user()->created_at)->format('M Y') }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-white/20 rounded-lg text-xs text-white">
                                <i class="fas fa-check-circle"></i>
                                Compte vérifié
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Info Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user-edit text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">Informations personnelles</h2>
                            <p class="text-xs text-gray-500">Mettez à jour vos informations personnelles</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('organisateur.profil.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Photo Upload -->
                        <div class="mb-6 pb-6 border-b border-gray-100">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Photo de profil</label>
                            <div class="flex items-center gap-6">
                                <div class="relative">
                                    <div class="w-24 h-24 rounded-2xl overflow-hidden border-2 border-gray-200 shadow-md">
                                        @if(Auth::user()->profile_image && Storage::disk('public')->exists(Auth::user()->profile_image))
                                            <img src="{{ Storage::url(Auth::user()->profile_image) }}" alt="Profil" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-2xl font-bold">
                                                {{ strtoupper(substr(Auth::user()->prenom ?? 'U', 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-md">
                                        <i class="fas fa-camera text-blue-500 text-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="profile_image" accept="image/*" 
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all duration-200">
                                    <p class="text-xs text-gray-400 mt-2">Formats acceptés: JPG, PNG, GIF. Max 2MB</p>
                                    @error('profile_image')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user text-blue-500 mr-1"></i> Prénom
                                </label>
                                <input type="text" 
                                       name="prenom" 
                                       value="{{ old('prenom', Auth::user()->prenom) }}" 
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('prenom') border-red-500 @enderror">
                                @error('prenom')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user-tag text-blue-500 mr-1"></i> Nom
                                </label>
                                <input type="text" 
                                       name="nom" 
                                       value="{{ old('nom', Auth::user()->nom) }}" 
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('nom') border-red-500 @enderror">
                                @error('nom')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mt-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-envelope text-blue-500 mr-1"></i> Adresse email
                            </label>
                            <input type="email" 
                                   name="email" 
                                   value="{{ old('email', Auth::user()->email) }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mt-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-phone text-blue-500 mr-1"></i> Numéro de téléphone
                            </label>
                            <input type="tel" 
                                   name="telephone" 
                                   value="{{ old('telephone', Auth::user()->telephone) }}" 
                                   placeholder="+212 6XX XXX XXX"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            @error('telephone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mt-8 pt-4 border-t border-gray-100">
                            <button type="submit" 
                                    class="w-full md:w-auto px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                <i class="fas fa-save mr-2"></i>
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Security & Data -->
        <div class="space-y-6">
            <!-- Change Password Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-shield-alt text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">Sécurité du compte</h2>
                            <p class="text-xs text-gray-500">Changez votre mot de passe régulièrement</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('organisateur.profil.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-lock text-gray-500 mr-1"></i> Mot de passe actuel
                                </label>
                                <input type="password" 
                                       name="current_password" 
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-key text-gray-500 mr-1"></i> Nouveau mot de passe
                                </label>
                                <input type="password" 
                                       name="new_password" 
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-check-circle text-gray-500 mr-1"></i> Confirmer le mot de passe
                                </label>
                                <input type="password" 
                                       name="new_password_confirmation" 
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" 
                                    class="w-full px-4 py-2.5 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md">
                                <i class="fas fa-sync-alt mr-2"></i>
                                Mettre à jour le mot de passe
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-4 p-3 bg-blue-50 rounded-xl">
                        <p class="text-xs text-blue-700 text-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Utilisez un mot de passe fort avec au moins 8 caractères
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Data Management Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-database text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">Données personnelles</h2>
                            <p class="text-xs text-gray-500">RGPD - Contrôlez vos données</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('client.profil.export') }}" 
                           class="flex items-center justify-between w-full p-4 bg-gradient-to-r from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 rounded-xl transition-all duration-200 group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <i class="fas fa-download text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Exporter mes données</p>
                                    <p class="text-xs text-gray-500">Téléchargez toutes vos informations</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-all"></i>
                        </a>
                        
                        <button onclick="openDeleteModal()" 
                                class="flex items-center justify-between w-full p-4 bg-gradient-to-r from-red-50 to-rose-50 hover:from-red-100 hover:to-rose-100 rounded-xl transition-all duration-200 group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <i class="fas fa-trash-alt text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-red-600">Supprimer mon compte</p>
                                    <p class="text-xs text-gray-500">Suppression définitive (RGPD)</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-red-600 group-hover:translate-x-1 transition-all"></i>
                        </button>
                    </div>
                    
                    <div class="mt-5 p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-shield-alt text-green-600 text-sm mt-0.5"></i>
                            <p class="text-xs text-gray-500">
                                Conformément au RGPD, vous avez le droit d'accéder, de modifier et de supprimer vos données personnelles. 
                                La suppression est irréversible et anonymise vos historiques de commandes.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Account Stats Card -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-5 border border-blue-100">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                    <h3 class="font-bold text-gray-800">Statistiques du compte</h3>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Commandes totales</span>
                        <span class="font-semibold text-gray-800">{{ \App\Models\Commande::where('client_id', Auth::id())->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Billets achetés</span>
                        <span class="font-semibold text-gray-800">{{ \App\Models\Billet::whereHas('commande', function($q) { $q->where('client_id', Auth::id()); })->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Dernière connexion</span>
                        <span class="font-semibold text-gray-800">{{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all scale-95 opacity-0" id="deleteModalContent">
        <div class="relative">
            <div class="absolute -top-12 left-1/2 transform -translate-x-1/2">
                <div class="w-20 h-20 bg-gradient-to-r from-red-500 to-rose-600 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-exclamation-triangle text-white text-3xl"></i>
                </div>
            </div>
            <div class="pt-12 p-6">
                <div class="text-center">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Supprimer mon compte</h3>
                    <p class="text-gray-600 mb-4">
                        Cette action est <strong class="text-red-600">irréversible</strong>. Toutes vos données personnelles seront supprimées définitivement.
                    </p>
                    <div class="bg-amber-50 border-l-4 border-amber-500 p-3 rounded-lg mb-4">
                        <p class="text-xs text-amber-700 text-left">
                            <i class="fas fa-info-circle mr-1"></i>
                            Les historiques de commandes seront anonymisés conformément à la législation RGPD.
                        </p>
                    </div>
                    
                    <form action="{{ route('client.profil.destroy') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2 text-left">
                                Confirmez avec votre mot de passe
                            </label>
                            <input type="password" 
                                   name="password" 
                                   required
                                   placeholder="Votre mot de passe"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200">
                        </div>
                        
                        <div class="flex gap-3">
                            <button type="button" 
                                    onclick="closeDeleteModal()" 
                                    class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all duration-200">
                                Annuler
                            </button>
                            <button type="submit" 
                                    class="flex-1 px-4 py-2.5 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md">
                                Confirmer la suppression
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let deleteModal = document.getElementById('deleteModal');
    let deleteModalContent = document.getElementById('deleteModalContent');
    
    function openDeleteModal() {
        deleteModal.classList.remove('hidden');
        deleteModal.classList.add('flex');
        setTimeout(() => {
            deleteModalContent.classList.remove('scale-95', 'opacity-0');
            deleteModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
    
    function closeDeleteModal() {
        deleteModalContent.classList.remove('scale-100', 'opacity-100');
        deleteModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            deleteModal.classList.remove('flex');
            deleteModal.classList.add('hidden');
        }, 300);
    }
    
    // Close modal on outside click
    window.onclick = function(event) {
        if (event.target == deleteModal) {
            closeDeleteModal();
        }
    }
</script>
@endsection