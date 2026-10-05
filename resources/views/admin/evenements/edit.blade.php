{{-- resources/views/admin/evenements/edit.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Modifier - ' . $evenement->titre)
@section('page-title', 'Modifier l\'événement')
@section('page-description', $evenement->titre)

@section('page-actions')
    <a href="{{ route('admin.evenements.show', $evenement) }}" 
       class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border-2 border-gray-200 hover:border-blue-300 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 text-gray-700 text-sm font-semibold rounded-xl transition-all duration-200 shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        </svg>
        Voir l'événement
    </a>
    <a href="{{ route('admin.evenements.index') }}" 
       class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border-2 border-gray-200 hover:border-blue-300 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 text-gray-700 text-sm font-semibold rounded-xl transition-all duration-200 shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Retour
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-amber-600 to-orange-600 rounded-2xl shadow-lg overflow-hidden">
        <div class="relative px-6 py-8">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mt-32 -mr-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -mb-24 -ml-24"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">Modifier l'événement</h2>
                        <p class="text-amber-100 text-sm mt-1">{{ $evenement->titre }}</p>
                    </div>
                </div>
                <div class="hidden md:block text-right">
                    <div class="bg-white/10 rounded-xl px-4 py-2">
                        <p class="text-white text-sm font-medium">Formulaire de modification</p>
                        <p class="text-amber-200 text-xs">Modifiez les informations de l'événement</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.evenements.update', $evenement) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Column - Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Informations générales</h3>
                        </div>
                    </div>
                    <div class="p-6 space-y-5">
                        <!-- Titre -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Titre de l'événement <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="titre" value="{{ old('titre', $evenement->titre) }}" required
                                       class="pl-10 w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                       placeholder="Titre de l'événement">
                            </div>
                            @error('titre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="4" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"
                                      placeholder="Décrivez l'événement...">{{ old('description', $evenement->description) }}</textarea>
                            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- Type et Statut -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Type d'événement</label>
                                <div class="relative group">
                                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"></path>
                                        </svg>
                                    </div>
                                    <select name="type" class="pl-10 w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none bg-white cursor-pointer">
                                        <option value="">Sélectionner un type</option>
                                        <option value="Concert" {{ old('type', $evenement->type) == 'Concert' ? 'selected' : '' }}>🎵 Concert</option>
                                        <option value="Théâtre" {{ old('type', $evenement->type) == 'Théâtre' ? 'selected' : '' }}>🎭 Théâtre</option>
                                        <option value="Sport" {{ old('type', $evenement->type) == 'Sport' ? 'selected' : '' }}>⚽ Sport</option>
                                        <option value="Festival" {{ old('type', $evenement->type) == 'Festival' ? 'selected' : '' }}>🎪 Festival</option>
                                        <option value="Conférence" {{ old('type', $evenement->type) == 'Conférence' ? 'selected' : '' }}>🎤 Conférence</option>
                                        <option value="Exposition" {{ old('type', $evenement->type) == 'Exposition' ? 'selected' : '' }}>🖼️ Exposition</option>
                                        <option value="Autre" {{ old('type', $evenement->type) == 'Autre' ? 'selected' : '' }}>📌 Autre</option>
                                    </select>
                                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Statut</label>
                                <div class="relative group">
                                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <select name="statut" class="pl-10 w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none bg-white cursor-pointer">
                                        <option value="Brouillon" {{ old('statut', $evenement->statut) == 'Brouillon' ? 'selected' : '' }}>📝 Brouillon</option>
                                        <option value="Publié" {{ old('statut', $evenement->statut) == 'Publié' ? 'selected' : '' }}>✅ Publié</option>
                                        <option value="Terminé" {{ old('statut', $evenement->statut) == 'Terminé' ? 'selected' : '' }}>🏁 Terminé</option>
                                        <option value="Annulé" {{ old('statut', $evenement->statut) == 'Annulé' ? 'selected' : '' }}>❌ Annulé</option>
                                        <option value="Reporté" {{ old('statut', $evenement->statut) == 'Reporté' ? 'selected' : '' }}>⏰ Reporté</option>
                                    </select>
                                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Date & Time Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Date et heure</h3>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Date de l'événement <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" name="date" value="{{ old('date', $evenement->date ? $evenement->date->format('Y-m-d\TH:i') : '') }}" required
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            
                           
                        </div>
                        
                    </div>
                </div>
                
                <!-- Location Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Lieu</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="relative group">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <select name="lieu_id" id="lieu_id" class="pl-10 w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none bg-white cursor-pointer">
                                <option value="" data-capacite="0">-- Choisir un lieu --</option>
                                @foreach($lieux as $lieu)
                                    <option value="{{ $lieu->id }}" data-capacite="{{ $lieu->capacite }}" {{ old('lieu_id', $evenement->lieu_id) == $lieu->id ? 'selected' : '' }}>
                                        {{ $lieu->nom }} - {{ $lieu->ville }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        @error('lieu_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                <!-- Images & Media Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Images et médias</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Poster Upload -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Affiche de l'événement</label>
                                @if($evenement->affiche)
                                    <div class="mb-3">
                                        <img src="{{ Storage::url($evenement->affiche) }}" class="h-32 w-auto object-cover rounded-xl shadow-sm">
                                    </div>
                                @endif
                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:border-blue-500 hover:bg-blue-50 transition-all duration-200 cursor-pointer group"
                                     onclick="document.getElementById('affiche').click()">
                                    <div class="w-12 h-12 mx-auto bg-gray-100 rounded-xl flex items-center justify-center mb-2 group-hover:bg-blue-100 transition-all">
                                        <svg class="w-6 h-6 text-gray-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-600">Cliquez pour modifier l'image</p>
                                    <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF, WEBP. Max 2MB</p>
                                    <input type="file" name="affiche" id="affiche" accept="image/*" class="hidden">
                                </div>
                                @error('affiche') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            
                            <!-- Banner Upload -->
                            
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Capacity & Tickets -->
            <div class="space-y-6">
                <!-- Capacity Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Capacité</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Capacité totale <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <input type="number" name="capacite" id="capacite" value="{{ old('capacite', $evenement->capacite) }}" required
                                   class="pl-10 w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                   placeholder="Nombre de places totales">
                        </div>
                        @error('capacite') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        
                        <div class="mt-4 grid grid-cols-2 gap-3 pt-4 border-t border-gray-200">
                            <div class="bg-green-50 rounded-xl p-3 text-center">
                                <p class="text-xs text-gray-500">Billets vendus</p>
                                <p class="text-xl font-bold text-green-600">{{ $evenement->billets_count ?? 0 }}</p>
                            </div>
                            <div class="bg-blue-50 rounded-xl p-3 text-center">
                                <p class="text-xs text-gray-500">Places restantes</p>
                                <p class="text-xl font-bold text-blue-600">{{ ($evenement->capacite ?? 0) - ($evenement->billets_count ?? 0) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Ticket Types Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Types de billets</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div id="billetsContainer" class="space-y-4">
                            @foreach($typesBillets as $index => $type)
                            <div class="billet-type-item bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-all duration-200">
                                <div class="flex justify-between items-center mb-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                            {{ strtoupper(substr($type->nom, 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-gray-800">{{ $type->nom }}</span>
                                    </div>
                                    <input type="hidden" name="types_billets[{{ $index }}][id]" value="{{ $type->id }}">
                                    <button type="button" onclick="removeBilletType(this)" class="text-red-500 hover:text-red-700 text-sm">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Supprimer
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Nom</label>
                                        <select name="types_billets[{{ $index }}][nom]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm bg-white">
                                            @if(isset($ticketTypes) && $ticketTypes->count() > 0)
                                                @foreach($ticketTypes as $ticketType)
                                                    <option value="{{ $ticketType->name }}" {{ $type->nom == $ticketType->name ? 'selected' : '' }}>{{ $ticketType->label }}</option>
                                                @endforeach
                                            @else
                                                <option value="Standard" {{ $type->nom == 'Standard' ? 'selected' : '' }}>Standard</option>
                                                <option value="VIP" {{ $type->nom == 'VIP' ? 'selected' : '' }}>VIP</option>
                                                <option value="Premium" {{ $type->nom == 'Premium' ? 'selected' : '' }}>Premium</option>
                                                <option value="Réduit" {{ $type->nom == 'Réduit' ? 'selected' : '' }}>Réduit</option>
                                                <option value="Early Bird" {{ $type->nom == 'Early Bird' ? 'selected' : '' }}>Early Bird</option>
                                                <option value="Gratuit" {{ $type->nom == 'Gratuit' ? 'selected' : '' }}>Gratuit</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Prix (DH)</label>
                                        <input type="number" step="0.01" name="types_billets[{{ $index }}][prix]" value="{{ $type->prix }}"
                                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Quantité totale</label>
                                        <input type="number" name="types_billets[{{ $index }}][quantite]" value="{{ $type->quantite }}"
                                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Déjà vendus</label>
                                        <div class="bg-gray-100 rounded-lg px-3 py-2 text-sm font-semibold text-green-600 text-center">
                                            {{ $type->quantite_vendue ?? 0 }}
                                        </div>
                                        <input type="hidden" name="types_billets[{{ $index }}][quantite_vendue]" value="{{ $type->quantite_vendue ?? 0 }}">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <button type="button" onclick="addBilletType()" 
                                class="mt-4 w-full py-3 border-2 border-dashed border-gray-300 rounded-xl text-gray-600 hover:border-blue-500 hover:text-blue-600 transition-all duration-200 font-medium flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Ajouter un type de billet
                        </button>
                        <p class="text-xs text-gray-500 mt-3 text-center">Au moins un type de billet est requis</p>
                    </div>
                </div>
                
                <!-- Terms & Conditions Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Conditions</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Conditions générales</label>
                        <textarea name="conditions" rows="4" 
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"
                                  placeholder="Conditions de vente, politique d'annulation...">{{ old('conditions', $evenement->conditions) }}</textarea>
                    </div>
                </div>
                
                <!-- Organizer Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-cyan-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Organisateur</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Sélectionner un organisateur <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <select name="user_id" class="pl-10 w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none bg-white cursor-pointer">
                                <option value="">-- Choisir un organisateur --</option>
                                @foreach($organisateurs as $org)
                                    <option value="{{ $org->id }}" {{ old('user_id', $evenement->user_id) == $org->id ? 'selected' : '' }}>
                                        {{ $org->prenom }} {{ $org->nom }} - {{ $org->email }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        @error('user_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex justify-end gap-3 bg-white p-5 rounded-2xl shadow-lg border border-gray-100 sticky bottom-4">
            <a href="{{ route('admin.evenements.show', $evenement) }}" 
               class="px-6 py-2.5 border-2 border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-all duration-200 font-semibold">
                Annuler
            </a>
            <button type="submit" 
                    class="px-8 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>

@push('scripts')
@php
    $defaultTicketTypes = [
        ['name' => 'Standard', 'label' => 'Standard'],
        ['name' => 'VIP', 'label' => 'VIP'],
        ['name' => 'Premium', 'label' => 'Premium'],
        ['name' => 'Réduit', 'label' => 'Réduit'],
        ['name' => 'Early Bird', 'label' => 'Early Bird'],
        ['name' => 'Gratuit', 'label' => 'Gratuit']
    ];
@endphp
<script>
const ticketTypeOptions = {!! json_encode($ticketTypes ?? $defaultTicketTypes) !!};
</script>
<script>
let billetIndex = {{ count($typesBillets) }};

function addBilletType() {
    const container = document.getElementById('billetsContainer');
    const letters = ['V', 'P', 'G', 'E', 'D', 'C'];
    const letter = letters[billetIndex % letters.length];
    const colors = ['purple', 'pink', 'orange', 'teal', 'cyan', 'rose'];
    const color = colors[billetIndex % colors.length];
    
    const newBillet = document.createElement('div');
    newBillet.className = 'billet-type-item bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-all duration-200';
    newBillet.innerHTML = `
        <div class="flex justify-between items-center mb-3">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-gradient-to-br from-${color}-500 to-${color}-600 rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-sm">
                    ${letter}
                </div>
                <span class="font-semibold text-gray-800">Nouveau type</span>
            </div>
            <button type="button" onclick="removeBilletType(this)" class="text-red-500 hover:text-red-700 text-sm">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Supprimer
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nom</label>
                <select name="types_billets[${billetIndex}][nom]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm bg-white">
                    ${ticketTypeOptions.map(type => `<option value="${type.name}">${type.label}</option>`).join('')}
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Prix (DH)</label>
                <input type="number" step="0.01" name="types_billets[${billetIndex}][prix]" value="0"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Quantité</label>
                <input type="number" name="types_billets[${billetIndex}][quantite]" value="0"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Déjà vendus</label>
                <div class="bg-gray-100 rounded-lg px-3 py-2 text-sm font-semibold text-green-600 text-center">0</div>
                <input type="hidden" name="types_billets[${billetIndex}][quantite_vendue]" value="0">
            </div>
        </div>
    `;
    container.appendChild(newBillet);
    attachBilletEvents(newBillet);
    refreshStandardQuantite();
    billetIndex++;
}

function removeBilletType(button) {
    if(confirm('Supprimer ce type de billet ?')) {
        button.closest('.billet-type-item').remove();
        refreshStandardQuantite();
    }
}

function getCapacite() {
    const capaciteInput = document.getElementById('capacite');
    return capaciteInput ? parseInt(capaciteInput.value, 10) || 0 : 0;
}

function refreshStandardQuantite() {
    const capacity = getCapacite();
    const rows = document.querySelectorAll('.billet-type-item');
    let totalNonStandard = 0;
    let standardInput = null;

    rows.forEach(row => {
        const nomSelect = row.querySelector('select[name*="[nom]"]');
        const quantiteInput = row.querySelector('input[name*="[quantite]"]');
        if (!nomSelect || !quantiteInput) return;

        const nom = nomSelect.value;
        const qty = parseInt(quantiteInput.value, 10) || 0;

        if (nom === 'Standard' && !standardInput) {
            standardInput = quantiteInput;
        } else {
            totalNonStandard += qty;
        }
    });

    if (standardInput) {
        const remainder = Math.max(capacity - totalNonStandard, 0);
        standardInput.value = remainder;
        standardInput.readOnly = true;
        standardInput.classList.add('bg-gray-100', 'cursor-not-allowed');
    }
}

function attachBilletEvents(row) {
    const nomSelect = row.querySelector('select[name*="[nom]"]');
    const quantiteInput = row.querySelector('input[name*="[quantite]"]');
    if (nomSelect) {
        nomSelect.addEventListener('change', refreshStandardQuantite);
    }
    if (quantiteInput && nomSelect && nomSelect.value !== 'Standard') {
        quantiteInput.addEventListener('input', refreshStandardQuantite);
    }
}

function initBilletTypeSync() {
    document.querySelectorAll('.billet-type-item').forEach(attachBilletEvents);

    const capaciteInput = document.getElementById('capacite');
    if (capaciteInput) {
        capaciteInput.addEventListener('input', refreshStandardQuantite);
    }

    const lieuSelect = document.getElementById('lieu_id');
    if (lieuSelect) {
        lieuSelect.addEventListener('change', function () {
            const option = lieuSelect.selectedOptions[0];
            const cap = parseInt(option?.dataset?.capacite || 0, 10);
            if (cap && capaciteInput) {
                capaciteInput.value = cap;
                refreshStandardQuantite();
            }
        });
    }

    refreshStandardQuantite();
}

document.addEventListener('DOMContentLoaded', initBilletTypeSync);

// Image preview
document.getElementById('affiche')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const previewDiv = this.parentElement;
            const existingPreview = previewDiv.querySelector('.mb-3 img');
            if (existingPreview) {
                existingPreview.src = event.target.result;
            } else {
                const imgContainer = document.createElement('div');
                imgContainer.className = 'mb-3';
                imgContainer.innerHTML = `<img src="${event.target.result}" class="h-32 w-auto object-cover rounded-xl shadow-sm">`;
                previewDiv.insertBefore(imgContainer, previewDiv.firstChild);
            }
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById('banniere')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const previewDiv = this.parentElement;
            const existingPreview = previewDiv.querySelector('.mb-3 img');
            if (existingPreview) {
                existingPreview.src = event.target.result;
            } else {
                const imgContainer = document.createElement('div');
                imgContainer.className = 'mb-3';
                imgContainer.innerHTML = `<img src="${event.target.result}" class="h-24 w-auto object-cover rounded-xl shadow-sm">`;
                previewDiv.insertBefore(imgContainer, previewDiv.firstChild);
            }
        };
        reader.readAsDataURL(file);
    }
});

// Date synchronization logic
document.getElementById('date')?.addEventListener('change', function(e) {
    const eventDate = e.target.value;
    const venteDebut = document.getElementById('date_vente_debut');
    const venteFin = document.getElementById('date_vente_fin');
    
    // If sale start date is empty, set it to event date
    if (venteDebut && !venteDebut.value) {
        venteDebut.value = eventDate;
    }
    
    // If sale end date is empty, set it to event date
    if (venteFin && !venteFin.value) {
        venteFin.value = eventDate;
    }
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    // Ensure all quantity fields have valid integer values
    const quantityInputs = document.querySelectorAll('input[name*="[quantite]"]');
    quantityInputs.forEach(input => {
        if (!input.value || isNaN(parseInt(input.value))) {
            input.value = '0';
        }
    });
    
    // Ensure all price fields have valid numeric values
    const priceInputs = document.querySelectorAll('input[name*="[prix]"]');
    priceInputs.forEach(input => {
        if (!input.value || isNaN(parseFloat(input.value))) {
            input.value = '0';
        }
    });
    
    return true;
});
</script>
@endpush
@endsection