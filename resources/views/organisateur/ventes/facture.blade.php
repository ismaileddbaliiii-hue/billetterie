@extends('organisateur.layouts.master')

@section('title', 'Facture commande #' . $commande->id)
@section('page-title', 'Facture')
@section('page-description', 'Facture de la commande #' . $commande->id)

@section('content')
<div class="space-y-6">
    <!-- Actions -->
    <div class="flex flex-wrap justify-between items-center gap-4 no-print">
        
        <div class="flex gap-3">
            <button onclick="window.print()" 
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Imprimer
            </button>
            <button onclick="downloadPDF()" 
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Télécharger PDF
            </button>
        </div>
    </div>

    <!-- Invoice Card -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden" id="invoice">
        <!-- Header with Gradient -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mt-32 -mr-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -mb-24 -ml-24"></div>
            <div class="relative z-10">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-white">FACTURE</h1>
                                <p class="text-blue-100 text-sm mt-1">Document officiel</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-5 py-3 border border-white/20">
                            <p class="text-white/70 text-xs uppercase tracking-wider">N° Facture</p>
                            <p class="text-white font-bold text-xl">INV-{{ str_pad($commande->id, 6, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-blue-200 text-xs mt-1">Date: {{ \Carbon\Carbon::parse($commande->date)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-8">
            <!-- Company & Client Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Organisateur Info -->
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h2 class="font-bold text-gray-800 uppercase text-xs tracking-wider">Émetteur</h2>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <p class="font-bold text-gray-800 text-lg">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</p>
                        <p class="text-sm text-gray-600 mt-1 flex items-center gap-2">
                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ Auth::user()->email }}
                        </p>
                        @if(Auth::user()->telephone)
                            <p class="text-sm text-gray-600 mt-1 flex items-center gap-2">
                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ Auth::user()->telephone }}
                            </p>
                        @endif
                    </div>
                </div>
                
                <!-- Client Info -->
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h2 class="font-bold text-gray-800 uppercase text-xs tracking-wider">Client</h2>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <p class="font-bold text-gray-800 text-lg">{{ $commande->client->prenom ?? 'N/A' }} {{ $commande->client->nom ?? '' }}</p>
                        <p class="text-sm text-gray-600 mt-1 flex items-center gap-2">
                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ $commande->client->email ?? 'N/A' }}
                        </p>
                        @if($commande->client->telephone)
                            <p class="text-sm text-gray-600 mt-1 flex items-center gap-2">
                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $commande->client->telephone }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment & Event Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <h4 class="text-sm font-bold text-gray-700">Informations de paiement</h4>
                    </div>
                    <p class="text-sm text-gray-600">Prestataire: <strong class="text-gray-800">{{ $commande->paiement->prestataire ?? 'Non spécifié' }}</strong></p>
                    <p class="text-sm text-gray-600 mt-1">Référence: <span class="font-mono text-gray-800">{{ $commande->paiement->reference ?? '---' }}</span></p>
                    @if($commande->paiement && $commande->paiement->date)
                        <p class="text-sm text-gray-600 mt-1">Date: <strong>{{ \Carbon\Carbon::parse($commande->paiement->date)->format('d/m/Y H:i') }}</strong></p>
                    @endif
                </div>
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-100">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <h4 class="text-sm font-bold text-gray-700">Événement</h4>
                    </div>
                    <p class="text-sm text-gray-600"><strong class="text-gray-800">{{ $commande->evenement->titre ?? 'N/A' }}</strong></p>
                    <p class="text-sm text-gray-600 mt-1">Date: {{ \Carbon\Carbon::parse($commande->evenement->date ?? now())->format('d/m/Y H:i') }}</p>
                    <p class="text-sm text-gray-600">Lieu: {{ $commande->evenement->lieu->nom ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Items Table -->
            <div class="mb-8">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                    </div>
                    <h2 class="font-bold text-gray-800 uppercase text-sm tracking-wider">Détails des billets</h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-3 px-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider rounded-l-lg">Désignation</th>
                                <th class="py-3 px-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Type</th>
                                <th class="py-3 px-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Qté</th>
                                <th class="py-3 px-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Prix unit.</th>
                                <th class="py-3 px-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider rounded-r-lg">Total</th>
                             </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @php 
                                $grouped = $commande->billets->groupBy(function($billet){ 
                                    return $billet->typeBillet->id ?? $billet->type; 
                                }); 
                            @endphp
                            @foreach($grouped as $key => $billets)
                                @php $billet = $billets->first(); @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-4">
                                        <div class="font-semibold text-gray-800">{{ $billet->evenement->titre ?? 'Événement' }}</div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ \Carbon\Carbon::parse($billet->evenement->date)->format('d/m/Y H:i') }}
                                        </div>
                                      </td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-semibold">
                                            {{ $billet->typeBillet->nom ?? $billet->type ?? 'Standard' }}
                                        </span>
                                      </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 rounded-lg text-gray-700 font-bold">
                                            {{ $billets->count() }}
                                        </span>
                                      </td>
                                    <td class="py-3 px-4 text-right font-medium text-gray-700">
                                        {{ number_format($billet->prix, 2, ',', ' ') }} DH
                                      </td>
                                    <td class="py-3 px-4 text-right font-bold text-gray-800">
                                        {{ number_format($billets->sum('prix'), 2, ',', ' ') }} DH
                                      </td>
                                  </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-t-2 border-gray-200">
                            <tr class="bg-gray-50">
                                <td colspan="4" class="py-4 px-4 text-right font-bold text-gray-800 text-lg">Total TTC</td>
                                <td class="py-4 px-4 text-right">
                                    <span class="text-2xl font-bold text-blue-600">{{ number_format($commande->montant_total, 2, ',', ' ') }} DH</span>
                                    <p class="text-xs text-gray-500 mt-1">Toutes taxes incluses</p>
                                  </td>
                              </tr>
                        </tfoot>
                      </table>
                </div>
            </div>

            <!-- Footer Notes -->
            <div class="border-t border-gray-200 pt-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Informations légales</p>
                        </div>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Cette facture fait foi de paiement. Merci de votre confiance et à bientôt lors de l'événement !
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="flex items-center gap-2 justify-end mb-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Paiement confirmé</p>
                        </div>
                        @if($commande->statut == 'Confirmée')
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-100 text-emerald-700 rounded-xl text-sm font-semibold">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                                Payé le {{ \Carbon\Carbon::parse($commande->date)->format('d/m/Y') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-center text-xs text-gray-500 gap-2">
                <p>© {{ date('Y') }} TicketFlow - Plateforme de billetterie professionnelle</p>
                <p>Facture générée le {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-wrap justify-end gap-3 no-print">
        <a href="{{ route('organisateur.ventes.show', $commande->id) }}" 
           class="inline-flex items-center gap-2 px-6 py-2.5 border-2 border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-all duration-200 font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Détails commande
        </a>
        <a href="{{ route('organisateur.ventes.index') }}" 
           class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Toutes les ventes
        </button>
    </div>
</div>

<style media="print">
    @media print {
        body * {
            visibility: hidden;
        }
        #invoice, #invoice * {
            visibility: visible;
        }
        #invoice {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            margin: 0;
            padding: 0;
            box-shadow: none;
            border-radius: 0;
        }
        .no-print {
            display: none !important;
        }
        button, .btn, .flex.justify-between, .flex.justify-end {
            display: none !important;
        }
        .bg-gradient-to-r, .gradient {
            background: #2563eb !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        table {
            page-break-inside: avoid;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        .bg-gray-50, .bg-gray-100 {
            background: #f9fafb !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>

@push('scripts')
<script>
    window.onbeforeprint = function() {
        document.querySelectorAll('.no-print').forEach(el => {
            el.style.display = 'none';
        });
    };
    
    window.onafterprint = function() {
        document.querySelectorAll('.no-print').forEach(el => {
            el.style.display = '';
        });
    };
    
    function downloadPDF() {
        window.location.href = "{{ route('organisateur.ventes.download', $commande->id) }}";
    }
</script>
@endpush
@endsection