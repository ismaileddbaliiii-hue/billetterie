{{-- resources/views/admin/evenements/acheter.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Acheter des tickets - ' . $evenement->titre)
@section('page-title', 'Achat de tickets')
@section('page-description', 'Gestion des achats de tickets pour l\'événement')

@section('page-actions')
    <a href="{{ route('admin.evenements.show', $evenement) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Retour à l'événement
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Messages de notification -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Informations événement -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h3 class="text-lg font-medium text-gray-900">Informations événement</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-2">{{ $evenement->titre }}</h4>
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($evenement->date)->translatedFormat('l d F Y à H:i') }}</span>
                        </div>
                        @if($evenement->lieu)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>{{ $evenement->lieu->nom }} - {{ $evenement->lieu->adresse }}, {{ $evenement->lieu->ville }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-600 mb-1">Places disponibles</div>
                    <div class="text-3xl font-bold text-gray-900">{{ $evenement->placesRestantes() }}</div>
                    <div class="text-sm text-gray-500">Capacité totale : {{ $evenement->capacite }} places</div>
                    <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $evenement->capacite > 0 ? (($evenement->capacite - $evenement->placesRestantes()) / $evenement->capacite * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.evenements.acheter.confirm', $evenement) }}" method="POST" id="achatForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Sélection des tickets -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Sélection des tickets</h3>
                                <p class="text-sm text-gray-500 mt-1">Choisissez les types et quantités de billets</p>
                            </div>
                            <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded border border-gray-200">
                                Quantité max: {{ $quantiteMax }}
                            </span>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($typesBillets as $type)
                        <div class="p-6 hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="text-base font-semibold text-gray-900">{{ $type->nom }}</h4>
                                            <p class="text-sm text-gray-500 mt-1">
                                                <span class="inline-flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $type->quantite - $type->quantite_vendue }} disponibles
                                                </span>
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xl font-bold text-gray-900">{{ number_format($type->prix, 2, ',', ' ') }} DH</div>
                                            <div class="text-xs text-gray-500">TTC / unité</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center border border-gray-300 rounded-lg bg-white">
                                        <button type="button" onclick="updateQuantity({{ $type->id }}, -1)"
                                                class="w-9 h-9 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors rounded-l-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <input type="number" id="quantite_{{ $type->id }}" 
                                               name="billets[{{ $type->id }}][quantite]"
                                               value="0" min="0" max="{{ $type->quantite - $type->quantite_vendue }}"
                                               class="w-16 text-center border-0 focus:ring-0 text-gray-900 font-medium py-2">
                                        <input type="hidden" name="billets[{{ $type->id }}][type_id]" value="{{ $type->id }}">
                                        <button type="button" onclick="updateQuantity({{ $type->id }}, 1)"
                                                class="w-9 h-9 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors rounded-r-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="min-w-[100px] text-right">
                                        <span class="text-sm text-gray-500">Total</span>
                                        <div class="text-lg font-semibold text-gray-900" id="total_{{ $type->id }}">0,00 DH</div>
                                    </div>
                                </div>
                            </div>
                            @if($type->description)
                            <p class="text-xs text-gray-400 mt-3">{{ $type->description }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Récapitulatif et informations client -->
            <div class="space-y-6">
                <!-- Informations spectateur -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h3 class="text-lg font-medium text-gray-900">Informations spectateur</h3>
                        <p class="text-sm text-gray-500 mt-1">Coordonnées du bénéficiaire</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nom complet <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nom_spectateur" value="{{ old('nom_spectateur') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Dupont Jean">
                            @error('nom_spectateur') 
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Adresse email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email_spectateur" value="{{ old('email_spectateur') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="jean.dupont@email.com">
                            @error('email_spectateur') 
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                            @enderror
                        </div>
                        <div class="bg-blue-50 rounded-lg p-3">
                            <div class="flex items-start">
                                <svg class="w-4 h-4 text-blue-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-xs text-blue-700">Les tickets seront envoyés à cette adresse email après validation du paiement.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Récapitulatif commande -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden sticky top-6">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h3 class="text-lg font-medium text-gray-900">Récapitulatif</h3>
                        <p class="text-sm text-gray-500 mt-1">Détail de votre commande</p>
                    </div>
                    <div class="p-6">
                        <div id="orderSummary" class="space-y-3 mb-4 max-h-96 overflow-y-auto">
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-gray-400 text-sm">Aucun ticket sélectionné</p>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4 mt-2">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-base font-medium text-gray-700">Total à payer</span>
                                <span class="text-2xl font-bold text-gray-900" id="grandTotal">0,00 DH</span>
                            </div>
                            
                            <button type="submit" id="submitBtn"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-blue-600">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Confirmer l'achat
                            </button>
                            
                            <p class="text-xs text-gray-500 text-center mt-4">
                                En confirmant, vous acceptez les conditions générales de vente
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Configuration des tickets
const ticketPrices = {};
const ticketNames = {};
const ticketAvailable = {};

@foreach($typesBillets as $type)
    ticketPrices[{{ $type->id }}] = {{ $type->prix }};
    ticketNames[{{ $type->id }}] = '{{ addslashes($type->nom) }}';
    ticketAvailable[{{ $type->id }}] = {{ $type->quantite - $type->quantite_vendue }};
@endforeach

// Mise à jour des quantités
function updateQuantity(typeId, change) {
    const input = document.getElementById(`quantite_${typeId}`);
    const maxQuantity = parseInt(input.getAttribute('max')) || 0;
    let currentValue = parseInt(input.value) || 0;
    
    currentValue += change;
    currentValue = Math.max(0, Math.min(maxQuantity, currentValue));
    input.value = currentValue;
    
    // Mettre à jour l'affichage
    const total = currentValue * ticketPrices[typeId];
    document.getElementById(`total_${typeId}`).textContent = 
        total.toLocaleString('fr-FR', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' DH';
    
    // Mettre à jour le récapitulatif
    updateOrderSummary();
}

// Mise à jour du récapitulatif
function updateOrderSummary() {
    const summaryDiv = document.getElementById('orderSummary');
    const grandTotalSpan = document.getElementById('grandTotal');
    const submitBtn = document.getElementById('submitBtn');
    
    let grandTotal = 0;
    let totalTickets = 0;
    let summaryHtml = '';
    
    for (const typeId in ticketPrices) {
        const qty = parseInt(document.getElementById(`quantite_${typeId}`).value) || 0;
        if (qty > 0) {
            totalTickets += qty;
            const subtotal = qty * ticketPrices[typeId];
            grandTotal += subtotal;
            
            summaryHtml += `
                <div class="flex justify-between items-start py-2">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">${ticketNames[typeId]}</p>
                        <p class="text-xs text-gray-500">${qty} × ${ticketPrices[typeId].toLocaleString('fr-FR', {minimumFractionDigits: 2})} DH</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">${subtotal.toLocaleString('fr-FR', {minimumFractionDigits: 2})} DH</p>
                    </div>
                </div>
            `;
        }
    }
    
    if (totalTickets === 0) {
        summaryHtml = `
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p class="text-gray-400 text-sm">Aucun ticket sélectionné</p>
            </div>
        `;
        submitBtn.disabled = true;
    } else {
        summaryHtml = `
            <div class="space-y-2">
                ${summaryHtml}
                <div class="border-t border-gray-200 pt-3 mt-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">Nombre de tickets</span>
                        <span class="text-sm font-semibold text-gray-900">${totalTickets}</span>
                    </div>
                </div>
            </div>
        `;
        submitBtn.disabled = false;
    }
    
    summaryDiv.innerHTML = summaryHtml;
    grandTotalSpan.textContent = grandTotal.toLocaleString('fr-FR', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' DH';
}

// Validation du formulaire
document.getElementById('achatForm').addEventListener('submit', function(e) {
    // Supprimer les entrées avec quantité 0
    const quantityInputs = document.querySelectorAll('input[name*="[quantite]"]');
    quantityInputs.forEach(input => {
        const quantity = parseInt(input.value) || 0;
        if (quantity === 0) {
            const match = input.name.match(/billets\[(\d+)\]/);
            if (match) {
                const typeId = match[1];
                const typeIdInput = document.querySelector(`input[name="billets[${typeId}][type_id]"]`);
                if (typeIdInput) typeIdInput.remove();
                input.remove();
            }
        }
    });
    
    // Vérifier la quantité totale
    const totalQuantity = Array.from(document.querySelectorAll('input[name*="[quantite]"]'))
        .reduce((sum, input) => sum + (parseInt(input.value) || 0), 0);
    
    if (totalQuantity === 0) {
        e.preventDefault();
        alert('Veuillez sélectionner au moins un ticket.');
        return false;
    }
    
    const maxAvailable = {{ $quantiteMax }};
    if (totalQuantity > maxAvailable) {
        e.preventDefault();
        alert(`La quantité totale demandée (${totalQuantity}) dépasse les places disponibles (${maxAvailable}).`);
        return false;
    }
    
    return true;
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    updateOrderSummary();
    
    // Ajouter les écouteurs d'événements
    for (const typeId in ticketPrices) {
        const input = document.getElementById(`quantite_${typeId}`);
        if (input) {
            input.addEventListener('input', function() {
                let value = parseInt(this.value) || 0;
                const max = parseInt(this.getAttribute('max')) || 0;
                if (value > max) {
                    this.value = max;
                    value = max;
                }
                if (value < 0) {
                    this.value = 0;
                    value = 0;
                }
                updateQuantity(parseInt(typeId), 0);
            });
        }
    }
});
</script>
@endpush

@endsection