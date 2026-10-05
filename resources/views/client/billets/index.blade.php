@extends('client.layouts.master')

@section('title', 'Mes Billets')
@section('page-title', 'Mes Billets')
@section('page-description', 'Retrouvez tous vos billets d\'événements')

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
    table.table-fixed {
        width: 100%;
        min-width: 600px;
        table-layout: fixed;
    }
    
    table.table-fixed th, table.table-fixed td {
        word-wrap: break-word;
        overflow-wrap: break-word;
        padding: 0.5rem 0.75rem;
    }
    
    /* Sticky table header */
    thead.sticky {
        position: sticky;
        top: 0;
        background: #f9fafb;
        z-index: 10;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* Sticky bottom pagination */
    .sticky.bottom-0 {
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
<div class="space-y-6 px-2 sm:px-4">
    <!-- Header Stats - Khallihom b7al homa -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total billets</p>
                    <p class="text-2xl font-bold">{{ $totalBillets }}</p>
                </div>
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-ticket-alt text-white"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Billets valides</p>
                    <p class="text-2xl font-bold">{{ $validBillets }}</p>
                </div>
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-white"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Événements à venir</p>
                    <p class="text-2xl font-bold">{{ $evenementsAVenir }}</p>
                </div>
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <form method="GET" action="{{ route('client.billets.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                            <i class="fas fa-calendar-alt mr-1"></i> Filtrer par événement
                        </label>
                        <select name="evenement_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                            <option value="">Tous les événements</option>
                            @foreach($evenements as $evenement)
                                <option value="{{ $evenement->id }}" {{ request('evenement_id') == $evenement->id ? 'selected' : '' }}>
                                    {{ $evenement->titre }} ({{ \Carbon\Carbon::parse($evenement->date)->format('d/m/Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-md">
                            <i class="fas fa-filter mr-2"></i> Appliquer
                        </button>
                        @if(request('evenement_id'))
                            <a href="{{ route('client.billets.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors rounded-lg border border-gray-200">
                                <i class="fas fa-undo-alt"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Tickets List -->
    @if($billets->count() > 0)
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Header -->
            <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Liste des billets</h2>
                    <p class="text-sm text-gray-500">{{ $billets->total() }} billet(s) au total</p>
                </div>
                
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block">
                <!-- Pagination Top -->
                @if($billets->hasPages())
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Affichage de {{ $billets->count() }} billet(s) sur {{ $billets->total() }}
                        </div>
                        <div class="flex items-center gap-2">
                            {{-- Previous --}}
                            @if($billets->onFirstPage())
                                <span class="px-2 py-1 text-gray-400 bg-gray-100 rounded text-xs">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            @else
                                <a href="{{ $billets->previousPageUrl() }}"
                                   class="px-2 py-1 text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 text-xs transition">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            @endif

                            {{-- Page Numbers --}}
                            @php
                                $start = max(1, $billets->currentPage() - 2);
                                $end = min($billets->lastPage(), $start + 4);
                                if($end - $start < 4 && $start > 1) $start = max(1, $end - 4);
                            @endphp

                            @if($start > 1)
                                <a href="{{ $billets->url(1) }}" class="px-2 py-1 text-gray-700 hover:bg-gray-50 rounded text-xs">1</a>
                                @if($start > 2) <span class="px-1 text-gray-400">...</span> @endif
                            @endif

                            @for($i = $start; $i <= $end; $i++)
                                @if($i == $billets->currentPage())
                                    <span class="px-2 py-1 bg-blue-600 text-white rounded text-xs font-semibold">{{ $i }}</span>
                                @else
                                    <a href="{{ $billets->url($i) }}"
                                       class="px-2 py-1 text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 text-xs transition">
                                        {{ $i }}
                                    </a>
                                @endif
                            @endfor

                            @if($end < $billets->lastPage())
                                @if($end < $billets->lastPage() - 1) <span class="px-1 text-gray-400">...</span> @endif
                                <a href="{{ $billets->url($billets->lastPage()) }}" class="px-2 py-1 text-gray-700 hover:bg-gray-50 rounded text-xs">
                                    {{ $billets->lastPage() }}
                                </a>
                            @endif

                            {{-- Next --}}
                            @if($billets->hasMorePages())
                                <a href="{{ $billets->nextPageUrl() }}"
                                   class="px-2 py-1 text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 text-xs transition">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <span class="px-2 py-1 text-gray-400 bg-gray-100 rounded text-xs">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Table Container with Max Height -->
                <div class="table-container max-h-[60vh] overflow-y-auto">
                    <table class="min-w-full divide-y divide-gray-200 table-fixed">
                    <thead class="bg-gray-50 sticky top-0 z-10">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide w-1/3">Événement</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide w-1/6">Date</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide w-1/6">Type</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide w-1/6">Prix</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide w-1/6">Statut</th>
                            <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide w-1/6">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($billets as $billet)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-3 py-3">
                                    <div class="text-sm font-semibold text-gray-900 truncate" title="{{ $billet->evenement->titre }}">
                                        {{ $billet->evenement->titre }}
                                    </div>
                                    <div class="text-xs text-gray-500 truncate">
                                        {{ $billet->evenement->lieu->nom ?? 'Lieu non défini' }}
                                    </div>
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($billet->evenement->date)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-700">
                                    {{ $billet->typeBillet->nom ?? $billet->type ?? 'Standard' }}
                                </td>
                                <td class="px-3 py-3 text-sm font-semibold text-gray-800">
                                    {{ number_format($billet->prix, 2, ',', ' ') }} DH
                                </td>
                                <td class="px-3 py-3">
                                    @if($billet->statut == 'Valide')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                            <i class="fas fa-check-circle text-xs"></i> Valide
                                        </span>
                                    @elseif($billet->statut == 'Utilisé')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                            <i class="fas fa-check-double text-xs"></i> Utilisé
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                            <i class="fas fa-times-circle text-xs"></i> Annulé
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-center">
                                    @if($billet->statut == 'Valide')
                                        <div class="flex items-center justify-center gap-1">
                                            <a href="{{ route('client.billets.download', $billet->id) }}" 
                                               class="inline-flex items-center gap-1 px-2 py-1.5 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-xs">
                                                <i class="fas fa-download"></i> PDF
                                            </a>
                                            <button onclick="showQRCode('{{ $billet->code_unique }}', '{{ $billet->id }}', '{{ addslashes($billet->evenement->titre) }}')" 
                                                    class="inline-flex items-center gap-1 px-2 py-1.5 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition text-xs">
                                                <i class="fas fa-qrcode"></i> QR
                                            </button>
                                        </div>
                                    @elseif($billet->statut == 'Utilisé')
                                        <span class="text-xs text-blue-600 font-medium">Entrée validée</span>
                                    @else
                                        <span class="text-xs text-gray-400">Non disponible</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>

                <!-- Pagination Bottom -->
                @if($billets->hasPages())
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sticky bottom-0">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Page {{ $billets->currentPage() }} sur {{ $billets->lastPage() }}
                        </div>
                        <div class="flex items-center gap-2">
                            {{-- Previous --}}
                            @if($billets->onFirstPage())
                                <span class="px-2 py-1 text-gray-400 bg-gray-100 rounded text-xs">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            @else
                                <a href="{{ $billets->previousPageUrl() }}"
                                   class="px-2 py-1 text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 text-xs transition">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            @endif

                            {{-- Page Numbers --}}
                            @php
                                $start = max(1, $billets->currentPage() - 2);
                                $end = min($billets->lastPage(), $start + 4);
                                if($end - $start < 4 && $start > 1) $start = max(1, $end - 4);
                            @endphp

                            @if($start > 1)
                                <a href="{{ $billets->url(1) }}" class="px-2 py-1 text-gray-700 hover:bg-gray-50 rounded text-xs">1</a>
                                @if($start > 2) <span class="px-1 text-gray-400">...</span> @endif
                            @endif

                            @for($i = $start; $i <= $end; $i++)
                                @if($i == $billets->currentPage())
                                    <span class="px-2 py-1 bg-blue-600 text-white rounded text-xs font-semibold">{{ $i }}</span>
                                @else
                                    <a href="{{ $billets->url($i) }}"
                                       class="px-2 py-1 text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 text-xs transition">
                                        {{ $i }}
                                    </a>
                                @endif
                            @endfor

                            @if($end < $billets->lastPage())
                                @if($end < $billets->lastPage() - 1) <span class="px-1 text-gray-400">...</span> @endif
                                <a href="{{ $billets->url($billets->lastPage()) }}" class="px-2 py-1 text-gray-700 hover:bg-gray-50 rounded text-xs">
                                    {{ $billets->lastPage() }}
                                </a>
                            @endif

                            {{-- Next --}}
                            @if($billets->hasMorePages())
                                <a href="{{ $billets->nextPageUrl() }}"
                                   class="px-2 py-1 text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 text-xs transition">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <span class="px-2 py-1 text-gray-400 bg-gray-100 rounded text-xs">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden divide-y divide-gray-100">
                @foreach($billets as $billet)
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 text-base mb-1">{{ $billet->evenement->titre }}</h3>
                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                    <i class="fas fa-map-marker-alt text-gray-400"></i>
                                    {{ $billet->evenement->lieu->nom ?? 'Lieu non défini' }}
                                </p>
                            </div>
                            <div class="ml-2">
                                @if($billet->statut == 'Valide')
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                        <i class="fas fa-check-circle"></i> Valide
                                    </span>
                                @elseif($billet->statut == 'Utilisé')
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                        <i class="fas fa-check-double"></i> Utilisé
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                        <i class="fas fa-times-circle"></i> Annulé
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Date</p>
                                <p class="text-sm font-medium text-gray-800">
                                    {{ \Carbon\Carbon::parse($billet->evenement->date)->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Type</p>
                                <p class="text-sm font-medium text-gray-800">
                                    {{ $billet->typeBillet->nom ?? $billet->type ?? 'Standard' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Prix</p>
                                <p class="text-sm font-bold text-gray-900">
                                    {{ number_format($billet->prix, 2, ',', ' ') }} DH
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Code</p>
                                <p class="text-xs font-mono bg-gray-100 px-2 py-1 rounded text-gray-600">
                                    {{ substr($billet->code_unique, 0, 12) }}...
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex gap-2">
                            @if($billet->statut == 'Valide')
                                <a href="{{ route('client.billets.download', $billet->id) }}" 
                                   class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                                    <i class="fas fa-download"></i>
                                    Télécharger PDF
                                </a>
                                <button onclick="showQRCode('{{ $billet->code_unique }}', '{{ $billet->id }}', '{{ addslashes($billet->evenement->titre) }}')" 
                                        class="flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                                    <i class="fas fa-qrcode"></i>
                                    QR
                                </button>
                            @elseif($billet->statut == 'Utilisé')
                                <div class="flex-1 text-center py-2 bg-blue-50 text-blue-700 rounded-lg text-sm font-medium">
                                    <i class="fas fa-check-circle mr-1"></i> Billet utilisé
                                </div>
                            @else
                                <div class="flex-1 text-center py-2 bg-gray-50 text-gray-400 rounded-lg text-sm font-medium">
                                    <i class="fas fa-ban mr-1"></i> Non disponible
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
    @else
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-ticket-alt text-gray-400 text-4xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Aucun billet</h3>
            <p class="text-gray-500 mb-6">Vous n'avez pas encore acheté de billets.</p>
            <a href="{{ route('client.evenements.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md">
                <i class="fas fa-calendar-alt"></i>
                Découvrir les événements
            </a>
        </div>
    @endif
</div>

<!-- QR Code Modal (Khallih b7alo) -->
<div id="qrModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full transform transition-all scale-95 opacity-0" id="qrModalContent">
        <div class="p-6">
            <div class="text-center mb-4">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full mb-4">
                    <i class="fas fa-qrcode text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800" id="qrModalTitle">QR Code du billet</h3>
                <p class="text-xs text-gray-500 mt-1" id="qrModalEvent"></p>
            </div>
            <div id="qrModalCode" class="flex justify-center p-4 bg-gray-50 rounded-xl mb-4"></div>
            <p class="text-center text-sm text-gray-600 mb-4 flex items-center justify-center gap-2">
                <i class="fas fa-info-circle text-blue-500"></i>
                Présentez ce QR Code à l'entrée
            </p>
            <button onclick="closeQRModal()" 
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 rounded-xl transition-all duration-200 shadow-md">
                Fermer
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    function showQRCode(code, id, eventTitle) {
        let modal = document.getElementById('qrModal');
        let modalContent = document.getElementById('qrModalContent');
        let modalCode = document.getElementById('qrModalCode');
        let modalEvent = document.getElementById('qrModalEvent');
        
        if (modalEvent) modalEvent.textContent = eventTitle || 'Billet d\'événement';
        
        modalCode.innerHTML = '';
        let qrDiv = document.createElement('div');
        qrDiv.id = 'modal-qr-' + id;
        modalCode.appendChild(qrDiv);
        
        new QRCode(qrDiv, {
            text: code,
            width: 200,
            height: 200,
            colorDark: '#1e293b',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
    
    function closeQRModal() {
        let modal = document.getElementById('qrModal');
        let modalContent = document.getElementById('qrModalContent');
        
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 300);
    }
    
    window.onclick = function(event) {
        let modal = document.getElementById('qrModal');
        if (event.target == modal) closeQRModal();
    }
    
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') closeQRModal();
    });
</script>
@endpush
@endsection