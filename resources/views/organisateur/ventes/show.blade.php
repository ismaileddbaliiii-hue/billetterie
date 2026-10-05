@extends('organisateur.layouts.master')

@section('title', 'Commande #' . $commande->id)
@section('page-title', 'Détail de la commande')
@section('page-description', 'Commande #' . $commande->id . ' - ' . $commande->client->prenom . ' ' . $commande->client->nom)

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-wrap justify-between items-center gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Détails complets de la transaction</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Commande #{{ $commande->id }}</h1>
            <p class="text-gray-500 text-sm mt-1 flex items-center gap-2">
                <span class="inline-flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    {{ $commande->client->prenom ?? 'N/A' }} {{ $commande->client->nom ?? '' }}
                </span>
                <span class="text-gray-300">•</span>
                <span class="inline-flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $commande->evenement->titre ?? 'N/A' }}
                </span>
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('organisateur.ventes.index') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 border-2 border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-all font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour ventes
            </a>
            
        </div>
    </div>

    <!-- Order Details Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header with Status -->
        <div class="bg-gradient-to-r from-gray-900 to-gray-800 px-6 py-6 text-white">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-gray-400 text-sm">Détails de la commande</span>
                    </div>
                    <h2 class="text-2xl font-bold">Commande #{{ $commande->id }}</h2>
                    <div class="flex flex-wrap gap-4 mt-2">
                        <span class="inline-flex items-center gap-1.5 text-gray-400 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($commande->date)->format('d/m/Y H:i') }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-gray-400 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                            Réf: {{ $commande->reference ?? substr($commande->id . '-' . md5($commande->id), 0, 12) }}
                        </span>
                    </div>
                </div>
                <div>
                    @if($commande->statut == 'Confirmée')
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500/10 backdrop-blur-sm rounded-xl text-emerald-400 border border-emerald-500/20">
                            <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                            Confirmée
                        </span>
                    @elseif($commande->statut == 'En attente')
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500/10 backdrop-blur-sm rounded-xl text-amber-400 border border-amber-500/20">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            En attente
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-red-500/10 backdrop-blur-sm rounded-xl text-red-400 border border-red-500/20">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Annulée
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Client & Event Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Client</p>
                    <p class="font-bold text-gray-900 text-lg">{{ $commande->client->prenom ?? 'N/A' }} {{ $commande->client->nom ?? '' }}</p>
                    <p class="text-sm text-gray-500 flex items-center gap-1 mt-0.5">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $commande->client->email ?? 'N/A' }}
                    </p>
                    @if($commande->client->telephone)
                        <p class="text-sm text-gray-500 flex items-center gap-1 mt-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $commande->client->telephone }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Événement</p>
                    <p class="font-bold text-gray-900 text-lg">{{ $commande->evenement->titre ?? 'N/A' }}</p>
                    <div class="flex flex-wrap gap-3 mt-1">
                        <p class="text-sm text-gray-500 flex items-center gap-1">
                            <svg class="w-3 h-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $commande->evenement->lieu->nom ?? 'Lieu non défini' }}
                        </p>
                        <p class="text-sm text-gray-500 flex items-center gap-1">
                            <svg class="w-3 h-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($commande->evenement->date)->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tickets Table -->
        <div class="p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Billets achetés</h3>
                    <p class="text-xs text-gray-500">{{ $commande->billets->count() }} billet(s) au total</p>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 rounded-xl">
                        <tr class="border-b border-gray-200">
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Prix</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Code unique</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                          </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($commande->billets as $billet)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-gray-200 transition">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                        </svg>
                                    </div>
                                    <span class="font-mono text-sm font-semibold text-gray-800">#{{ $billet->id }}</span>
                                </div>
                              </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-semibold">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                    </svg>
                                    {{ $billet->typeBillet->nom ?? $billet->type ?? 'Standard' }}
                                </span>
                              </td>
                            <td class="px-4 py-4 text-right">
                                <span class="font-bold text-gray-800">{{ number_format($billet->prix, 2) }} DH</span>
                              </td>
                            <td class="px-4 py-4">
                                <span class="font-mono text-xs bg-gray-100 px-2 py-1.5 rounded-lg text-gray-600">{{ substr($billet->code_unique, 0, 12) }}...</span>
                              </td>
                            <td class="px-4 py-4">
                                @if($billet->statut == 'Valide')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-semibold">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                        Valide
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-50 text-red-700 rounded-lg text-xs font-semibold">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        {{ $billet->statut }}
                                    </span>
                                @endif
                              </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @if($billet->statut == 'Valide')
                                        <a href="{{ route('organisateur.billets.download', $billet->id) }}" 
                                           class="p-2 text-gray-400 hover:text-emerald-600 rounded-lg hover:bg-emerald-50 transition-all group relative"
                                           title="Télécharger le billet">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </a>
                                        <button onclick="showQRCode('{{ $billet->code_unique }}', {{ $billet->id }})" 
                                                class="p-2 text-gray-400 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition-all group relative"
                                                title="Afficher QR Code">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                              </td>
                          </tr>
                        @endforeach
                    </tbody>
                  </table>
            </div>

            <!-- Summary Cards -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Total billets</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $commande->billets->count() }}</p>
                        </div>
                        <div class="w-11 h-11 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl border border-emerald-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Montant total</p>
                            <p class="text-2xl font-bold text-emerald-600">{{ number_format($commande->montant_total, 2) }} DH</p>
                        </div>
                        <div class="w-11 h-11 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Paiement</p>
                            <p class="text-lg font-bold text-purple-600">{{ $commande->paiement->prestataire ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $commande->paiement->reference ?? 'N/A' }}</p>
                        </div>
                        <div class="w-11 h-11 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex flex-wrap justify-between gap-3">
                <div class="flex gap-3">
                    <a href="{{ route('organisateur.ventes.event', $commande->evenement_id) }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 border-2 border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-all font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Toutes les ventes
                    </a>
                    <a href="{{ route('organisateur.evenements.show', $commande->evenement_id) }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 border-2 border-blue-200 rounded-xl text-blue-600 hover:bg-blue-50 hover:border-blue-300 transition-all font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Voir l'événement
                    </a>
                </div>
                
               
            </div>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div id="qrModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4 transform transition-all scale-95 opacity-0" id="qrModalContent">
        <div class="relative">
            <div class="absolute -top-12 left-1/2 transform -translate-x-1/2">
                <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
            </div>
            <div class="pt-12 p-6">
                <div class="text-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900">QR Code du billet</h3>
                    <p class="text-xs text-gray-500 mt-1">Billet #<span id="qrBilletId" class="font-mono font-semibold"></span></p>
                </div>
                <div id="qrModalCode" class="flex justify-center mb-4"></div>
                <button onclick="closeQRModal()" 
                        class="w-full px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showQRCode(code, billetId) {
    document.getElementById('qrBilletId').innerText = billetId;
    
    // Generate QR code (using a library like QRCode.js)
    // This is a placeholder - you would implement actual QR generation
    document.getElementById('qrModalCode').innerHTML = `
        <div class="w-48 h-48 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center">
            <div class="text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                <p class="text-xs text-gray-500 font-mono">${code.substring(0, 20)}...</p>
            </div>
        </div>
    `;
    
    document.getElementById('qrModal').classList.remove('hidden');
    document.getElementById('qrModal').classList.add('flex');
    setTimeout(() => {
        document.getElementById('qrModalContent').classList.remove('scale-95', 'opacity-0');
        document.getElementById('qrModalContent').classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeQRModal() {
    document.getElementById('qrModalContent').classList.remove('scale-100', 'opacity-100');
    document.getElementById('qrModalContent').classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        document.getElementById('qrModal').classList.remove('flex');
        document.getElementById('qrModal').classList.add('hidden');
    }, 300);
}

function sendInvoiceEmail(commandeId) {
    fetch(`{{ url('organisateur/ventes') }}/${commandeId}/send-email`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    }).then(response => response.json())
      .then(data => {
          if(data.success) {
              alert('Facture envoyée avec succès par email !');
          } else {
              alert('Erreur lors de l\'envoi de l\'email');
          }
      });
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if(e.key === 'Escape' && document.getElementById('qrModal').classList.contains('flex')) {
        closeQRModal();
    }
});

// Close modal on background click
document.getElementById('qrModal').addEventListener('click', function(e) {
    if(e.target === this) {
        closeQRModal();
    }
});
</script>
@endsection