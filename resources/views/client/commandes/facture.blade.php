@extends('client.layouts.master')

@section('title', 'Facture commande #' . $commande->id)
@section('page-title', 'Facture')
@section('page-description', 'Commande #' . $commande->id)

@push('styles')
<style>
    /* Prevent horizontal scroll on body */
    body, html {
        max-width: 100%;
        overflow-x: hidden;
    }
    
    /* Table container - ensure it fits viewport */
    .table-container {
        max-width: 100%;
        overflow-x: auto;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Force table to fit container */
    table {
        width: 100%;
        min-width: 600px;
        table-layout: fixed;
    }
    
    table th, table td {
        word-wrap: break-word;
        overflow-wrap: break-word;
        padding: 0.75rem;
    }
    
    /* Sticky table header */
    thead.sticky {
        position: sticky;
        top: 0;
        background: #f3f4f6;
        z-index: 10;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* Sticky table footer */
    tfoot.sticky {
        position: sticky;
        bottom: 0;
        background: #f9fafb;
        z-index: 10;
        box-shadow: 0 -2px 4px rgba(0,0,0,0.1);
    }
    
    /* Ensure table doesn't push content */
    .table-container table {
        margin: 0;
    }
    
    /* Custom scrollbar for table container */
    .table-container::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    .table-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .table-container::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    
    .table-container::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
@endpush

@push('styles')
<style>
    /* Prevent horizontal scroll on body */
    body, html {
        max-width: 100%;
        overflow-x: hidden;
    }
    
    /* Table container - ensure it fits viewport */
    .table-container {
        max-width: 100%;
        overflow-x: auto;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Force table to fit container */
    table {
        width: 100%;
        min-width: 600px;
        table-layout: fixed;
    }
    
    table th, table td {
        word-wrap: break-word;
        overflow-wrap: break-word;
        padding: 0.75rem;
    }
    
    /* Sticky table header */
    thead.sticky {
        position: sticky;
        top: 0;
        background: #f3f4f6;
        z-index: 10;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* Sticky table footer */
    tfoot.sticky {
        position: sticky;
        bottom: 0;
        background: #f9fafb;
        z-index: 10;
        box-shadow: 0 -2px 4px rgba(0,0,0,0.1);
    }
    
    /* Ensure table doesn't push content */
    .table-container table {
        margin: 0;
    }
    
    /* Custom scrollbar for table container */
    .table-container::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    .table-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .table-container::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    
    .table-container::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Actions -->
    <div class="flex flex-wrap justify-between gap-3">
        <a href="{{ route('client.commandes.show', $commande->id) }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 border-2 border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-all duration-200 font-semibold group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            Retour à la commande
        </a>
        
        <div class="flex gap-3">
            <button onclick="window.print()" 
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                <i class="fas fa-print"></i>
                Imprimer
            </button>
            <button onclick="downloadPDF()" 
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                <i class="fas fa-download"></i>
                Télécharger PDF
            </button>
            <button onclick="sendByEmail()" 
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                <i class="fas fa-envelope"></i>
                Envoyer par email
            </button>
        </div>
    </div>

    <!-- Invoice Card -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden" id="invoice">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mt-32 -mr-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -mb-24 -ml-24"></div>
            <div class="relative z-10">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <i class="fas fa-file-invoice text-white text-2xl"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-white">Facture</h1>
                                <p class="text-blue-100 text-sm">Commande #{{ $commande->id }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                            <p class="text-white/80 text-xs uppercase tracking-wide">Date d'émission</p>
                            <p class="text-white font-bold text-xl">{{ \Carbon\Carbon::parse($commande->date)->format('d/m/Y') }}</p>
                            <p class="text-blue-200 text-xs">{{ \Carbon\Carbon::parse($commande->date)->format('H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-8">
            <!-- Company & Client Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Company Info -->
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-building text-blue-600 text-xs"></i>
                        </div>
                        <h2 class="font-bold text-gray-800 uppercase text-xs tracking-wider">Informations société</h2>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <p class="font-bold text-gray-800 text-lg">TicketFlow</p>
                        <p class="text-sm text-gray-600 mt-1">123 Avenue Mohammed V</p>
                        <p class="text-sm text-gray-600">Casablanca, Maroc</p>
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <p class="text-sm text-gray-600"><i class="fas fa-envelope mr-2 text-blue-500"></i> contact@ticketflow.ma</p>
                            <p class="text-sm text-gray-600 mt-1"><i class="fas fa-phone mr-2 text-blue-500"></i> +212 522 123 456</p>
                        </div>
                    </div>
                </div>
                
                <!-- Client Info -->
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-6 h-6 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user text-green-600 text-xs"></i>
                        </div>
                        <h2 class="font-bold text-gray-800 uppercase text-xs tracking-wider">Facturé à</h2>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <p class="font-bold text-gray-800 text-lg">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ auth()->user()->email }}</p>
                        @if(auth()->user()->telephone)
                            <p class="text-sm text-gray-600 mt-1"><i class="fas fa-phone mr-2 text-gray-400"></i> {{ auth()->user()->telephone }}</p>
                        @endif
                        @if(auth()->user()->adresse)
                            <p class="text-sm text-gray-600 mt-1"><i class="fas fa-map-marker-alt mr-2 text-gray-400"></i> {{ auth()->user()->adresse }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 mb-8 border border-blue-100">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Statut de la commande</p>
                        @if($commande->statut == 'Confirmée')
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-100 text-green-700 rounded-xl text-sm font-semibold">
                                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                Confirmée
                            </span>
                        @elseif($commande->statut == 'En attente')
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-xl text-sm font-semibold">
                                <i class="fas fa-clock text-xs"></i>
                                En attente
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-red-100 text-red-700 rounded-xl text-sm font-semibold">
                                <i class="fas fa-times-circle text-xs"></i>
                                Annulée
                            </span>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Méthode de paiement</p>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-credit-card text-blue-500"></i>
                            <p class="font-semibold text-gray-800">
                                @if($commande->paiement)
                                    {{ $commande->paiement->prestataire }}
                                @else
                                    Non spécifié
                                @endif
                            </p>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Référence paiement</p>
                        <p class="font-mono text-sm bg-white px-3 py-1.5 rounded-lg inline-block">
                            @if($commande->paiement && $commande->paiement->reference)
                                {{ $commande->paiement->reference }}
                            @else
                                ---
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="mb-8">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-ticket-alt text-green-600"></i>
                    </div>
                    <h2 class="font-bold text-gray-800 uppercase text-sm tracking-wider">Détails des billets</h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-3 px-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider rounded-l-lg">Événement</th>
                                <th class="py-3 px-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Type</th>
                                <th class="py-3 px-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Quantité</th>
                                <th class="py-3 px-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Prix unitaire</th>
                                <th class="py-3 px-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider rounded-r-lg">Sous-total</th>
                             </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @php
                                $grouped = $commande->billets->groupBy(function($billet) {
                                    return $billet->evenement_id . '_' . ($billet->type ?? 'Standard');
                                });
                            @endphp
                            @foreach($grouped as $key => $billetsParType)
                                @php $billet = $billetsParType->first(); @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-3">
                                        <div class="font-semibold text-gray-800 text-sm">{{ $billet->evenement->titre }}</div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-calendar-alt mr-1 text-blue-500"></i>
                                            {{ \Carbon\Carbon::parse($billet->evenement->date)->format('d/m/Y H:i') }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            <i class="fas fa-map-marker-alt mr-1 text-red-500"></i>
                                            {{ $billet->evenement->lieu->nom ?? 'Lieu non défini' }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-semibold">
                                            <i class="fas fa-tag"></i>
                                            {{ $billet->type ?? 'Standard' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <span class="inline-flex items-center justify-center w-6 h-6 bg-gray-100 rounded text-gray-700 font-bold text-xs">
                                            {{ $billetsParType->count() }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-right font-medium text-gray-700 text-sm">
                                        {{ number_format($billet->prix, 2) }} DH
                                    </td>
                                    <td class="py-3 px-3 text-right font-bold text-gray-800 text-sm">
                                        {{ number_format($billetsParType->sum('prix'), 2) }} DH
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-t-2 border-gray-200 bg-gray-50 sticky bottom-0">
                            <tr>
                                <td colspan="4" class="py-3 px-3 text-right font-bold text-gray-800 text-sm">Total TTC</td>
                                <td class="py-3 px-3 text-right">
                                    <span class="text-xl font-bold text-blue-600">{{ number_format($commande->montant_total, 2) }} DH</span>
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
                            <i class="fas fa-info-circle text-blue-500 text-sm"></i>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Notes</p>
                        </div>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Cette facture est générée automatiquement. Pour toute question, veuillez contacter notre service client 
                            à <a href="mailto:support@ticketflow.ma" class="text-blue-600 hover:underline">support@ticketflow.ma</a>
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="flex items-center gap-2 justify-end mb-2">
                            <i class="fas fa-check-circle text-green-500 text-sm"></i>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Paiement sécurisé</p>
                        </div>
                        <div class="flex items-center justify-end gap-2 mt-2">
                            <img src="https://cdn-icons-png.flaticon.com/512/196/196578.png" class="h-6 w-auto opacity-50" alt="Visa">
                            <img src="https://cdn-icons-png.flaticon.com/512/196/196561.png" class="h-6 w-auto opacity-50" alt="Mastercard">
                            <img src="https://cdn-icons-png.flaticon.com/512/174/174861.png" class="h-6 w-auto opacity-50" alt="PayPal">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-center text-xs text-gray-500 gap-2">
                <p>© {{ date('Y') }} TicketFlow - Plateforme de billetterie en ligne</p>
                <p>Facture générée le {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Action Buttons Bottom -->
    <div class="flex justify-center gap-4">
        <a href="{{ route('client.billets.event', $commande->billets->first()->evenement->id ?? '#') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
            <i class="fas fa-ticket-alt"></i>
            Voir mes billets
        </a>
        <a href="{{ route('client.commandes.index') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 border-2 border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-all duration-200 font-semibold">
            <i class="fas fa-history"></i>
            Toutes mes commandes
        </a>
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
        button, .btn, .actions, .flex.justify-between, .flex.justify-center {
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
    // Auto-hide action buttons when printing
    window.onbeforeprint = function() {
        document.querySelectorAll('button, .no-print, .flex.justify-between > a, .flex.justify-center').forEach(el => {
            el.classList.add('no-print');
        });
    };
    
    window.onafterprint = function() {
        document.querySelectorAll('.no-print').forEach(el => {
            el.classList.remove('no-print');
        });
    };
    
    function downloadPDF() {
        alert('Fonctionnalité de téléchargement PDF en cours de développement.\nEn attendant, utilisez "Imprimer" > "Enregistrer en tant que PDF".');
    }
    
    function sendByEmail() {
        if(confirm('Envoyer cette facture par email ?')) {
            fetch('{{ route("client.commandes.send-invoice", $commande->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Facture envoyée avec succès à {{ auth()->user()->email }} !');
                } else {
                    alert('Erreur lors de l\'envoi de la facture');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue');
            });
        }
    }
</script>
@endpush
@endsection