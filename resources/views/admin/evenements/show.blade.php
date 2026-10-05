{{-- resources/views/admin/evenements/show.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Détail - ' . $evenement->titre)
@section('page-title', $evenement->titre)
@section('page-description', 'Détail de l\'événement')

@section('page-actions')
    @if($evenement->billets->count() > 0)
    <a href="{{ route('admin.evenements.download-all-tickets', $evenement) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        Télécharger tous les billets
    </a>
    @endif
    <a href="{{ route('admin.evenements.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Retour
    </a>
    <a href="{{ route('admin.evenements.edit', $evenement) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
        Modifier
    </a>
@endsection

@section('content')
@php
    // Calcul des statistiques
    $billetsVendus = $evenement->billets_count ?? $evenement->billets->count() ?? 0;
    $capaciteTotale = $evenement->capacite;
    $chiffreAffaires = $evenement->billets->sum('prix');
@endphp

<div class="max-w-7xl mx-auto">
    <!-- En-tête avec affiche -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="relative">
            @if($evenement->affiche)
                <img src="{{ Storage::url($evenement->affiche) }}" class="w-full h-64 object-cover" alt="{{ $evenement->titre }}">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
            @else
                <div class="w-full h-64 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                    <svg class="w-24 h-24 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            @endif
            <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black to-transparent">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center space-x-3 mb-2">
                            @php
                                $statusConfig = [
                                    'Publié' => ['color' => 'green', 'icon' => 'check-circle'],
                                    'Brouillon' => ['color' => 'yellow', 'icon' => 'edit'],
                                    'Terminé' => ['color' => 'blue', 'icon' => 'flag-checkered'],
                                    'Annulé' => ['color' => 'red', 'icon' => 'times-circle'],
                                    'Reporté' => ['color' => 'orange', 'icon' => 'clock']
                                ];
                                $config = $statusConfig[$evenement->statut] ?? ['color' => 'gray', 'icon' => 'info-circle'];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $evenement->statut }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                ID: #{{ $evenement->id }}
                            </span>
                        </div>
                        <h1 class="text-3xl font-bold text-white">{{ $evenement->titre }}</h1>
                        @if($evenement->created_at->diffInDays(now()) <= 7)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-500 text-white mt-2">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Nouvel événement
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne de gauche - Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Description</h3>
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 leading-relaxed">{{ $evenement->description ?? 'Aucune description disponible pour cet événement.' }}</p>
                </div>
            </div>
            
            <!-- Informations détaillées -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Informations générales</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Type d'événement</p>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"></path>
                                    </svg>
                                    <span class="font-medium text-gray-900">{{ $evenement->type ?? 'Non spécifié' }}</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Date et heure</p>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($evenement->date)->format('d/m/Y à H:i') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Capacité totale</p>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="font-medium text-gray-900">{{ number_format($capaciteTotale, 0, ',', ' ') }} places</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Créé le</p>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($evenement->created_at)->format('d/m/Y à H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Lieu -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Lieu de l'événement</h3>
                    </div>
                </div>
                <div class="p-6">
                    @if($evenement->lieu)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Nom du lieu</p>
                                    <p class="font-medium text-gray-900">{{ $evenement->lieu->nom }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Ville</p>
                                    <p class="font-medium text-gray-900">{{ $evenement->lieu->ville }}</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Adresse</p>
                                    <p class="font-medium text-gray-900">{{ $evenement->lieu->adresse }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Capacité du lieu</p>
                                    <p class="font-medium text-gray-900">{{ number_format($evenement->lieu->capacite, 0, ',', ' ') }} places</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <p class="text-gray-500">Aucun lieu défini pour cet événement</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Types de billets -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Types de billets</h3>
                    </div>
                </div>
                <div class="p-6">
                    @if($evenement->typesBillets && $evenement->typesBillets->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantité</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendus</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Disponibles</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Taux</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($evenement->typesBillets as $type)
                                        @php $tauxVente = $type->quantite > 0 ? ($type->quantite_vendue / $type->quantite) * 100 : 0; @endphp
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-3">
                                                <div class="font-medium text-gray-900">{{ $type->nom }}</div>
                                                @if($type->description)
                                                    <div class="text-xs text-gray-500 mt-1">{{ $type->description }}</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="font-semibold text-gray-900">{{ number_format($type->prix, 2, ',', ' ') }} DH</span>
                                            </td>
                                            <td class="px-4 py-3 text-gray-700">{{ number_format($type->quantite, 0, ',', ' ') }}</td>
                                            <td class="px-4 py-3 text-green-600 font-medium">{{ number_format($type->quantite_vendue, 0, ',', ' ') }}</td>
                                            <td class="px-4 py-3 text-blue-600 font-medium">{{ number_format($type->quantite - $type->quantite_vendue, 0, ',', ' ') }}</td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-sm font-medium">{{ number_format($tauxVente, 1) }}%</span>
                                                    <div class="flex-1 max-w-20">
                                                        <div class="bg-gray-200 rounded-full h-1.5">
                                                            <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $tauxVente }}%"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"></path>
                            </svg>
                            <p class="text-gray-500">Aucun type de billet défini pour cet événement</p>
                            <a href="{{ route('admin.evenements.edit', $evenement) }}" class="mt-3 inline-flex items-center text-blue-600 hover:text-blue-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Ajouter des billets
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Colonne de droite - Statistiques et Actions -->
        <div class="space-y-6">
            <!-- Statistiques -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Statistiques de vente</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                            <span class="text-gray-600">Billets vendus</span>
                            <span class="text-2xl font-bold text-gray-900">{{ number_format($billetsVendus, 0, ',', ' ') }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                            <span class="text-gray-600">Capacité totale</span>
                            <span class="text-2xl font-bold text-gray-900">{{ number_format($capaciteTotale, 0, ',', ' ') }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                            <span class="text-gray-600">Chiffre d'affaires</span>
                            <span class="text-2xl font-bold text-green-600">
                                {{ number_format($chiffreAffaires, 2, ',', ' ') }} DH
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Organisateur -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Organisateur</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="h-16 w-16 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xl shadow-md">
                                {{ strtoupper(substr($evenement->organisateur->prenom, 0, 1)) }}{{ strtoupper(substr($evenement->organisateur->nom, 0, 1)) }}
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 text-lg">{{ $evenement->organisateur->prenom }} {{ $evenement->organisateur->nom }}</p>
                            <div class="flex items-center mt-1">
                                <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm text-gray-600">{{ $evenement->organisateur->email }}</p>
                            </div>
                            @if($evenement->organisateur->telephone)
                                <div class="flex items-center mt-1">
                                    <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600">{{ $evenement->organisateur->telephone }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions rapides -->
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
                    <a href="{{ route('admin.evenements.acheter', $evenement) }}" 
                       class="flex items-center justify-center w-full px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg transition-all duration-200 font-medium shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Acheter des tickets
                    </a>
                    <a href="{{ route('admin.evenements.billets', $evenement) }}" 
                       class="flex items-center justify-center w-full px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg transition-all duration-200 font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"></path>
                        </svg>
                        Gérer les billets
                    </a>
                    <button onclick="changeStatus({{ $evenement->id }})" 
                            class="flex items-center justify-center w-full px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg transition-all duration-200 font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        Changer le statut
                    </button>
                    <button onclick="openDeleteModal({{ $evenement->id }}, '{{ addslashes($evenement->titre) }}')" 
                            class="flex items-center justify-center w-full px-4 py-2 bg-white border border-red-300 hover:bg-red-50 text-red-600 rounded-lg transition-all duration-200 font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Supprimer l'événement
                    </button>
                </div>
            </div>
        </div>
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
            <form id="statusForm" method="POST" action="{{ route('admin.evenements.status', $evenement) }}">
                @csrf
                @method('PATCH')
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nouveau statut</label>
                    <select name="statut" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200">
                        <option value="Publié" {{ $evenement->statut == 'Publié' ? 'selected' : '' }}>📢 Publié</option>
                        <option value="Brouillon" {{ $evenement->statut == 'Brouillon' ? 'selected' : '' }}>📝 Brouillon</option>
                        <option value="Terminé" {{ $evenement->statut == 'Terminé' ? 'selected' : '' }}>🏁 Terminé</option>
                        <option value="Annulé" {{ $evenement->statut == 'Annulé' ? 'selected' : '' }}>❌ Annulé</option>
                        <option value="Reporté" {{ $evenement->statut == 'Reporté' ? 'selected' : '' }}>⏰ Reporté</option>
                    </select>
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
let deleteModal = document.getElementById('deleteModal');
let deleteModalContent = document.getElementById('deleteModalContent');
let deleteForm = document.getElementById('delete-form');
let deleteEventName = document.getElementById('delete-event-name');

function changeStatus(eventId) {
    const modal = document.getElementById('statusModal');
    const modalContent = document.getElementById('statusModalContent');
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
}

function openDeleteModal(id, titre) {
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

// Fermer les modals en cliquant en dehors
window.onclick = function(event) {
    const statusModal = document.getElementById('statusModal');
    const deleteModalEl = document.getElementById('deleteModal');
    
    if (event.target == statusModal) {
        closeModal();
    }
    if (event.target == deleteModalEl) {
        closeDeleteModal();
    }
}

// Animation des barres de progression
document.addEventListener('DOMContentLoaded', function() {
    const progressBars = document.querySelectorAll('.bg-gradient-to-r');
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