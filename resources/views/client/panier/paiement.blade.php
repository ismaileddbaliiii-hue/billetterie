@extends('client.layouts.master')

@section('title', 'Paiement')
@section('page-title', 'Paiement sécurisé')
@section('page-description', 'Finalisez votre commande en toute sécurité')

@section('content')
<div class="space-y-6">
    <!-- Header Alert -->
    @if(session('success'))
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 p-4 rounded-xl shadow-sm animate-slide-down">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 text-red-800 p-4 rounded-xl shadow-sm animate-slide-down">
            <div class="flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden sticky top-24 border border-gray-100 hover:shadow-2xl transition-shadow duration-300">
                <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                            <i class="fas fa-shopping-cart text-white"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">Résumé de la commande</h2>
                    </div>
                </div>
                
                <div class="p-6">
                    <!-- Items List -->
                    <div class="space-y-3 mb-4 max-h-80 overflow-y-auto custom-scrollbar">
                        @foreach($panier as $item)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 hover:bg-gray-50 px-2 rounded-lg transition-colors duration-200">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $item['type_nom'] }}</p>
                                    <p class="text-xs text-gray-500">Quantité: x{{ $item['quantite'] }}</p>
                                </div>
                                <span class="font-semibold text-gray-700">{{ number_format($item['sous_total'], 2, ',', ' ') }} DH</span>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Total -->
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-baseline">
                            <span class="text-gray-600">Sous-total</span>
                            <span class="font-medium">{{ number_format($montantTotal, 2, ',', ' ') }} DH</span>
                        </div>
                        <div class="flex justify-between items-baseline mt-2">
                            <span class="text-gray-600">Frais de service</span>
                            <span class="text-green-600 font-medium">Gratuit</span>
                        </div>
                        <div class="flex justify-between items-baseline mt-4 pt-4 border-t border-gray-200">
                            <span class="text-lg font-bold text-gray-800">Total TTC</span>
                            <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">{{ number_format($montantTotal, 2, ',', ' ') }} DH</span>
                        </div>
                    </div>
                    
                    <!-- Security Badge -->
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <div class="flex items-center justify-center gap-3 text-xs text-gray-500">
                            <div class="flex items-center gap-1">
                                <i class="fas fa-lock text-green-600"></i>
                                <span>Paiement sécurisé</span>
                            </div>
                            <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                            <div class="flex items-center gap-1">
                                <i class="fas fa-shield-alt text-blue-600"></i>
                                <span>Protection des données</span>
                            </div>
                            <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                            <div class="flex items-center gap-1">
                                <i class="fas fa-clock text-gray-500"></i>
                                <span>Paiement 3D Secure</span>
                            </div>
                        </div>
                    </div>

                    <!-- Accepted Cards -->
                    <div class="mt-4 pt-3 border-t border-gray-100">
                        <div class="flex items-center justify-center gap-2">
                            <i class="fab fa-cc-visa text-2xl text-gray-400 hover:text-blue-600 transition-colors duration-200"></i>
                            <i class="fab fa-cc-mastercard text-2xl text-gray-400 hover:text-orange-500 transition-colors duration-200"></i>
                            <i class="fab fa-cc-amex text-2xl text-gray-400 hover:text-blue-500 transition-colors duration-200"></i>
                            <i class="fab fa-cc-paypal text-2xl text-gray-400 hover:text-blue-400 transition-colors duration-200"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="lg:col-span-2">
            <form action="{{ route('client.panier.paiement.process') }}" method="POST" id="paiementForm">
                @csrf
                
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-md">
                                <i class="fas fa-credit-card text-white"></i>
                            </div>
                            <h2 class="text-lg font-bold text-gray-800">Moyen de paiement</h2>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <!-- Payment Method Selection -->
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Choisissez votre méthode de paiement</label>
                            <div class="relative">
                                <select name="methode_paiement" id="methode_paiement" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none bg-white">
                                    <option value="carte" {{ old('methode_paiement', 'carte') == 'carte' ? 'selected' : '' }}>
                                        💳 Carte bancaire
                                    </option>
                                    <option value="paypal" {{ old('methode_paiement') == 'paypal' ? 'selected' : '' }}>
                                        🅿️ PayPal
                                    </option>
                                    <option value="virement" {{ old('methode_paiement') == 'virement' ? 'selected' : '' }}>
                                        🏦 Virement bancaire
                                    </option>
                                    <option value="especes" {{ old('methode_paiement') == 'especes' ? 'selected' : '' }}>
                                        💵 Espèces
                                    </option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                            @error('methode_paiement')
                                <p class="text-red-500 text-xs mt-2 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Carte Bancaire Form - Enhanced with realistic card -->
                        <div id="carte_block" class="space-y-4 transition-all duration-300">
                            <!-- Virtual Card Preview -->
                            <div class="mb-6 perspective-1000">
                                <div class="card-preview bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-5 shadow-2xl transform transition-all duration-500 hover:scale-105 hover:rotate-y-10" style="min-height: 200px;">
                                    <div class="flex justify-between items-start">
                                        <div class="text-white">
                                            <i class="fas fa-microchip text-3xl opacity-80"></i>
                                        </div>
                                        <div class="text-right">
                                            <i class="fab fa-cc-visa text-4xl text-white opacity-80" id="cardBrandIcon"></i>
                                        </div>
                                    </div>
                                    <div class="mt-6">
                                        <p class="text-white text-lg tracking-wider font-mono" id="cardNumberPreview">**** **** **** ****</p>
                                    </div>
                                    <div class="flex justify-between mt-4">
                                        <div>
                                            <p class="text-gray-400 text-xs uppercase tracking-wider">Valable jusqu'au</p>
                                            <p class="text-white font-mono text-sm" id="cardExpiryPreview">MM/YY</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-gray-400 text-xs uppercase tracking-wider">Titulaire</p>
                                            <p class="text-white text-sm uppercase" id="cardHolderPreview">{{ auth()->user()->prenom ?? 'NOM' }} {{ auth()->user()->nom ?? 'PRENOM' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 mb-4 border border-blue-100">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-shield-alt text-blue-600 animate-pulse"></i>
                                    <p class="text-sm text-blue-800">Vos informations bancaires sont cryptées et sécurisées (SSL 256 bits)</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Numéro de carte</label>
                                    <div class="relative group">
                                        <i class="fas fa-credit-card absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                                        <input type="text" name="carte_numero" id="cardNumber" value="{{ old('carte_numero') }}" 
                                               class="pl-10 w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 group-hover:border-blue-300"
                                               placeholder="4242 4242 4242 4242" maxlength="19">
                                    </div>
                                    @error('carte_numero')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Date d'expiration</label>
                                    <div class="relative group">
                                        <i class="fas fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                                        <input type="text" name="carte_expiration" id="cardExpiry" value="{{ old('carte_expiration') }}" 
                                               class="pl-10 w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 group-hover:border-blue-300"
                                               placeholder="MM/YY" maxlength="5">
                                    </div>
                                    @error('carte_expiration')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">CVV/CVC</label>
                                    <div class="relative group">
                                        <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                                        <input type="password" name="carte_cvv" id="cardCVV" value="{{ old('carte_cvv') }}" 
                                               class="pl-10 w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 group-hover:border-blue-300"
                                               placeholder="123" maxlength="4">
                                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 cursor-help group/cvv-tooltip">
                                            <i class="fas fa-question-circle text-gray-400 hover:text-blue-500 transition-colors duration-200"></i>
                                            <div class="absolute bottom-full right-0 mb-2 hidden group-hover/cvv-tooltip:block z-10">
                                                <div class="bg-gray-800 text-white text-xs rounded-lg p-2 whitespace-nowrap">
                                                    Les 3 chiffres au dos de votre carte
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @error('carte_cvv')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom du titulaire</label>
                                    <div class="relative group">
                                        <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                                        <input type="text" name="carte_titulaire" id="cardHolder" value="{{ old('carte_titulaire', auth()->user()->prenom . ' ' . auth()->user()->nom) }}" 
                                               class="pl-10 w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 group-hover:border-blue-300"
                                               placeholder="Nom sur la carte">
                                    </div>
                                    @error('carte_titulaire')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- PayPal Form -->
                        <div id="paypal_block" class="hidden space-y-4 transition-all duration-300">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 border border-blue-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                        <i class="fab fa-paypal text-2xl text-white"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-blue-900">Paiement sécurisé avec PayPal</p>
                                        <p class="text-xs text-blue-700">Vous serez redirigé vers PayPal pour finaliser votre paiement</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email PayPal</label>
                                <div class="relative group">
                                    <i class="fab fa-paypal absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                                    <input type="email" name="paypal_email" value="{{ old('paypal_email', auth()->user()->email) }}" 
                                           class="pl-10 w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 group-hover:border-blue-300"
                                           placeholder="votre-email@paypal.com">
                                </div>
                                @error('paypal_email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Virement Form -->
                        <div id="virement_block" class="hidden space-y-4 transition-all duration-300">
                            <div class="bg-gradient-to-r from-amber-50 to-yellow-50 rounded-xl p-5 border border-amber-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                                        <i class="fas fa-university text-2xl text-white"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-amber-900">Paiement par virement bancaire</p>
                                        <p class="text-xs text-amber-700">Après validation, vous recevrez les coordonnées bancaires par email</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Référence du virement (optionnel)</label>
                                <div class="relative group">
                                    <i class="fas fa-hashtag absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 group-hover:text-amber-500 transition-colors duration-200"></i>
                                    <input type="text" name="virement_reference" value="{{ old('virement_reference') }}" 
                                           class="pl-10 w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 group-hover:border-amber-300"
                                           placeholder="Référence du virement bancaire">
                                </div>
                                @error('virement_reference')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Espèces Form -->
                        <div id="especes_block" class="hidden space-y-4 transition-all duration-300">
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-5 border border-green-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                                        <i class="fas fa-money-bill-wave text-2xl text-white"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-green-900">Paiement en espèces</p>
                                        <p class="text-xs text-green-700">Paiement en espèces à retirer sur place</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email de confirmation</label>
                                <div class="relative group">
                                    <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 group-hover:text-green-500 transition-colors duration-200"></i>
                                    <input type="email" name="especes_email" value="{{ old('especes_email', auth()->user()->email) }}" 
                                           class="pl-10 w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 group-hover:border-green-300"
                                           placeholder="Email pour recevoir la confirmation">
                                </div>
                                @error('especes_email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <button type="submit" 
                                    id="submitButton"
                                    class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-[1.02] active:scale-95">
                                <i class="fas fa-check-circle"></i>
                                Confirmer le paiement
                                <span class="text-lg font-bold ml-2">{{ number_format($montantTotal, 2, ',', ' ') }} DH</span>
                            </button>
                            <div class="flex items-center justify-center gap-4 mt-4">
                                <p class="text-center text-xs text-gray-500">
                                    En cliquant sur "Confirmer le paiement", vous acceptez nos 
                                    <a href="#" class="text-blue-600 hover:underline font-medium">conditions générales de vente</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    .animate-slide-down {
        animation: slideDown 0.3s ease-out;
    }
    
    .animate-shake {
        animation: shake 0.3s ease-in-out;
    }
    
    .perspective-1000 {
        perspective: 1000px;
    }
    
    .hover\:rotate-y-10:hover {
        transform: rotateY(10deg);
    }
    
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    /* Card Preview Styles */
    .card-preview {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        box-shadow: 0 20px 35px -10px rgba(0,0,0,0.3);
    }
    
    /* Loading state for button */
    .btn-loading {
        opacity: 0.7;
        cursor: not-allowed;
    }
    
    .btn-loading i {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>

@push('scripts')
<script>
    // Payment method selection
    const methodeSelects = document.querySelectorAll('input[name="methode_paiement"]');
    const blocks = {
        'carte': document.getElementById('carte_block'),
        'paypal': document.getElementById('paypal_block'),
        'virement': document.getElementById('virement_block'),
        'especes': document.getElementById('especes_block'),
    };

    function updatePaymentBlocks() {
        const selected = document.querySelector('input[name="methode_paiement"]:checked').value;
        Object.keys(blocks).forEach(key => {
            if (blocks[key]) {
                if (key === selected) {
                    blocks[key].classList.remove('hidden');
                    blocks[key].classList.add('block');
                    setTimeout(() => {
                        blocks[key].style.opacity = '1';
                    }, 10);
                } else {
                    blocks[key].style.opacity = '0';
                    blocks[key].classList.add('hidden');
                    blocks[key].classList.remove('block');
                }
            }
        });
    }

    methodeSelects.forEach(radio => {
        radio.addEventListener('change', updatePaymentBlocks);
    });

    // Initialize on page load
    updatePaymentBlocks();

    // Format card number with brand detection
    const cardNumberInput = document.querySelector('input[name="carte_numero"]');
    const cardBrandIcon = document.getElementById('cardBrandIcon');
    const cardNumberPreview = document.getElementById('cardNumberPreview');
    
    function detectCardBrand(number) {
        const cleaned = number.replace(/\s/g, '');
        if (cleaned.startsWith('4')) return { brand: 'visa', icon: 'fab fa-cc-visa', color: 'text-blue-400' };
        if (cleaned.startsWith('5')) return { brand: 'mastercard', icon: 'fab fa-cc-mastercard', color: 'text-orange-400' };
        if (cleaned.startsWith('3')) return { brand: 'amex', icon: 'fab fa-cc-amex', color: 'text-blue-300' };
        return { brand: 'unknown', icon: 'fab fa-cc-visa', color: 'text-white' };
    }
    
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            if (value.length > 16) value = value.slice(0, 16);
            
            // Detect card brand
            const brand = detectCardBrand(value);
            if (cardBrandIcon) {
                cardBrandIcon.className = brand.icon + ' text-4xl opacity-80';
            }
            
            // Format display
            let formatted = value.replace(/(\d{4})/g, '$1 ').trim();
            e.target.value = formatted;
            
            // Update preview
            if (cardNumberPreview) {
                let displayValue = formatted || '**** **** **** ****';
                cardNumberPreview.textContent = displayValue;
            }
        });
    }

    // Format expiration date
    const expirationInput = document.querySelector('input[name="carte_expiration"]');
    const cardExpiryPreview = document.getElementById('cardExpiryPreview');
    
    if (expirationInput) {
        expirationInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\//g, '');
            if (value.length > 4) value = value.slice(0, 4);
            if (value.length >= 2) {
                value = value.slice(0, 2) + '/' + value.slice(2);
            }
            e.target.value = value;
            
            if (cardExpiryPreview) {
                cardExpiryPreview.textContent = value || 'MM/YY';
            }
        });
    }

    // Format CVV
    const cvvInput = document.querySelector('input[name="carte_cvv"]');
    if (cvvInput) {
        cvvInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 4) value = value.slice(0, 4);
            e.target.value = value;
        });
    }
    
    // Update card holder preview
    const cardHolderInput = document.querySelector('input[name="carte_titulaire"]');
    const cardHolderPreview = document.getElementById('cardHolderPreview');
    
    if (cardHolderInput && cardHolderPreview) {
        cardHolderInput.addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase();
            cardHolderPreview.textContent = value || 'NOM PRENOM';
        });
    }
    
    // Form submission with loading state
    const form = document.getElementById('paiementForm');
    const submitBtn = document.getElementById('submitButton');
    
    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            const selectedMethod = document.querySelector('input[name="methode_paiement"]:checked').value;
            
            // Validation based on selected method
            if (selectedMethod === 'carte') {
                const cardNumber = document.querySelector('input[name="carte_numero"]').value.replace(/\s/g, '');
                const cardExpiry = document.querySelector('input[name="carte_expiration"]').value;
                const cardCVV = document.querySelector('input[name="carte_cvv"]').value;
                
                if (cardNumber.length !== 16) {
                    e.preventDefault();
                    alert('Veuillez saisir un numéro de carte valide (16 chiffres)');
                    return false;
                }
                
                if (!/^\d{2}\/\d{2}$/.test(cardExpiry)) {
                    e.preventDefault();
                    alert('Veuillez saisir une date d\'expiration valide (MM/YY)');
                    return false;
                }
                
                if (cardCVV.length < 3) {
                    e.preventDefault();
                    alert('Veuillez saisir un CVV valide (3 ou 4 chiffres)');
                    return false;
                }
            }
            
            // Add loading state to button
            submitBtn.classList.add('btn-loading');
            const originalHTML = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner"></i> Traitement en cours...';
            submitBtn.disabled = true;
            
            // Re-enable after 30 seconds in case of network issues
            setTimeout(() => {
                if (submitBtn.disabled) {
                    submitBtn.classList.remove('btn-loading');
                    submitBtn.innerHTML = originalHTML;
                    submitBtn.disabled = false;
                }
            }, 30000);
        });
    }
</script>
@endpush
@endsection