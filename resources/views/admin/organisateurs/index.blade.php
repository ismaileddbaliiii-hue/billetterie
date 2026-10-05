{{-- resources/views/admin/organisateurs/index.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Gestion des organisateurs')
@section('page-title', 'Organisateurs')
@section('page-description', 'Gérez tous les organisateurs de la plateforme')

@section('page-actions')
    <a href="{{ route('admin.organisateurs.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Ajouter un organisateur
    </a>
    <button onclick="exportOrganisateurs()" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
        </svg>
        Exporter
    </button>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total organisateurs</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $totalOrganisateurs ?? $organisateurs->total() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Tous statuts confondus</p>
                </div>
                <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Organisateurs actifs</p>
                    <p class="text-2xl font-bold text-green-600 mt-2">{{ $activeOrganisateurs ?? 0 }}</p>
                    <p class="text-xs text-green-600 mt-1">+{{ $activeGrowth ?? 0 }}% ce mois</p>
                </div>
                <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total événements</p>
                    <p class="text-2xl font-bold text-purple-600 mt-2">{{ $totalEvenements ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Organisés par tous</p>
                </div>
                <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Taux d'activité</p>
                    <p class="text-2xl font-bold text-orange-600 mt-2">{{ $activiteRate ?? 0 }}%</p>
                    <p class="text-xs text-gray-500 mt-1">Organisateurs actifs</p>
                </div>
                <div class="h-12 w-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header with Filters -->
        <div class="p-6 border-b border-gray-200 bg-white">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-semibold text-gray-900">Liste des organisateurs</h3>
                    <p class="text-sm text-gray-500 mt-1">Gérez les comptes et les permissions des organisateurs</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" id="search" placeholder="Rechercher..." 
                               class="pl-10 pr-4 py-2 w-full sm:w-64 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow">
                    </div>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <select id="status-filter" class="pl-10 pr-8 py-2 w-full sm:w-40 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                            <option value="all">Tous les statuts</option>
                            <option value="active">Actifs</option>
                            <option value="inactive">Inactifs</option>
                        </select>
                    </div>
                    <button id="reset-filters" class="px-4 py-2 text-gray-600 hover:text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Réinitialiser
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Organisateur</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Activité</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Inscription</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="organisateurs-table-body">
                    @forelse($organisateurs as $org)
                    <tr class="hover:bg-gray-50 transition-colors duration-150 group" data-id="{{ $org->id }}" data-status="{{ $org->active ? 'active' : 'inactive' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($org->profile_image_url ?? false)
                                        <img src="{{ $org->profile_image_url }}" alt="{{ $org->prenom }} {{ $org->nom }}" class="h-10 w-10 rounded-lg object-cover border border-gray-200 shadow-sm" />
                                    @else
                                        <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold shadow-sm">
                                            {{ strtoupper(substr($org->prenom, 0, 1)) }}{{ strtoupper(substr($org->nom, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $org->prenom }} {{ $org->nom }}</div>
                                    @if($org->created_at->diffInDays(now()) <= 7)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
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
                            <div class="text-sm text-gray-900">{{ $org->email }}</div>
                            <div class="text-sm text-gray-500">{{ $org->telephone ?? 'Non renseigné' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900">{{ $org->evenements_count ?? 0 }}</span>
                                <span class="text-sm text-gray-500 ml-1">événements</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($org->active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                    Actif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                                    Inactif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $org->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.organisateurs.show', $org) }}" 
                                   class="p-1.5 text-gray-400 hover:text-green-600 rounded-lg hover:bg-green-50 transition-all duration-200" 
                                   title="Voir">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <button onclick="openPermissionsModal({{ $org->id }})" 
                                        class="p-1.5 text-gray-400 hover:text-purple-600 rounded-lg hover:bg-purple-50 transition-all duration-200" 
                                        title="Gérer les permissions">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6-4h12a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 4v2a2 2 0 01-2 2H8a2 2 0 01-2-2v-2"></path>
                                    </svg>
                                </button>
                                <a href="{{ route('admin.organisateurs.edit', $org) }}" 
                                   class="p-1.5 text-gray-400 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition-all duration-200" 
                                   title="Modifier">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <button onclick="toggleStatus(event, {{ $org->id ?? 'null' }})"
                                        class="p-1.5 {{ $org->active ? 'text-gray-400 hover:text-red-600 hover:bg-red-50' : 'text-gray-400 hover:text-green-600 hover:bg-green-50' }} rounded-lg transition-all duration-200"
                                        title="{{ $org->active ? 'Désactiver cet organisateur' : 'Activer cet organisateur' }}">
                                    @if($org->active)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @endif
                                </button>
                                <button onclick="openDeleteModal({{ $org->id }}, '{{ addslashes($org->prenom . ' ' . $org->nom) }}')" 
                                        class="p-1.5 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-all duration-200" 
                                        title="Supprimer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">Aucun organisateur</h3>
                                <p class="text-gray-500 mb-4">Commencez par ajouter votre premier organisateur</p>
                                <a href="{{ route('admin.organisateurs.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Ajouter un organisateur
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(method_exists($organisateurs, 'links') && $organisateurs->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $organisateurs->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
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
                Êtes-vous sûr de vouloir supprimer <span id="delete-organisateur-name" class="font-semibold text-gray-900"></span> ?
                Cette action est irréversible et supprimera également tous les événements associés.
            </p>
            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                    Annuler
                </button>
                <form id="delete-form" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors font-medium">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Permissions Modal -->
<div id="permissionsModal" class="fixed inset-0 top-0 bg-black bg-opacity-50 flex items-start justify-center z-[9999] hidden overflow-y-auto pt-6 pb-6 sm:pt-10 transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 transform transition-all scale-95 opacity-0 max-h-[calc(100vh-4rem)] overflow-y-auto" id="permissionsModalContent">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6-4h12a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 4v2a2 2 0 01-2 2H8a2 2 0 01-2-2v-2"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 ml-3">Gérer les permissions</h3>
                </div>
                <button onclick="closePermissionsModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="permissions-content">
                <!-- Permissions content will be loaded here via AJAX -->
                <div class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    // Filtres en temps réel
    const searchInput = document.getElementById('search');
    const statusFilter = document.getElementById('status-filter');
    const resetBtn = document.getElementById('reset-filters');
    const tableRows = document.querySelectorAll('#organisateurs-table-body tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const status = statusFilter.value;

        tableRows.forEach(row => {
            if (row.cells.length === 1) return;

            const nameCell = row.cells[0].innerText.toLowerCase();
            const emailCell = row.cells[1].innerText.toLowerCase();
            const rowStatus = row.getAttribute('data-status');

            const matchesSearch = nameCell.includes(searchTerm) || emailCell.includes(searchTerm);
            const matchesStatus = status === 'all' || rowStatus === status;

            row.style.display = matchesSearch && matchesStatus ? '' : 'none';
        });
    }

    searchInput?.addEventListener('input', filterTable);
    statusFilter?.addEventListener('change', filterTable);
    resetBtn?.addEventListener('click', () => {
        if (searchInput) searchInput.value = '';
        if (statusFilter) statusFilter.value = 'all';
        filterTable();
    });

    // Toggle status avec animation
    async function toggleStatus(e, id) {
        console.log('toggleStatus called with id:', id, 'type:', typeof id);

        if (id === 'null' || id === null || id === undefined || id === '') {
            const row = e.currentTarget.closest('tr');
            id = row?.getAttribute('data-id');
            console.log('Got id from data-id:', id);
        }

        if (!id) {
            console.error('No valid id found');
            showNotification('Erreur: ID organisateur manquant', 'error');
            return;
        }

        id = parseInt(id, 10);
        if (isNaN(id) || id <= 0) {
            console.error('Invalid id:', id);
            showNotification('Erreur: ID organisateur invalide', 'error');
            return;
        }

        const button = e.currentTarget;
        const originalHtml = button.innerHTML;
        const spinnerHtml = '<div class="animate-spin rounded-full h-4 w-4 border-2 border-current border-t-transparent"></div>';
        const row = button.closest('tr');
        const isCurrentlyActive = row?.getAttribute('data-status') === 'active';
        const actionText = isCurrentlyActive ? 'désactiver' : 'activer';
        const organisateurName = row?.querySelector('td:first-child .text-sm.font-medium')?.textContent.trim() || 'cet organisateur';

        if (!confirm(`Êtes-vous sûr de vouloir ${actionText} l'organisateur "${organisateurName}" ?`)) {
            return;
        }

        button.innerHTML = spinnerHtml;
        button.disabled = true;

        const toggleUrl = '/admin/organisateurs/' + id + '/toggle-status';
        console.log('Toggle URL:', toggleUrl);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', toggleUrl, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('Content-Type', 'application/json');

        xhr.onload = function() {
            console.log('XHR response status:', xhr.status);
            console.log('XHR response text:', xhr.responseText);

            button.innerHTML = originalHtml;
            button.disabled = false;

            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    const data = JSON.parse(xhr.responseText);
                    console.log('Parsed response:', data);

                    if (data.success) {
                        const row = document.querySelector(`tr[data-id="${id}"]`);
                        if (row) {
                            const statusCell = row.cells[3];
                            const actionsCell = row.cells[5];
                            const isActive = data.active;
                            row.setAttribute('data-status', isActive ? 'active' : 'inactive');

                            statusCell.innerHTML = isActive ?
                                `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                    Actif
                                </span>` :
                                `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                                    Inactif
                                </span>`;

                            const toggleButton = actionsCell.querySelector('button[onclick*="toggleStatus"]');
                            if (toggleButton) {
                                toggleButton.className = `p-1.5 ${isActive ? 'text-gray-400 hover:text-red-600 hover:bg-red-50' : 'text-gray-400 hover:text-green-600 hover:bg-green-50'} rounded-lg transition-all duration-200`;
                                toggleButton.title = isActive ? 'Désactiver cet organisateur' : 'Activer cet organisateur';
                                toggleButton.innerHTML = isActive ?
                                    `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                    </svg>` :
                                    `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>`;
                            }

                            showNotification(`Organisateur ${isActive ? 'activé' : 'désactivé'} avec succès`, 'success');
                        }
                    } else {
                        showNotification('Erreur lors du changement de statut', 'error');
                    }
                } catch (parseError) {
                    console.error('JSON parse error:', parseError);
                    showNotification('Erreur de réponse du serveur', 'error');
                }
            } else {
                showNotification('Erreur lors du changement de statut', 'error');
            }
        };

        xhr.onerror = function() {
            console.error('XHR network error');
            button.innerHTML = originalHtml;
            button.disabled = false;
            showNotification('Erreur réseau', 'error');
        };

        xhr.send();
    }

    // Permissions modal
    let permissionsModal = document.getElementById('permissionsModal');
    let permissionsModalContent = document.getElementById('permissionsModalContent');
    
    async function openPermissionsModal(id) {
        window.scrollTo({ top: 0, behavior: 'smooth' });
        permissionsModal.classList.remove('hidden');
        setTimeout(() => {
            permissionsModal.classList.add('bg-opacity-50');
            permissionsModalContent.classList.remove('scale-95', 'opacity-0');
            permissionsModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
        
        try {
            const response = await fetch(`/admin/organisateurs/${id}/permissions-modal`);
            const html = await response.text();
            document.getElementById('permissions-content').innerHTML = html;
        } catch (error) {
            console.error('Error loading permissions:', error);
            document.getElementById('permissions-content').innerHTML = `
                <div class="text-center py-8 text-red-600">
                    Erreur lors du chargement des permissions
                </div>
            `;
        }
    }
    
    function closePermissionsModal() {
        permissionsModalContent.classList.remove('scale-100', 'opacity-100');
        permissionsModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            permissionsModal.classList.add('hidden');
            document.getElementById('permissions-content').innerHTML = `
                <div class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600"></div>
                </div>
            `;
        }, 300);
    }

    // Delete modal
    let deleteModal = document.getElementById('deleteModal');
    let deleteModalContent = document.getElementById('deleteModalContent');
    let deleteForm = document.getElementById('delete-form');
    let deleteNameSpan = document.getElementById('delete-organisateur-name');

    function openDeleteModal(id, name) {
        deleteForm.action = `/admin/organisateurs/${id}`;
        deleteNameSpan.textContent = name;
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

    // Notification system
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white`;
        notification.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${
                        type === 'success' 
                            ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' 
                            : 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                    }"></path>
                </svg>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
            notification.classList.add('translate-x-0');
        }, 10);
        
        setTimeout(() => {
            notification.classList.remove('translate-x-0');
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Export function
    function exportOrganisateurs() {
        window.location.href = '{{ route("admin.organisateurs.export") }}';
    }

    // Close modals on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
            closePermissionsModal();
        }
    });

    // Exposer الوظائف globalement lorsque تم استخدام onclick inline
    window.openPermissionsModal = openPermissionsModal;
    window.closePermissionsModal = closePermissionsModal;
    window.openDeleteModal = openDeleteModal;
    window.closeDeleteModal = closeDeleteModal;
    window.exportOrganisateurs = exportOrganisateurs;
    window.toggleStatus = toggleStatus;

})();
</script>
@endpush
@endsection