{{-- resources/views/admin/commandes/show.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Commande - ' . $commande->reference)
@section('page-title', 'Commande ' . $commande->reference)
@section('page-description', 'Détails de la commande')

@section('page-actions')
    @if($commande->billets->count() > 0)
    <a href="{{ route('admin.commandes.download-billets', $commande) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        Télécharger billets PDF
    </a>
    @endif
    <a href="{{ route('admin.commandes.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Retour à la liste
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Informations de la commande -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-base font-bold text-gray-900">Informations de la commande</h3>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Référence</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $commande->reference }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Statut</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($commande->statut == 'Confirmée') bg-green-100 text-green-800
                        @elseif($commande->statut == 'En attente') bg-yellow-100 text-yellow-800
                        @elseif($commande->statut == 'Annulée') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $commande->statut }}
                    </span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Montant total</label>
                    <p class="text-lg font-semibold text-green-600">{{ number_format($commande->montant_total, 2, ',', ' ') }} DH</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Date</label>
                    <p class="text-gray-700">{{ $commande->date->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations client -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <h3 class="text-base font-bold text-gray-900">Informations client</h3>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Nom complet</label>
                        <p class="text-gray-900">{{ $commande->client ? $commande->client->prenom . ' ' . $commande->client->nom : 'Client inconnu' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                        <p class="text-gray-700">{{ $commande->client ? $commande->client->email : $commande->email_spectateur }}</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Téléphone</label>
                        <p class="text-gray-700">{{ $commande->client && $commande->client->telephone ? $commande->client->telephone : '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Rôle</label>
                        <p class="text-gray-700">{{ $commande->client ? ucfirst($commande->client->role) : 'Spectateur' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Billets -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                    <h3 class="text-base font-bold text-gray-900">Billets ({{ $commande->billets->count() }})</h3>
                </div>
            </div>
        </div>
        <div class="p-6">
            @if($commande->billets->count() > 0)
                <div class="space-y-4">
                    @foreach($commande->billets as $billet)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200 hover:border-blue-300 transition-colors">
                        <div class="flex items-center space-x-4 flex-1">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center text-white font-bold shadow-sm">
                                {{ strtoupper(substr($billet->evenement->titre ?? 'E', 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">{{ $billet->evenement->titre ?? 'Événement inconnu' }}</h4>
                                <p class="text-sm text-gray-500">
                                    Code: <span class="font-mono text-xs">{{ $billet->code_unique }}</span>
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $billet->typeBillet->nom ?? 'Type inconnu' }} •
                                    {{ $billet->evenement->date ? $billet->evenement->date->format('d/m/Y') : 'Date inconnue' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <div class="text-lg font-semibold text-green-600">{{ number_format($billet->prix, 2, ',', ' ') }} DH</div>
                                <div class="text-sm text-gray-500">Quantité: {{ $billet->quantite }}</div>
                            </div>
                            <a href="{{ route('admin.evenements.tickets.download-single', ['evenement' => $billet->evenement_id, 'billet' => $billet->id]) }}" 
                               class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-green-100 hover:bg-green-200 text-green-600 transition-all duration-200 shadow-sm" 
                               title="Télécharger le billet">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-400">Aucun billet dans cette commande</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Informations de paiement -->
    @if($commande->paiement)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                <h3 class="text-base font-bold text-gray-900">Informations de paiement</h3>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Prestataire</label>
                    <p class="text-gray-900">{{ ucfirst($commande->paiement->prestataire) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Référence paiement</label>
                    <p class="text-gray-900">{{ $commande->paiement->reference ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Statut paiement</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($commande->paiement->statut == 'Réussi') bg-green-100 text-green-800
                        @elseif($commande->paiement->statut == 'En cours') bg-yellow-100 text-yellow-800
                        @elseif($commande->paiement->statut == 'Échec') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $commande->paiement->statut }}
                    </span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Date paiement</label>
                    <p class="text-gray-700">{{ $commande->paiement->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Notes -->
    @if($commande->notes)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <h3 class="text-base font-bold text-gray-900">Notes</h3>
            </div>
        </div>
        <div class="p-6">
            <div class="bg-gray-50 rounded-lg p-4">
                <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ json_decode($commande->notes, true) ? json_encode(json_decode($commande->notes, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $commande->notes }}</pre>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection