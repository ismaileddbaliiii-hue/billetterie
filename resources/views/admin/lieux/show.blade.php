{{-- resources/views/admin/lieux/show.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Détails du lieu - ' . $lieu->nom)
@section('page-title', $lieu->nom)
@section('page-description', 'Informations détaillées sur le lieu')

@section('page-actions')
    <a href="{{ route('admin.lieux.edit', $lieu) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
        </svg>
        Modifier
    </a>
    <a href="{{ route('admin.lieux.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Retour à la liste
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Informations principales -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <h3 class="text-base font-bold text-gray-900">Informations générales</h3>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Nom du lieu</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $lieu->nom }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Adresse</label>
                        <p class="text-gray-700">{{ $lieu->adresse }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Ville</label>
                        <p class="text-gray-700">{{ $lieu->ville }}</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Capacité</label>
                        <p class="text-lg font-semibold text-blue-600">{{ number_format($lieu->capacite) }} places</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Créé le</label>
                        <p class="text-gray-700">{{ $lieu->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Dernière modification</label>
                        <p class="text-gray-700">{{ $lieu->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Événements totaux</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_evenements'] ?? 0 }}</p>
                </div>
                <div class="h-12 w-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Événements à venir</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['evenements_futurs'] ?? 0 }}</p>
                </div>
                <div class="h-12 w-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Événements passés</p>
                    <p class="text-3xl font-bold text-orange-600 mt-2">{{ $stats['evenements_passes'] ?? 0 }}</p>
                </div>
                <div class="h-12 w-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Billets vendus</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $stats['total_billets'] ?? 0 }}</p>
                </div>
                <div class="h-12 w-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Événements récents -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-base font-bold text-gray-900">Événements récents</h3>
                </div>
                @if(($stats['total_evenements'] ?? 0) > 10)
                    <a href="{{ route('admin.evenements.index', ['lieu' => $lieu->id]) }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1 hover:gap-2 transition-all">
                        Voir tous <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                @endif
            </div>
        </div>
        <div class="p-6">
            @if($lieu->evenements->count() > 0)
                <div class="space-y-4">
                    @foreach($lieu->evenements as $evenement)
                    <a href="{{ route('admin.evenements.show', $evenement) }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200 hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 hover:border-blue-200 transition-all duration-200 hover:shadow-md group">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center text-white font-bold shadow-sm group-hover:scale-105 transition-transform duration-200">
                                {{ strtoupper(substr($evenement->titre ?? 'E', 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $evenement->titre }}</h4>
                                <p class="text-sm text-gray-500">
                                    {{ $evenement->organisateur ? $evenement->organisateur->prenom . ' ' . $evenement->organisateur->nom : 'Organisateur inconnu' }}
                                    • {{ $evenement->date ? $evenement->date->format('d/m/Y') : 'Date inconnue' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                @if($evenement->statut == 'Publié')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>
                                        Publié
                                    </span>
                                @elseif($evenement->statut == 'Brouillon')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                        <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1"></span>
                                        Brouillon
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        <span class="w-1.5 h-1.5 bg-gray-500 rounded-full mr-1"></span>
                                        {{ $evenement->statut }}
                                    </span>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500 mt-0">{{ $evenement->billets_count ?? 0 }} billets</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-400">Aucun événement n'a encore été organisé dans ce lieu</p>
                    <a href="{{ route('admin.evenements.create') }}" class="inline-flex items-center gap-2 mt-3 text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Créer le premier événement
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection