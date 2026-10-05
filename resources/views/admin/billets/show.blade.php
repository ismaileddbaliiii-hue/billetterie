@extends('admin.layouts.master')

@section('title', 'Détails du Billet - ' . $billet->code)
@section('page-title', 'Billet ' . $billet->code)
@section('page-description', 'Détails complets du billet')

@section('page-actions')
    <a href="{{ route('admin.billets.download', $billet) }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
        </svg>
        Télécharger PDF
    </a>
    @if($billet->statut !== 'Utilisé')
        <form action="{{ route('admin.billets.valider', $billet) }}" method="POST" class="inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Valider le billet
            </button>
        </form>
    @endif
    <a href="{{ route('admin.billets.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Retour
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Informations du billet -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                </svg>
                <h3 class="text-base font-bold text-gray-900">Informations du billet</h3>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Colonne gauche -->
                <div class="space-y-6">
                    <!-- Code -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Code du billet</label>
                        <div class="flex items-center gap-3">
                            <p class="text-2xl font-bold font-mono text-gray-900 bg-gray-50 px-4 py-3 rounded-lg border border-gray-200">{{ $billet->code }}</p>
                            <button onclick="navigator.clipboard.writeText('{{ $billet->code }}')" class="p-2 hover:bg-gray-100 rounded-lg transition-colors text-gray-600 hover:text-gray-900">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Événement -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Événement</label>
                        @if($billet->evenement)
                            <a href="{{ route('admin.evenements.show', $billet->evenement) }}" class="text-lg font-semibold text-blue-600 hover:text-blue-800">
                                {{ $billet->evenement->titre }}
                            </a>
                            <p class="text-sm text-gray-600 mt-1">{{ $billet->evenement->date->format('d/m/Y à H:i') }}</p>
                        @else
                            <span class="text-gray-400">Événement supprimé</span>
                        @endif
                    </div>

                    <!-- Lieu -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Lieu</label>
                        @if($billet->evenement?->lieu)
                            <p class="text-gray-900">{{ $billet->evenement->lieu->nom }}</p>
                            <p class="text-sm text-gray-600">{{ $billet->evenement->lieu->adresse }}, {{ $billet->evenement->lieu->ville }}</p>
                        @else
                            <span class="text-gray-400">Lieu non disponible</span>
                        @endif
                    </div>

                    <!-- Type de billet -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Type de billet</label>
                        <div class="flex items-center gap-2">
                            <span class="text-gray-900 font-medium">{{ $billet->typeBillet?->nom ?? 'N/A' }}</span>
                            @if($billet->typeBillet?->prix)
                                <span class="text-blue-600 font-semibold">{{ number_format($billet->typeBillet->prix, 2, ',', ' ') }} €</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Colonne droite -->
                <div class="space-y-6">
                    <!-- Client -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Client</label>
                        @if($billet->commande?->client)
                            <div>
                                <p class="font-semibold text-gray-900">
                                    {{ $billet->commande->client->prenom }} {{ $billet->commande->client->nom }}
                                </p>
                                <p class="text-sm text-gray-600">{{ $billet->commande->client->email }}</p>
                                @if($billet->commande->client->telephone)
                                    <p class="text-sm text-gray-600">{{ $billet->commande->client->telephone }}</p>
                                @endif
                            </div>
                        @else
                            <span class="text-gray-400">Client supprimé</span>
                        @endif
                    </div>

                    <!-- Commande -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Commande</label>
                        @if($billet->commande)
                            <a href="{{ route('admin.commandes.show', $billet->commande) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                {{ $billet->commande->numero_commande }}
                            </a>
                        @else
                            <span class="text-gray-400">Commande supprimée</span>
                        @endif
                    </div>

                    <!-- Statut -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Statut</label>
                        @if(in_array($billet->statut, ['Valide', 'Utilisé']))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                {{ $billet->statut ?? 'Valide' }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                                {{ $billet->statut ?? 'Inconnu' }}
                            </span>
                        @endif
                    </div>

                    <!-- Validation -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">État de validation</label>
                        @if($billet->statut === 'Utilisé')
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 text-green-700">
                                    {{-- <i class="fas fa-check-circle"></i>
                                    <span class="font-medium">Billet validé</span> --}}
                                </div>
                                @if($billet->date_validation)
                                    <p class="text-xs text-gray-600 ml-6">Validé le {{ $billet->date_validation->format('d/m/Y à H:i') }}</p>
                                @endif
                            </div>
                        @else
                            <div class="flex items-center gap-2 text-gray-700">
                                <i class="fas fa-times-circle"></i>
                                <span class="font-medium">Non validé</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Dates -->
            <div class="border-t border-gray-200 mt-6 pt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Créé le</label>
                    <p class="text-gray-900">{{ $billet->created_at->format('d/m/Y à H:i') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Dernière modification</label>
                    <p class="text-gray-900">{{ $billet->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Code -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <h3 class="text-base font-bold text-gray-900">Code QR du billet</h3>
            </div>
        </div>
        <div class="p-12 flex justify-center">
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                {!! QrCode::format('svg')->size(250)->generate($billet->code) !!}
                <p class="text-center text-sm text-gray-600 mt-4">{{ $billet->code }}</p>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h3 class="text-base font-bold text-gray-900">Actions</h3>
        </div>
        <div class="p-6 flex gap-3 flex-wrap">
            <a href="{{ route('admin.billets.download', $billet) }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-download mr-2"></i>Télécharger le billet
            </a>

            @if($billet->statut !== 'Utilisé')
                <form action="{{ route('admin.billets.valider', $billet) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-check mr-2"></i>Marquer comme validé
                    </button>
                </form>
            @else
                <button disabled class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-600 text-sm font-medium rounded-lg cursor-not-allowed">
                    <i class="fas fa-check mr-2"></i>Déjà validé
                </button>
            @endif

            <form action="{{ route('admin.billets.destroy', $billet) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr ? Cette action est irréversible.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <i class="fas fa-trash mr-2"></i>Supprimer le billet
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
