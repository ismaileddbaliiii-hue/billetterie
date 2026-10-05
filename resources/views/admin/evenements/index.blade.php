{{-- resources/views/admin/evenements/index.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Gestion des événements')
@section('page-title', 'Événements')
@section('page-description', 'Gérez tous les événements de la plateforme')

@section('page-actions')
    <a href="{{ route('admin.evenements.create') }}" id="adminAddEventBtn" role="button" class="inline-flex items-center min-w-[220px] px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm cursor-pointer" aria-label="Ajouter un événement">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Ajouter un événement
    </a>
    <button onclick="exportEvents()" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
        </svg>
        Exporter
    </button>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Statistiques globales améliorées -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total événements -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total événements</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Tous statuts confondus</p>
                </div>
                <div class="h-12 w-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Publiés -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Événements publiés</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['publies'] ?? 0 }}</p>
                    <p class="text-xs text-green-600 mt-1">{{ $stats['publies_percent'] ?? 0 }}% du total</p>
                </div>
                <div class="h-12 w-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Billets vendus -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Billets vendus</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ number_format($stats['billets_vendus'] ?? 0, 0, ',', ' ') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Taux d'occupation moyen</p>
                </div>
                <div class="h-12 w-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Chiffre d'affaires -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Chiffre d'affaires</p>
                    <p class="text-3xl font-bold text-amber-600 mt-2">{{ isset($stats['ca_total']) ? number_format($stats['ca_total'], 0, ',', ' ') : '0' }} DH</p>
                    <p class="text-xs text-gray-500 mt-1">Revenu total généré</p>
                </div>
                <div class="h-12 w-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres avancés -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <h3 class="text-sm font-semibold text-gray-900">Filtres de recherche</h3>
            </div>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('admin.evenements.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Recherche</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Titre, description, lieu..." 
                                   class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Statut</label>
                        <select name="statut" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <option value="">Tous les statuts</option>
                            <option value="Publié" {{ request('statut') == 'Publié' ? 'selected' : '' }}>Publié</option>
                            <option value="Brouillon" {{ request('statut') == 'Brouillon' ? 'selected' : '' }}>Brouillon</option>
                            <option value="Terminé" {{ request('statut') == 'Terminé' ? 'selected' : '' }}>Terminé</option>
                            <option value="Annulé" {{ request('statut') == 'Annulé' ? 'selected' : '' }}>Annulé</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Organisateur</label>
                        <select name="organisateur" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <option value="">Tous les organisateurs</option>
                            @foreach($organisateurs ?? [] as $org)
                                <option value="{{ $org->id }}" {{ request('organisateur') == $org->id ? 'selected' : '' }}>
                                    {{ $org->prenom }} {{ $org->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Période</label>
                        <div class="flex space-x-2">
                            <input type="date" name="date_debut" value="{{ request('date_debut') }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   placeholder="Début">
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-end space-x-3 pt-2">
                    @if(request()->hasAny(['search', 'statut', 'organisateur', 'date_debut', 'date_fin']))
                        <a href="{{ route('admin.evenements.index') }}" 
                           class="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Réinitialiser
                        </a>
                    @endif
                    <button type="submit" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Appliquer les filtres
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des événements amélioré -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Événement</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Organisateur</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date & Lieu</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Billets</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Revenu</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($evenements as $event)
                    <tr class="hover:bg-gray-50 transition-colors duration-150 group" data-event-id="{{ $event->id }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    @if($event->affiche)
                                        <img src="{{ Storage::url($event->affiche) }}" class="h-12 w-12 rounded-lg object-cover shadow-sm">
                                    @else
                                        <div class="h-12 w-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $event->titre }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">ID : {{ $event->id }}</div>
                                    @if($event->created_at->diffInDays(now()) <= 7)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Nouveau
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                        {{ strtoupper(substr($event->organisateur->prenom, 0, 1)) }}{{ strtoupper(substr($event->organisateur->nom, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $event->organisateur->prenom }} {{ $event->organisateur->nom }}</div>
                                    <div class="text-xs text-gray-500">{{ $event->organisateur->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $event->date->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $event->date->format('H:i') }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $event->lieu->nom ?? 'Lieu non défini' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-semibold text-gray-900">{{ $event->billets_count ?? 0 }}</span>
                                <span class="text-xs text-gray-500">/ {{ $event->capacite }}</span>
                            </div>
                            @php $pourcentage = ($event->capacite > 0) ? (($event->billets_count ?? 0) / $event->capacite) * 100 : 0; @endphp
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                                <div class="bg-gradient-to-r from-green-500 to-green-600 h-1.5 rounded-full transition-all duration-500" style="width: {{ $pourcentage }}%"></div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">{{ number_format($pourcentage, 1) }}% rempli</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900">{{ number_format($event->billets_sum_prix ?? 0, 0, ',', ' ') }} DH</div>
                            @if(($event->billets_count ?? 0) > 0)
                                <div class="text-xs text-gray-500">Moy. {{ number_format(($event->billets_sum_prix ?? 0) / ($event->billets_count ?? 1), 0, ',', ' ') }} DH</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusConfig = [
                                    'Publié' => ['color' => 'green', 'icon' => 'check-circle'],
                                    'Brouillon' => ['color' => 'yellow', 'icon' => 'edit'],
                                    'Terminé' => ['color' => 'blue', 'icon' => 'flag-checkered'],
                                    'Annulé' => ['color' => 'red', 'icon' => 'times-circle'],
                                    'Reporté' => ['color' => 'orange', 'icon' => 'clock']
                                ];
                                $config = $statusConfig[$event->statut] ?? ['color' => 'gray', 'icon' => 'info-circle'];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $event->statut }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.evenements.show', $event) }}" 
                                   class="p-1.5 text-gray-400 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition-all duration-200" 
                                   title="Voir les détails">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.evenements.edit', $event) }}" 
                                   class="p-1.5 text-gray-400 hover:text-green-600 rounded-lg hover:bg-green-50 transition-all duration-200" 
                                   title="Modifier">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.evenements.billets', $event) }}" 
                                   class="p-1.5 text-gray-400 hover:text-purple-600 rounded-lg hover:bg-purple-50 transition-all duration-200" 
                                   title="Gérer les billets">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"></path>
                                    </svg>
                                </a>
                                <button onclick="changeStatus({{ $event->id }})" 
                                        class="p-1.5 text-gray-400 hover:text-yellow-600 rounded-lg hover:bg-yellow-50 transition-all duration-200" 
                                        title="Changer le statut">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                </button>
                                <button onclick="duplicateEvent({{ $event->id }})" 
                                        class="p-1.5 text-gray-400 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 transition-all duration-200" 
                                        title="Dupliquer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                                <form action="{{ route('admin.evenements.destroy', $event) }}" method="POST" class="inline" id="delete-form-{{ $event->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete({{ $event->id }}, '{{ addslashes($event->titre) }}')" 
                                            class="p-1.5 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-all duration-200" 
                                            title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">Aucun événement trouvé</h3>
                                <p class="text-gray-500 mb-4">Commencez par créer votre premier événement</p>
                                <a href="{{ route('admin.evenements.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Créer un événement
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination améliorée -->
        @if(method_exists($evenements, 'links') && $evenements->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $evenements->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Changement de Statut amélioré -->
<div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all scale-95 opacity-0" id="statusModalContent">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="h-10 w-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 ml-3">Changer le statut</h3>
                </div>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nouveau statut</label>
                    <select name="statut" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200">
                        <option value="Publié">📢 Publié</option>
                        <option value="Brouillon">📝 Brouillon</option>
                        <option value="Terminé">🏁 Terminé</option>
                        <option value="Annulé">❌ Annulé</option>
                    </select>
                    <div class="warning-container mt-2 text-xs text-amber-600"></div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white rounded-lg transition-all duration-200 font-medium shadow-sm">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Duplication amélioré -->
<div id="duplicateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all scale-95 opacity-0" id="duplicateModalContent">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 ml-3">Dupliquer l'événement</h3>
                </div>
                <button onclick="closeDuplicateModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="duplicateForm" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nouveau titre</label>
                    <input type="text" name="titre" id="duplicateTitre" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                           placeholder="Titre de l'événement">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeDuplicateModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-lg transition-all duration-200 font-medium shadow-sm">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        Dupliquer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all scale-95 opacity-0" id="deleteModalContent">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="h-10 w-10 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 ml-3">Confirmer la suppression</h3>
                </div>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <p class="text-gray-600 mb-6">
                Êtes-vous sûr de vouloir supprimer l'événement <span id="delete-event-name" class="font-semibold text-gray-900"></span> ?
                Cette action est irréversible et supprimera également tous les billets associés.
            </p>
            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteModal()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                    Annuler
                </button>
                <form id="delete-form" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors font-medium">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentEventId = null;
let deleteModal = document.getElementById('deleteModal');
let deleteModalContent = document.getElementById('deleteModalContent');
let deleteForm = document.getElementById('delete-form');
let deleteEventName = document.getElementById('delete-event-name');

function changeStatus(eventId) {
    currentEventId = eventId;
    const modal = document.getElementById('statusModal');
    const modalContent = document.getElementById('statusModalContent');
    const form = document.getElementById('statusForm');
    const statusSelect = form.querySelector('select[name="statut"]');
    const warningText = form.querySelector('.warning-text') || document.createElement('p');

    form.action = `/admin/evenements/${eventId}/status`;

    // Récupérer les informations de l'événement depuis la ligne du tableau
    const eventRow = document.querySelector(`tr[data-event-id="${eventId}"]`);
    let currentStatus = 'Brouillon';
    let hasTickets = false;
    let isPast = false;
    const warningContainer = form.querySelector('.warning-container');

    if (eventRow) {
        const statusCell = eventRow.querySelector('td:nth-child(6) span');
        currentStatus = statusCell ? statusCell.textContent.trim() : 'Brouillon';
        const ticketsCell = eventRow.querySelector('td:nth-child(4) .text-sm.font-semibold');
        hasTickets = ticketsCell && ticketsCell.textContent.trim() !== '0';
        const dateCell = eventRow.querySelector('td:nth-child(3) .text-sm.text-gray-900');
        const eventDateText = dateCell ? dateCell.textContent.trim() : '';
        if (eventDateText) {
            const parts = eventDateText.split('/');
            if (parts.length === 3) {
                const eventDate = new Date(`${parts[2]}-${parts[1]}-${parts[0]}`);
                isPast = eventDate < new Date();
            }
        }
    }

    // Réinitialiser les options avant d'appliquer les règles
    Array.from(statusSelect.options).forEach(option => {
        option.disabled = false;
        option.selected = false;
    });
    statusSelect.value = currentStatus;

    if (currentStatus === 'Terminé') {
        statusSelect.querySelector('option[value="Publié"]').disabled = true;
    }

    if (hasTickets) {
        statusSelect.querySelector('option[value="Annulé"]').disabled = true;
        warningContainer.textContent = '⚠️ Cet événement a des billets vendus - l’annulation n’est pas possible.';
    } else {
        warningContainer.textContent = '';
    }

    if (!isPast) {
        statusSelect.querySelector('option[value="Terminé"]').disabled = true;
    }

    if (statusSelect.querySelector(`option[value="${currentStatus}"]`)) {
        statusSelect.querySelector(`option[value="${currentStatus}"]`).selected = true;
    }

    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.add('bg-opacity-50');
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('statusModal');
    const modalContent = document.getElementById('statusModalContent');
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('bg-opacity-50');
    }, 300);
    currentEventId = null;
}

function duplicateEvent(eventId) {
    currentEventId = eventId;
    const modal = document.getElementById('duplicateModal');
    const modalContent = document.getElementById('duplicateModalContent');
    const form = document.getElementById('duplicateForm');
    form.action = `/admin/evenements/${eventId}/duplicate`;
    
    fetch(`/admin/evenements/${eventId}/get-title`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('duplicateTitre').value = data.titre + ' (Copie)';
        })
        .catch(() => {
            document.getElementById('duplicateTitre').value = 'Copie de événement';
        });
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.add('bg-opacity-50');
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeDuplicateModal() {
    const modal = document.getElementById('duplicateModal');
    const modalContent = document.getElementById('duplicateModalContent');
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('bg-opacity-50');
    }, 300);
    currentEventId = null;
}

function confirmDelete(id, titre) {
    deleteForm.action = `/admin/evenements/${id}`;
    deleteEventName.textContent = titre;
    deleteModal.classList.remove('hidden');
    setTimeout(() => {
        deleteModal.classList.add('bg-opacity-50');
        deleteModalContent.classList.remove('scale-95', 'opacity-0');
        deleteModalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeDeleteModal() {
    deleteModalContent.classList.remove('scale-100', 'opacity-100');
    deleteModalContent.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        deleteModal.classList.add('hidden');
        deleteModal.classList.remove('bg-opacity-50');
    }, 300);
}

function exportEvents() {
    window.location.href = '{{ route("admin.evenements.export") }}' + window.location.search;
}

// Sécurité pour le bouton ajouter événement
const adminAddEventBtn = document.getElementById('adminAddEventBtn');
if (adminAddEventBtn) {
    adminAddEventBtn.addEventListener('click', function(e) {
        if (!this.href || this.href === '#') {
            e.preventDefault();
            alert('Impossible d\'accéder au formulaire de création d\'événement. Vérifiez votre connexion ou les routes.');
        }
    });
}

// Fermer les modals en cliquant en dehors
window.onclick = function(event) {
    const statusModal = document.getElementById('statusModal');
    const duplicateModal = document.getElementById('duplicateModal');
    const deleteModalEl = document.getElementById('deleteModal');
    
    if (event.target == statusModal) {
        closeModal();
    }
    if (event.target == duplicateModal) {
        closeDuplicateModal();
    }
    if (event.target == deleteModalEl) {
        closeDeleteModal();
    }
}

// Animation des barres de progression au chargement
document.addEventListener('DOMContentLoaded', function() {
    const progressBars = document.querySelectorAll('.bg-gradient-to-r.from-green-500');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 100);
    });
});
</script>
@endpush
@endsection