@extends('client.layouts.master')

@section('title', 'Billet #' . $billet->id)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-t-2xl p-6 text-white">
        <h1 class="text-3xl font-bold">Billet #{{ $billet->id }}</h1>
        <p class="text-blue-100 mt-2">{{ $billet->evenement->titre }} - {{ \Carbon\Carbon::parse($billet->evenement->date)->format('d/m/Y H:i') }}</p>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-b-2xl shadow-lg overflow-hidden">
        <div class="p-6 lg:p-8">
            <!-- Status Badge -->
            <div class="mb-6 flex items-center gap-3">
                @if($billet->statut == 'Valide')
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 text-green-700 rounded-lg font-semibold text-sm">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        Valide - À présenter à l'entrée
                    </span>
                @elseif($billet->statut == 'Utilisé')
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-700 rounded-lg font-semibold text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Utilisé - Entrée validée
                    </span>
                @elseif($billet->statut == 'Annulé')
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 text-red-700 rounded-lg font-semibold text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Annulé - Non valide
                    </span>
                @endif
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Informations Événement -->
                <div class="border-l-4 border-blue-500 pl-4">
                    <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-4">📅 Informations Événement</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Titre</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $billet->evenement->titre }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Date & Heure</p>
                            <p class="text-gray-700">{{ \Carbon\Carbon::parse($billet->evenement->date)->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Lieu</p>
                            <p class="text-gray-700">
                                {{ $billet->evenement->lieu->nom ?? 'Non défini' }}
                                @if($billet->evenement->lieu->ville)
                                    <br><span class="text-sm text-gray-500">{{ $billet->evenement->lieu->ville }}</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Capacité</p>
                            <p class="text-gray-700">{{ $billet->evenement->capacite }} places</p>
                        </div>
                    </div>
                </div>

                <!-- Détails Billet -->
                <div class="border-l-4 border-indigo-500 pl-4">
                    <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-4">🎟️ Détails Billet</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Type de Billet</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $billet->typeBillet->nom ?? 'Standard' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Prix</p>
                            <p class="text-lg font-bold text-green-600">{{ number_format($billet->prix, 2, ',', ' ') }} DH</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Code Unique</p>
                            <p class="font-mono text-sm bg-gray-100 px-3 py-2 rounded text-gray-700 break-all">{{ $billet->code_unique }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Statut</p>
                            <p class="font-semibold text-gray-800">{{ $billet->statut }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Client Information -->
            <div class="bg-gray-50 rounded-lg p-4 mb-8 border-l-4 border-purple-500">
                <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-4">👤 Information Titulaire</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Spectateur</p>
                        <p class="font-semibold text-gray-800">{{ $billet->nom_spectateur ?? $billet->commande->client->prenom . ' ' . $billet->commande->client->nom }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Email</p>
                        <p class="text-gray-700">{{ $billet->email_spectateur ?? $billet->commande->client->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Validation Information (if used) -->
            @if($billet->statut == 'Utilisé' && $billet->date_validation)
            <div class="bg-blue-50 rounded-lg p-4 mb-8 border-l-4 border-blue-500">
                <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-4">✓ Information Validation</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Date de Validation</p>
                        <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($billet->date_validation)->format('d/m/Y H:i:s') }}</p>
                    </div>
                    @if($billet->valide_par)
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Validé par</p>
                        <p class="text-gray-700">
                            @php
                                $validateur = \App\Models\User::find($billet->valide_par);
                            @endphp
                            {{ $validateur ? ($validateur->prenom . ' ' . $validateur->nom) : 'Organisateur' }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- QR Code Section -->
            @if($billet->statut == 'Valide')
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-6 mb-8 text-center border-2 border-dashed border-gray-300">
                <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-4">📱 Code QR</h2>
                <p class="text-sm text-gray-600 mb-4">Présentez ce code à l'entrée :</p>
                <div class="inline-block">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($billet->code_unique) }}" alt="QR Code" class="max-w-xs">
                </div>
                <p class="text-xs text-gray-500 mt-4">Ou présentez le code unique ci-dessous</p>
            </div>
            @elseif($billet->statut == 'Utilisé')
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-6 mb-8 text-center border-2 border-blue-300">
                <h2 class="text-sm font-bold text-blue-600 uppercase tracking-wide mb-4">📱 Code QR (Archivé)</h2>
                <p class="text-sm text-blue-700 mb-4">Ce billet a été validé :</p>
                <div class="inline-block opacity-75">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($billet->code_unique) }}" alt="QR Code" class="max-w-xs">
                </div>
                <p class="text-xs text-blue-600 mt-4">Code archivé après validation</p>
            </div>
            @endif

            <!-- Actions -->
            <div class="flex flex-col md:flex-row gap-4">
                @if($billet->statut == 'Valide')
                <a href="{{ route('client.billets.download', $billet->id) }}" class="flex-1 inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 px-4 rounded-lg transition-all shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Télécharger PDF
                </a>
                @else
                <button disabled class="flex-1 inline-flex items-center justify-center gap-2 bg-gray-300 text-gray-600 font-semibold py-3 px-4 rounded-lg cursor-not-allowed opacity-60">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Télécharger PDF (Indisponible)
                </button>
                @endif
                
                <a href="{{ route('client.billets.index') }}" class="flex-1 inline-flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Retour à mes billets
                </a>
            </div>
        </div>
    </div>
</div>
@endsection