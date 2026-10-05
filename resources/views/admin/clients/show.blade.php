{{-- resources/views/admin/clients/show.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Détails client - ' . $user->prenom . ' ' . $user->nom)
@section('page-title', 'Détails du client')
@section('page-description', $user->prenom . ' ' . $user->nom)

@section('page-actions')
    <div class="flex space-x-3">
        @if(($user->billets_count ?? 0) > 0)
        <a href="{{ route('admin.clients.download-billets', $user->id) }}" 
           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            Télécharger les billets
        </a>
        @endif
        <a href="{{ route('admin.clients.edit', $user->id) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Modifier
        </a>
        <a href="{{ route('admin.clients.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Retour
        </a>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- En-tête avec avatar -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="relative">
            <div class="h-32 bg-gradient-to-r from-blue-500 to-purple-600"></div>
            <div class="absolute -bottom-12 left-6">
                <div class="h-24 w-24 rounded-xl overflow-hidden shadow-lg border-4 border-white bg-white">
                    @if($user->profile_image && Storage::disk('public')->exists($user->profile_image))
                        <img src="{{ Storage::url($user->profile_image) }}" alt="{{ $user->prenom }} {{ $user->nom }}" class="w-full h-full object-cover">
                    @else
                        <div class="h-full w-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-3xl">
                            {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="pt-14 pb-6 px-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->prenom }} {{ $user->nom }}</h2>
                    <div class="flex items-center mt-2 space-x-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $user->active ? 'bg-green-500' : 'bg-red-500' }} mr-1.5"></span>
                            {{ $user->active ? 'Actif' : 'Suspendu' }}
                        </span>
                        <span class="text-sm text-gray-500">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            {{ $user->email }}
                        </span>
                        @if($user->telephone)
                            <span class="text-sm text-gray-500">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                {{ $user->telephone }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">ID Client</p>
                    <p class="text-lg font-mono font-semibold text-gray-900">#{{ $user->id }}</p>
                    <p class="text-xs text-gray-500 mt-1">Inscrit le {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques détaillées -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Commandes</p>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $user->commandes_count ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Commandes passées</p>
                </div>
                <div class="h-12 w-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Billets achetés</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $user->billets_count ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Tickets achetés</p>
                </div>
                <div class="h-12 w-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total dépensé</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ number_format($user->total_depense ?? 0, 2, ',', ' ') }} DH</p>
                    <p class="text-xs text-gray-500 mt-1">Montant total</p>
                </div>
                <div class="h-12 w-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Dernière activité</p>
                    <p class="text-xl font-bold text-gray-900 mt-2">{{ $user->last_activity ? \Carbon\Carbon::parse($user->last_activity)->diffForHumans() : 'Jamais' }}</p>
                    <p class="text-xs text-gray-500 mt-1">Dernière connexion</p>
                </div>
                <div class="h-12 w-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations détaillées -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informations personnelles -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900">Informations personnelles</h3>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                    <span class="text-gray-600">Nom complet</span>
                    <span class="font-medium text-gray-900">{{ $user->prenom }} {{ $user->nom }}</span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                    <span class="text-gray-600">Adresse email</span>
                    <span class="font-medium text-gray-900">{{ $user->email }}</span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                    <span class="text-gray-600">Téléphone</span>
                    <span class="font-medium text-gray-900">{{ $user->telephone ?? 'Non renseigné' }}</span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                    <span class="text-gray-600">Date d'inscription</span>
                    <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y à H:i') }}</span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                    <span class="text-gray-600">Dernière connexion</span>
                    <span class="font-medium text-gray-900">{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y à H:i') : 'Jamais' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Statut du compte</span>
                    @if($user->active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                            Actif
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                            Suspendu
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistiques d'achat -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900">Statistiques d'achat</h3>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                    <span class="text-gray-600">Nombre total de commandes</span>
                    <span class="text-2xl font-bold text-blue-600">{{ $user->commandes_count ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                    <span class="text-gray-600">Billets achetés</span>
                    <span class="text-2xl font-bold text-green-600">{{ $user->billets_count ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                    <span class="text-gray-600">Panier moyen</span>
                    <span class="text-xl font-bold text-purple-600">{{ number_format(($user->total_depense ?? 0) / max(($user->commandes_count ?? 1), 1), 2, ',', ' ') }} DH</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total dépensé</span>
                    <span class="text-2xl font-bold text-amber-600">{{ number_format($user->total_depense ?? 0, 2, ',', ' ') }} DH</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Dernières commandes -->
    @if(isset($user->commandes) && $user->commandes->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900">Dernières commandes</h3>
                </div>
                @if($user->commandes->count() > 5)
                    <a href="{{ route('admin.clients.commandes', $user->id) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Voir toutes les commandes
                        <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Référence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Événement</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($user->commandes->take(5) as $commande)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm font-medium text-gray-900">{{ $commande->reference ?? '#' . $commande->id }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($commande->created_at)->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($commande->evenement)
                                    <div class="flex items-center">
                                        @if($commande->evenement->affiche)
                                            <img src="{{ Storage::url($commande->evenement->affiche) }}" class="w-8 h-8 rounded-lg object-cover mr-2">
                                        @endif
                                        <span class="text-sm text-gray-900">{{ $commande->evenement->titre }}</span>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-900">{{ number_format($commande->montant_total, 2, ',', ' ') }} DH</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusConfig = [
                                        'Confirmée' => ['color' => 'green', 'icon' => 'check-circle'],
                                        'En attente' => ['color' => 'yellow', 'icon' => 'clock'],
                                        'Annulée' => ['color' => 'red', 'icon' => 'times-circle'],
                                        'Remboursée' => ['color' => 'gray', 'icon' => 'undo']
                                    ];
                                    $config = $statusConfig[$commande->statut] ?? ['color' => 'gray', 'icon' => 'info-circle'];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $commande->statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.commandes.show', $commande->id) }}" 
                                   class="p-1.5 text-gray-400 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition-all duration-200" 
                                   title="Voir les détails">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Actions supplémentaires -->
    <div class="flex justify-end space-x-3">
        <button onclick="toggleStatus({{ $user->id }}, '{{ $user->active ? 'suspend' : 'activate' }}')" 
                class="inline-flex items-center px-4 py-2 {{ $user->active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg transition-all duration-200 font-medium shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $user->active ? 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' }}"></path>
            </svg>
            {{ $user->active ? 'Suspendre le compte' : 'Activer le compte' }}
        </button>
        
        <form action="{{ route('admin.clients.destroy', $user->id) }}" method="POST" class="inline" id="delete-form-{{ $user->id }}">
            @csrf
            @method('DELETE')
            <button type="button" onclick="confirmDelete({{ $user->id }}, '{{ addslashes($user->prenom . ' ' . $user->nom) }}')" 
                    class="inline-flex items-center px-4 py-2 bg-white border border-red-300 hover:bg-red-50 text-red-600 rounded-lg transition-all duration-200 font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Supprimer le compte
            </button>
        </form>
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
                Êtes-vous sûr de vouloir supprimer le client <span id="delete-client-name" class="font-semibold text-gray-900"></span> ?
                Cette action est irréversible et supprimera toutes ses commandes et billets associés.
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
let deleteModal = document.getElementById('deleteModal');
let deleteModalContent = document.getElementById('deleteModalContent');
let deleteForm = document.getElementById('delete-form');
let deleteClientName = document.getElementById('delete-client-name');

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
        }).then(response => {
            if (!response.ok) throw new Error('Statut HTTP ' + response.status);
            return response.json();
        })
          .then(data => {
              if(data.success) {
                  alert(data.message ?? 'Statut du client mis à jour.');
                  location.reload();
              } else {
                  alert(data.message ?? 'Erreur lors du changement de statut');
              }
          }).catch(error => {
              console.error('Error:', error);
              alert('Une erreur est survenue lors du changement de statut.');
          });
    }
}

function confirmDelete(id, name) {
    deleteForm.action = `/admin/clients/${id}`;
    deleteClientName.textContent = name;
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

window.onclick = function(event) {
    if (event.target == deleteModal) {
        closeDeleteModal();
    }
}
</script>
@endpush
@endsection