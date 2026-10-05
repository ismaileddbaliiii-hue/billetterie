@extends('client.layouts.master')

@section('title', 'Commande #' . $commande->id)
@section('page-title', 'Détails de la commande')
@section('page-description', 'Commande #' . $commande->id)

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('client.commandes.index') }}" 
           class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 transition-colors group">
            <i class="fas fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
            <span class="font-medium">Retour à mes commandes</span>
        </a>
        
        
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Header Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-receipt text-white/80 text-xl"></i>
                                <h1 class="text-2xl font-bold text-white">Commande #{{ $commande->id }}</h1>
                            </div>
                            <div class="flex flex-wrap items-center gap-3 text-blue-100 text-sm">
                                <div class="flex items-center gap-1">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>{{ \Carbon\Carbon::parse($commande->date)->format('d/m/Y') }}</span>
                                </div>
                                <div class="w-1 h-1 bg-blue-300 rounded-full"></div>
                                <div class="flex items-center gap-1">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ \Carbon\Carbon::parse($commande->date)->format('H:i') }}</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            @if($commande->statut == 'Confirmée')
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-xl text-sm font-semibold shadow-lg">
                                    <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                                    Confirmée
                                </span>
                            @elseif($commande->statut == 'En attente')
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-500 text-white rounded-xl text-sm font-semibold shadow-lg">
                                    <i class="fas fa-clock"></i>
                                    En attente
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 text-white rounded-xl text-sm font-semibold shadow-lg">
                                    <i class="fas fa-times-circle"></i>
                                    Annulée
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tickets Section -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-ticket-alt text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">Billets achetés</h2>
                            <p class="text-xs text-gray-500">{{ $commande->billets->count() }} billet(s)</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    @php
                        $billetsParEvenement = $commande->billets->groupBy('evenement_id');
                    @endphp
                    
                    @foreach($billetsParEvenement as $evenementId => $billets)
                        @php $evenement = $billets->first()->evenement; @endphp
                        <div class="border border-gray-200 rounded-xl overflow-hidden mb-6 last:mb-0 hover:shadow-md transition-all duration-300">
                            <!-- Event Header -->
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-4 border-b border-gray-200">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-lg text-gray-800">{{ $evenement->titre }}</h3>
                                        <div class="flex flex-wrap items-center gap-3 mt-2 text-sm text-gray-600">
                                            <div class="flex items-center gap-1">
                                                <i class="fas fa-calendar-alt text-blue-500"></i>
                                                <span>{{ \Carbon\Carbon::parse($evenement->date)->format('d/m/Y H:i') }}</span>
                                            </div>
                                            <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                            <div class="flex items-center gap-1">
                                                <i class="fas fa-map-marker-alt text-red-500"></i>
                                                <span>{{ $evenement->lieu->nom ?? 'Lieu non défini' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @if($commande->statut == 'Confirmée')
                                        <a href="{{ route('client.billets.event', $evenement->id) }}" 
                                           class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                            <i class="fas fa-download"></i>
                                            Tous les billets
                                        </a>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Tickets Table -->
                            <div class="p-4">
                                @php
                                    $groupedByType = $billets->groupBy(function ($billet) {
                                        return $billet->typeBillet->nom ?? $billet->type ?? 'Standard';
                                    });
                                @endphp
                                
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead>
                                            <tr class="border-b border-gray-200">
                                                <th class="text-left py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                                                <th class="text-left py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Prix unitaire</th>
                                                <th class="text-left py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Quantité</th>
                                                <th class="text-right py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Sous-total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach($groupedByType as $type => $billetsType)
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    <td class="py-3">
                                                        <div class="flex items-center gap-2">
                                                            <i class="fas fa-ticket text-blue-500 text-sm"></i>
                                                            <span class="font-medium text-gray-700">{{ $type }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="py-3 text-gray-600">{{ number_format($billetsType->first()->prix, 2) }} DH</td>
                                                    <td class="py-3">
                                                        <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 rounded-lg text-gray-700 font-semibold">
                                                            {{ $billetsType->count() }}
                                                        </span>
                                                    </td>
                                                    <td class="py-3 text-right font-semibold text-gray-800">{{ number_format($billetsType->sum('prix'), 2) }} DH</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="border-t border-gray-200">
                                            <tr class="bg-gray-50">
                                                <td colspan="3" class="py-3 text-right font-semibold text-gray-700">Total pour cet événement</td>
                                                <td class="py-3 text-right font-bold text-blue-600">{{ number_format($billets->sum('prix'), 2) }} DH</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                
                                @if($commande->statut == 'Confirmée')
                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        <div class="grid grid-cols-2 gap-3">
                                            @foreach($billets as $billet)
                                                <a href="{{ route('client.billets.show', $billet->id) }}" 
                                                   class="flex items-center justify-between p-3 bg-gray-50 hover:bg-blue-50 rounded-lg transition-colors group">
                                                    <div class="flex items-center gap-2">
                                                        <i class="fas fa-qrcode text-blue-500 text-lg"></i>
                                                        <div>
                                                            <p class="text-xs text-gray-500">Billet</p>
                                                            <p class="text-sm font-medium text-gray-700">#{{ $billet->id }}</p>
                                                        </div>
                                                    </div>
                                                    <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Order Summary Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Summary Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden sticky top-24">
                <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-receipt text-white"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">Résumé de la commande</h2>
                    </div>
                </div>
                
                <div class="p-6">
                    <!-- Totals -->
                    <div class="space-y-3">
                        <div class="flex justify-between text-gray-600">
                            <span>Sous-total</span>
                            <span>{{ number_format($commande->montant_total, 2) }} DH</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Frais de service</span>
                            <span class="text-green-600">Gratuit</span>
                        </div>
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <div class="flex justify-between items-baseline">
                                <span class="text-lg font-bold text-gray-800">Total</span>
                                <div class="text-right">
                                    <span class="text-2xl font-bold text-blue-600">{{ number_format($commande->montant_total, 2) }} DH</span>
                                    <p class="text-xs text-gray-500 mt-1">TTC</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($commande->statut == 'Confirmée')
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <div class="bg-green-50 rounded-xl p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span class="font-semibold text-green-800">Paiement confirmé</span>
                                </div>
                                <p class="text-xs text-green-700">Votre commande a été traitée avec succès</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Payment Information -->
            @if($commande->paiement)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-credit-card text-white"></i>
                            </div>
                            <h2 class="text-lg font-bold text-gray-800">Informations de paiement</h2>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-600">Prestataire</span>
                                <span class="font-medium text-gray-800">{{ $commande->paiement->prestataire }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-600">Date de paiement</span>
                                <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($commande->paiement->date)->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <span class="text-gray-600">Référence</span>
                                <span class="font-mono text-sm text-gray-800">{{ $commande->paiement->reference }}</span>
                            </div>
                        </div>
                        
                        @if($commande->paiement->statut == 'Confirmé')
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <div class="flex items-center justify-center gap-2 text-green-600">
                                    <i class="fas fa-check-circle"></i>
                                    <span class="text-sm font-medium">Paiement validé</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            
            <!-- Help Card -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-headset text-white"></i>
                    </div>
                    <h3 class="font-bold text-gray-800">Besoin d'aide ?</h3>
                </div>
                <p class="text-sm text-gray-600 mb-4">
                    Une question sur votre commande ? Contactez notre service client.
                </p>
                <a href="#" class="inline-flex items-center gap-2 text-blue-600 font-medium text-sm hover:text-blue-700">
                    Contacter le support
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection