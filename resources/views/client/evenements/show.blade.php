@extends('client.layouts.master')

@section('title', 'Événements')
@section('page-title', 'Événements')
@section('page-description', 'Découvrez, réservez et vivez des expériences uniques près de chez vous')
@section('main-class', 'flex-1 overflow-y-auto p-0 bg-transparent')

@section('content')
<div class="min-h-screen bg-[#F8FAFC]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('client.evenements.index') }}" class="hover:text-blue-600 transition-colors">Événements</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-800 font-medium">{{ Str::limit($evenement->titre, 50) }}</span>
        </nav>
        
        @php
            $places_restantes = $evenement->capacite - ($evenement->billets_vendus ?? 0);
            $est_disponible = $places_restantes > 0;
            $date_evenement = \Carbon\Carbon::parse($evenement->date);
            $est_passe = $date_evenement->isPast();
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content - 2/3 width -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Event Image -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    @if($evenement->affiche)
                        <img src="{{ asset('storage/' . ltrim($evenement->affiche, '/')) }}" 
                             alt="{{ $evenement->titre }}" 
                             class="w-full h-80 lg:h-96 object-cover">
                    @else
                        <div class="w-full h-80 lg:h-96 bg-gradient-to-br from-blue-50 to-indigo-50 flex items-center justify-center">
                            <div class="text-center">
                                <i class="fas fa-calendar-alt text-gray-300 text-6xl mb-3"></i>
                                <p class="text-gray-400">Image non disponible</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Event Header -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                    @switch($evenement->type)
                                        @case('Concert') 🎵 Concert @break
                                        @case('Sport') ⚽ Sport @break
                                        @case('Theatre') 🎭 Théâtre @break
                                        @case('Conference') 💡 Conférence @break
                                        @case('Festival') 🎪 Festival @break
                                        @default 📅 Événement
                                    @endswitch
                                </span>
                                @if(!$est_passe && $est_disponible && $places_restantes <= 20)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-50 text-orange-700">
                                        <i class="fas fa-fire mr-1 text-xs"></i> Dernières places
                                    </span>
                                @endif
                            </div>
                            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900">{{ $evenement->titre }}</h1>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-500 mb-1">À partir de</div>
                            <div class="text-3xl font-bold text-blue-600">{{ number_format($evenement->prix_min, 0, ',', ' ') }} <span class="text-base font-normal text-gray-500">DH</span></div>
                        </div>
                    </div>
                    
                    <!-- Event Meta -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Date</p>
                                <p class="text-sm font-medium text-gray-800">{{ $date_evenement->translatedFormat('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-indigo-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Horaire</p>
                                <p class="text-sm font-medium text-gray-800">{{ $date_evenement->format('H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Places</p>
                                <p class="text-sm font-medium text-gray-800">{{ max(0, $places_restantes) }} / {{ $evenement->capacite }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-tag text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Référence</p>
                                <p class="text-sm font-medium text-gray-800">#EV{{ str_pad($evenement->id, 5, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-align-left text-blue-500"></i>
                        Description
                    </h2>
                    <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed">
                        <p>{{ $evenement->description ?? 'Aucune description disponible pour cet événement.' }}</p>
                    </div>
                </div>

                <!-- Location -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-location-dot text-red-500"></i>
                        Lieu
                    </h2>
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-red-500 text-xl"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $evenement->lieu->nom ?? 'Lieu non spécifié' }}</p>
                            <p class="text-gray-500 text-sm mt-1">
                                {{ $evenement->lieu->adresse ?? '' }} 
                                {{ isset($evenement->lieu->adresse) && isset($evenement->lieu->ville) ? ' - ' : '' }}
                                {{ $evenement->lieu->ville ?? '' }}
                            </p>
                            @if(isset($evenement->lieu->plan_de_salle) && $evenement->lieu->plan_de_salle)
                                <a href="{{ $evenement->lieu->plan_de_salle }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 mt-3 text-sm text-blue-600 hover:text-blue-700">
                                    <i class="fas fa-map"></i>
                                    <span>Voir le plan de salle</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Sidebar - 1/3 width -->
            <div class="lg:col-span-1">
                <div class="sticky top-24">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-ticket-alt text-blue-600"></i>
                                Réservation
                            </h2>
                        </div>

                        <div class="p-6">
                            @if($est_passe)
                                <div class="text-center py-6">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-600 font-medium">Événement terminé</p>
                                    <p class="text-sm text-gray-400 mt-1">Cet événement a déjà eu lieu</p>
                                    <a href="{{ route('client.evenements.index') }}" class="inline-block mt-5 text-sm text-blue-600 hover:text-blue-700 font-medium">
                                        Découvrir d'autres événements →
                                    </a>
                                </div>
                            @elseif(!$est_disponible)
                                <div class="text-center py-6">
                                    <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-ban text-red-500 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-600 font-medium">Complet</p>
                                    <p class="text-sm text-gray-400 mt-1">Toutes les places sont réservées</p>
                                    <a href="{{ route('client.evenements.index') }}" class="inline-block mt-5 text-sm text-blue-600 hover:text-blue-700 font-medium">
                                        Voir d'autres événements →
                                    </a>
                                </div>
                            @else
                                <form action="{{ route('client.panier.ajouter') }}" method="POST" id="reservation-form">
                                    @csrf
                                    <input type="hidden" name="evenement_id" value="{{ $evenement->id }}">
                                    
                                    <div class="space-y-4 mb-6">
                                        @forelse($typesBillets as $type => $data)
                                            <div class="border border-gray-200 rounded-lg p-3 hover:border-blue-200 transition-colors">
                                                <div class="flex justify-between items-center mb-3">
                                                    <div>
                                                        <p class="font-medium text-gray-900">{{ $type }}</p>
                                                        <p class="text-sm text-blue-600 font-semibold">{{ number_format($data['prix'], 0, ',', ' ') }} DH</p>
                                                        <p class="text-xs text-gray-500">{{ $data['disponible'] }} disponibles</p>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <button type="button" 
                                                                onclick="decrementQty('{{ Str::slug($type) }}')" 
                                                                class="w-8 h-8 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                                                            <i class="fas fa-minus text-xs text-gray-600"></i>
                                                        </button>
                                                        <input type="number" 
                                                               name="quantite[{{ $type }}]" 
                                                               id="qty_{{ Str::slug($type) }}"
                                                               value="0" 
                                                               min="0" 
                                                               max="{{ $data['disponible'] }}"
                                                               class="w-14 text-center border border-gray-300 rounded-lg py-1.5 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-200">
                                                        <button type="button" 
                                                                onclick="incrementQty('{{ Str::slug($type) }}', {{ $data['disponible'] }})" 
                                                                class="w-8 h-8 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                                                            <i class="fas fa-plus text-xs text-gray-600"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="text-right text-sm border-t border-gray-100 pt-2">
                                                    <span class="text-gray-500">Sous-total :</span>
                                                    <span id="subtotal_{{ Str::slug($type) }}" class="font-semibold text-gray-800 ml-2">0</span>
                                                    <span class="text-gray-500"> DH</span>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-center text-gray-500 py-4">Aucun billet disponible</p>
                                        @endforelse
                                    </div>

                                    <div class="border-t border-gray-200 pt-4 mb-5">
                                        <div class="flex justify-between items-center mb-4">
                                            <span class="text-gray-700 font-medium">Total</span>
                                            <div>
                                                <span id="total" class="text-2xl font-bold text-blue-600">0</span>
                                                <span class="text-gray-500"> DH</span>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" 
                                                id="btn-reserver" 
                                                disabled 
                                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                            <i class="fas fa-shopping-cart mr-2"></i>
                                            Ajouter au panier
                                        </button>
                                    </div>

                                    <div class="flex items-center justify-center gap-4 text-xs text-gray-400">
                                        <span><i class="fas fa-lock mr-1"></i> Paiement sécurisé</span>
                                        <span><i class="fas fa-shield-alt mr-1"></i> Garantie place</span>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mt-4">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-info-circle text-blue-500 text-lg mt-0.5"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-800 mb-1">Informations importantes</p>
                                <ul class="text-xs text-gray-500 space-y-1 list-disc list-inside">
                                    <li>Billets non remboursables</li>
                                    <li>Présentez votre confirmation à l'entrée</li>
                                    <li>Ouverture des portes 30 min avant</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const placesRestantes = {{ $places_restantes ?? 0 }};
    const typesBillets = @json($typesBillets ?? ['Standard' => ['prix' => $evenement->prix_min, 'disponible' => $places_restantes ?? 0]]);

    function updateTotal() {
        let total = 0;
        for (const [type, data] of Object.entries(typesBillets)) {
            const slug = type.replace(/\s+/g, '-').toLowerCase();
            const input = document.getElementById('qty_' + slug);
            if (input) {
                let qty = parseInt(input.value) || 0;
                let prix = typeof data === 'object' ? Number(data.prix) : Number(data);
                let subtotal = qty * prix;
                const subtotalSpan = document.getElementById('subtotal_' + slug);
                if (subtotalSpan) subtotalSpan.innerText = subtotal.toLocaleString('fr-FR');
                total += subtotal;
            }
        }
        const totalSpan = document.getElementById('total');
        if (totalSpan) totalSpan.innerText = total.toLocaleString('fr-FR');
        const btn = document.getElementById('btn-reserver');
        if (btn) btn.disabled = total <= 0;
    }

    function decrementQty(slug) {
        const input = document.getElementById('qty_' + slug);
        if (input) {
            let val = parseInt(input.value) || 0;
            if (val > 0) input.value = val - 1;
            updateTotal();
        }
    }

    function incrementQty(slug, max) {
        const input = document.getElementById('qty_' + slug);
        if (input) {
            let val = parseInt(input.value) || 0;
            if (val < max) input.value = val + 1;
            updateTotal();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateTotal();
        
        @foreach($typesBillets as $type => $data)
            const input{{ $loop->index }} = document.getElementById('qty_{{ Str::slug($type) }}');
            if (input{{ $loop->index }}) {
                input{{ $loop->index }}.addEventListener('change', function() {
                    let val = parseInt(this.value);
                    const max = {{ $data['disponible'] }};
                    if (isNaN(val) || val < 0) this.value = 0;
                    if (val > max) this.value = max;
                    updateTotal();
                });
            }
        @endforeach

        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('action') === 'reserver') {
            document.querySelector('.sticky')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>
@endpush

@if(session('success'))
    <div class="fixed bottom-5 right-5 z-50 bg-white rounded-lg shadow-xl border border-gray-100 p-4 animate-slide-up">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600"></i>
            </div>
            <div>
                <p class="font-medium text-gray-800">Succès</p>
                <p class="text-sm text-gray-500">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="fixed bottom-5 right-5 z-50 bg-white rounded-lg shadow-xl border border-gray-100 p-4 animate-slide-up">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation-circle text-red-600"></i>
            </div>
            <div>
                <p class="font-medium text-gray-800">Erreur</p>
                <p class="text-sm text-gray-500">{{ session('error') }}</p>
            </div>
        </div>
    </div>
@endif

<style>
    @keyframes slide-up {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-up {
        animation: slide-up 0.3s ease-out;
    }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button {
        opacity: 0.5;
    }
</style>
@endsection