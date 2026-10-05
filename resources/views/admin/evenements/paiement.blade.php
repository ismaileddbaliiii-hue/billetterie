{{-- resources/views/admin/evenements/paiement.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Paiement - ' . $evenement->titre)
@section('page-title', 'Paiement')
@section('page-description', $evenement->titre)

@section('page-actions')
    <a href="{{ route('admin.evenements.acheter', $evenement) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Retour
    </a>
@endsection

@section('content')
@php
    $panier = session('panier', []);
    $montantTotal = session('montant_total', 0);
    $emailSpectateur = session('email_spectateur', auth()->user()->email);
    $nomSpectateur = session('nom_spectateur', auth()->user()->prenom . ' ' . auth()->user()->nom);
@endphp

<div class="max-w-7xl mx-auto">
    <!-- Afficher les erreurs de validation -->
    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="font-medium text-red-800">Des erreurs ont été détectées :</p>
                    <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- En-tête -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-blue-50">
            <div class="flex items-center">
                <div class="h-12 w-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-xl font-bold text-gray-900">Paiement sécurisé</h2>
                    <p class="text-sm text-gray-600">Choisissez votre mode de paiement pour finaliser l'achat</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulaire de paiement -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <form action="{{ route('admin.evenements.paiement.process', $evenement) }}" method="POST" id="paiementForm">
                    @csrf
                    
                    <!-- Résumé de la commande -->
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">Résumé de la commande</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($panier as $item)
                                @php
                                    $type = \App\Models\TypeBillet::find($item['type_id']);
                                @endphp
                                <div class="flex justify-between items-center py-2">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $type->nom ?? 'Billet' }}</p>
                                        <p class="text-xs text-gray-500">Quantité: {{ $item['quantite'] }}</p>
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ number_format(($type->prix ?? 0) * $item['quantite'], 2, ',', ' ') }} DH</span>
                                </div>
                            @endforeach
                            
                            <div class="border-t pt-4 mt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-semibold text-gray-900">Total à payer</span>
                                    <span class="text-2xl font-bold text-green-600">{{ number_format($montantTotal, 2, ',', ' ') }} DH</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Méthode de paiement -->
                    <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">Méthode de paiement</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <!-- Carte bancaire -->
                            <label class="relative flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-purple-300 hover:bg-purple-50 transition-all duration-200">
                                <input type="radio" name="methode_paiement" value="carte" class="w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500" {{ old('methode_paiement', session('methode_paiement')) == 'carte' ? 'checked' : '' }}>
                                <div class="flex items-center flex-1 ml-3">
                                    <div class="flex space-x-1">
                                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none">
                                            <rect x="2" y="4" width="20" height="16" rx="2" fill="#1a1f71"/>
                                            <text x="6" y="16" fill="white" font-size="10" font-weight="bold">VISA</text>
                                        </svg>
                                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none">
                                            <rect x="2" y="4" width="20" height="16" rx="2" fill="#eb001b"/>
                                            <circle cx="12" cy="12" r="4" fill="#f79e1b"/>
                                        </svg>
                                    </div>
                                    <span class="ml-3 font-medium text-gray-900">Carte bancaire</span>
                                </div>
                            </label>
                            
                            <!-- PayPal -->
                            <label class="relative flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                                <input type="radio" name="methode_paiement" value="paypal" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" {{ old('methode_paiement', session('methode_paiement')) == 'paypal' ? 'checked' : '' }}>
                                <div class="flex items-center flex-1 ml-3">
                                    <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none">
                                        <path d="M7.5 3.5L6 12h7.5c2.5 0 4-1 4.5-3 .5-2-1-3.5-3-3.5H7.5z" fill="#003087"/>
                                        <path d="M5 20l1.5-8.5h4.5c2.5 0 4.5-1 5-3 .5-2-1-3.5-3-3.5H10l-1.5 8.5H5z" fill="#009cde"/>
                                        <path d="M11.5 8.5L10 17h3c2 0 3.5-1 4-3 .5-2-1-3.5-3-3.5h-2.5z" fill="#012169"/>
                                    </svg>
                                    <span class="ml-3 font-medium text-gray-900">PayPal</span>
                                </div>
                            </label>
                            
                            <!-- Virement bancaire -->
                            <label class="relative flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 hover:bg-orange-50 transition-all duration-200">
                                <input type="radio" name="methode_paiement" value="virement" class="w-4 h-4 text-orange-600 border-gray-300 focus:ring-orange-500" {{ old('methode_paiement', session('methode_paiement')) == 'virement' ? 'checked' : '' }}>
                                <div class="flex items-center flex-1 ml-3">
                                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    <span class="ml-3 font-medium text-gray-900">Virement bancaire</span>
                                </div>
                            </label>
                            
                            <!-- Espèces -->
                            <label class="relative flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-green-300 hover:bg-green-50 transition-all duration-200">
                                <input type="radio" name="methode_paiement" value="especes" class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500" {{ old('methode_paiement', session('methode_paiement')) == 'especes' ? 'checked' : '' }}>
                                <div class="flex items-center flex-1 ml-3">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="ml-3 font-medium text-gray-900">Espèces (paiement sur place)</span>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Formulaire carte bancaire -->
                    <div id="carteForm" class="hidden border-t border-gray-200 bg-gray-50">
                        <div class="px-6 py-4">
                            <h4 class="font-semibold text-gray-900 mb-4">Informations carte bancaire</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Numéro de carte *</label>
                                    <input type="text" name="carte_numero" value="{{ old('carte_numero') }}" placeholder="4242 4242 4242 4242"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Date d'expiration *</label>
                                        <input type="text" name="carte_expiration" value="{{ old('carte_expiration') }}" placeholder="MM/YY"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">CVV *</label>
                                        <input type="text" name="carte_cvv" value="{{ old('carte_cvv') }}" placeholder="123"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom du titulaire *</label>
                                    <input type="text" name="carte_titulaire" value="{{ old('carte_titulaire', $nomSpectateur) }}" placeholder="Nom inscrit sur la carte"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Formulaire PayPal -->
                    <div id="paypalForm" class="hidden border-t border-gray-200 bg-gray-50">
                        <div class="px-6 py-4">
                            <h4 class="font-semibold text-gray-900 mb-4">Informations PayPal</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email PayPal *</label>
                                    <input type="email" name="paypal_email" value="{{ old('paypal_email', $emailSpectateur) }}" placeholder="votre-email@paypal.com"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                </div>
                                <div class="bg-blue-50 rounded-lg p-4 text-center">
                                    <svg class="w-12 h-12 text-blue-600 mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M7.5 3.5L6 12h7.5c2.5 0 4-1 4.5-3 .5-2-1-3.5-3-3.5H7.5zM5 20l1.5-8.5h4.5c2.5 0 4.5-1 5-3 .5-2-1-3.5-3-3.5H10l-1.5 8.5H5zM11.5 8.5L10 17h3c2 0 3.5-1 4-3 .5-2-1-3.5-3-3.5h-2.5z"/>
                                    </svg>
                                    <p class="text-sm text-gray-700">Vous serez redirigé vers PayPal pour finaliser votre paiement.</p>
                                    <p class="text-xs text-gray-500 mt-2">Après confirmation, vos tickets seront générés automatiquement.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Formulaire virement bancaire -->
                    <div id="virementForm" class="hidden border-t border-gray-200 bg-gray-50">
                        <div class="px-6 py-4">
                            <h4 class="font-semibold text-gray-900 mb-4">Coordonnées bancaires</h4>
                            <div class="space-y-4">
                                <div class="bg-white rounded-lg p-4 border border-gray-200">
                                    <p class="text-sm font-medium text-gray-700 mb-3">Virement à effectuer sur le compte suivant :</p>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Banque :</span>
                                            <span class="font-medium text-gray-900">Banque Populaire</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Titulaire :</span>
                                            <span class="font-medium text-gray-900">{{ config('app.name') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">IBAN :</span>
                                            <span class="font-mono text-sm">MA64 1234 5678 9012 3456 7890 12</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">BIC :</span>
                                            <span class="font-mono text-sm">BMPAMAMCXXX</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Référence du virement *</label>
                                    <input type="text" name="virement_reference" value="{{ old('virement_reference', session('reference')) }}" readonly
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 font-mono text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email pour l'envoi de la confirmation *</label>
                                    <input type="email" name="virement_email" value="{{ old('virement_email', $emailSpectateur) }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Formulaire espèces -->
                    <div id="especesForm" class="hidden border-t border-gray-200 bg-gray-50">
                        <div class="px-6 py-4">
                            <h4 class="font-semibold text-gray-900 mb-4">Paiement sur place</h4>
                            <div class="space-y-4">
                                <div class="bg-green-50 rounded-lg p-4 text-center">
                                    <svg class="w-12 h-12 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-sm font-medium text-green-800">Vos tickets sont réservés !</p>
                                    <p class="text-xs text-green-600 mt-1">Paiement à effectuer le jour de l'événement à l'entrée.</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email pour l'envoi de la confirmation *</label>
                                    <input type="email" name="especes_email" value="{{ old('especes_email', $emailSpectateur) }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone (optionnel)</label>
                                    <input type="tel" name="especes_telephone" value="{{ old('especes_telephone') }}" placeholder="06 XX XX XX XX"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.evenements.acheter', $evenement) }}" 
                               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200 font-medium">
                                Annuler
                            </a>
                            <button type="submit" 
                                    id="submitBtn"
                                    class="px-6 py-2 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 font-medium shadow-sm">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Confirmer le paiement
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Informations sur l'événement -->
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
                <div class="relative">
                    @if($evenement->affiche)
                        <img src="{{ Storage::url($evenement->affiche) }}" alt="{{ $evenement->titre }}" class="w-full h-40 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent"></div>
                    @else
                        <div class="w-full h-40 bg-gradient-to-r from-purple-500 to-blue-500 flex items-center justify-center">
                            <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black to-transparent">
                        <h3 class="text-white font-bold">{{ $evenement->titre }}</h3>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500">Date et heure</p>
                                <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($evenement->date)->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($evenement->lieu)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500">Lieu</p>
                                <p class="text-sm font-medium text-gray-900">{{ $evenement->lieu->nom }}, {{ $evenement->lieu->ville }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Paiement sécurisé</span>
                            <div class="flex space-x-1">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-xs text-gray-500">SSL sécurisé</span>
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
// Afficher/cacher les formulaires selon la méthode de paiement
const formatters = {
    cardNumber: function(value) {
        return value.replace(/\s/g, '').replace(/(\d{4})/g, '$1 ').trim();
    },
    cardExpiry: function(value) {
        return value.replace(/\D/g, '').replace(/(\d{2})(\d{2})/, '$1/$2').slice(0, 5);
    }
};

document.querySelectorAll('input[name="methode_paiement"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Cacher tous les formulaires
        document.getElementById('carteForm').classList.add('hidden');
        document.getElementById('paypalForm').classList.add('hidden');
        document.getElementById('virementForm').classList.add('hidden');
        document.getElementById('especesForm').classList.add('hidden');
        
        // Afficher le formulaire correspondant
        let formId;
        switch(this.value) {
            case 'carte':
                formId = 'carteForm';
                break;
            case 'paypal':
                formId = 'paypalForm';
                break;
            case 'virement':
                formId = 'virementForm';
                break;
            case 'especes':
                formId = 'especesForm';
                break;
        }
        
        const form = document.getElementById(formId);
        if (form) {
            form.classList.remove('hidden');
            form.style.opacity = '0';
            setTimeout(() => {
                form.style.transition = 'all 0.3s ease';
                form.style.opacity = '1';
            }, 10);
        }
    });
});

// Appliquer les formats sur les champs carte
const cardNumberInput = document.querySelector('input[name="carte_numero"]');
const cardExpiryInput = document.querySelector('input[name="carte_expiration"]');

if (cardNumberInput) {
    cardNumberInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '');
        if (value.length > 16) value = value.slice(0, 16);
        e.target.value = value.replace(/(\d{4})/g, '$1 ').trim();
    });
}

if (cardExpiryInput) {
    cardExpiryInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.slice(0, 2) + '/' + value.slice(2, 4);
        }
        e.target.value = value.slice(0, 5);
    });
}

// Activer le formulaire correspondant si une méthode est pré-sélectionnée
document.addEventListener('DOMContentLoaded', function() {
    const checkedRadio = document.querySelector('input[name="methode_paiement"]:checked');
    if (checkedRadio) {
        checkedRadio.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
@endsection