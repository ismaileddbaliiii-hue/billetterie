@extends('client.layouts.master')

@section('title', 'Mes Commandes')
@section('page-title', 'Mes Commandes')
@section('page-description', 'Suivez l\'historique de toutes vos commandes')

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
@endpush)

@section('content')
<div class="space-y-6">
    <!-- Header avec statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total commandes</p>
                    <p class="text-2xl font-bold">{{ $commandes->total() }}</p>
                </div>
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-white"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Commandes confirmées</p>
                    <p class="text-2xl font-bold">{{ $commandes->where('statut', 'Confirmée')->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-white"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">En attente</p>
                    <p class="text-2xl font-bold">{{ $commandes->where('statut', 'En attente')->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-white"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Total dépensé</p>
                    <p class="text-2xl font-bold">{{ number_format($commandes->where('statut', 'Confirmée')->sum('montant_total'), 2) }} DH</p>
                </div>
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Barre de recherche et filtres -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Search Input -->
                <div class="flex-1">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" 
                               id="searchInput"
                               placeholder="Rechercher par numéro de commande, événement..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                
                <!-- Filter by Status -->
                <div class="md:w-48">
                    <select id="statusFilter" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                        <option value="all">Tous les statuts</option>
                        <option value="Confirmée">✅ Confirmée</option>
                        <option value="En attente">⏳ En attente</option>
                        <option value="Annulée">❌ Annulée</option>
                    </select>
                </div>
                
                <!-- Date Filter -->
                <div class="md:w-48">
                    <select id="dateFilter" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                        <option value="all">Toutes les dates</option>
                        <option value="today">Aujourd'hui</option>
                        <option value="week">Cette semaine</option>
                        <option value="month">Ce mois</option>
                        <option value="year">Cette année</option>
                    </select>
                </div>
                
                <!-- Reset Button -->
                <button id="resetFilters" 
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-undo-alt mr-1"></i> Réinitialiser
                </button>
            </div>
        </div>
    </div>
    
    <!-- Orders List -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        @if($commandes->count() > 0)
            <!-- Pagination Top -->
            @if($commandes->hasPages())
            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Affichage de {{ $commandes->count() }} commande(s) sur {{ $commandes->total() }}
                    </div>
                    <div class="flex items-center gap-2">
                        {{-- Previous --}}
                        @if($commandes->onFirstPage())
                            <span class="px-2 py-1 text-gray-400 bg-gray-100 rounded text-xs">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $commandes->previousPageUrl() }}"
                               class="px-2 py-1 text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 text-xs transition">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Page Numbers --}}
                        @php
                            $start = max(1, $commandes->currentPage() - 2);
                            $end = min($commandes->lastPage(), $start + 4);
                            if($end - $start < 4 && $start > 1) $start = max(1, $end - 4);
                        @endphp

                        @if($start > 1)
                            <a href="{{ $commandes->url(1) }}" class="px-2 py-1 text-gray-700 hover:bg-gray-50 rounded text-xs">1</a>
                            @if($start > 2) <span class="px-1 text-gray-400">...</span> @endif
                        @endif

                        @for($i = $start; $i <= $end; $i++)
                            @if($i == $commandes->currentPage())
                                <span class="px-2 py-1 bg-blue-600 text-white rounded text-xs font-semibold">{{ $i }}</span>
                            @else
                                <a href="{{ $commandes->url($i) }}"
                                   class="px-2 py-1 text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 text-xs transition">
                                    {{ $i }}
                                </a>
                            @endif
                        @endfor

                        @if($end < $commandes->lastPage())
                            @if($end < $commandes->lastPage() - 1) <span class="px-1 text-gray-400">...</span> @endif
                            <a href="{{ $commandes->url($commandes->lastPage()) }}" class="px-2 py-1 text-gray-700 hover:bg-gray-50 rounded text-xs">
                                {{ $commandes->lastPage() }}
                            </a>
                        @endif

                        {{-- Next --}}
                        @if($commandes->hasMorePages())
                            <a href="{{ $commandes->nextPageUrl() }}"
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
                <table class="min-w-full divide-y divide-gray-200 table-fixed" id="ordersTable">
                    <thead class="bg-gray-50 sticky top-0 z-10">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-1/6">Commande</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-1/6">Date</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-1/4">Événements</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-1/6">Montant</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-1/6">Statut</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-1/6">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="ordersTableBody">
                        @foreach($commandes as $commande)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 order-row" 
                                data-id="{{ $commande->id }}"
                                data-statut="{{ $commande->statut }}"
                                data-date="{{ \Carbon\Carbon::parse($commande->date)->format('Y-m-d') }}"
                                data-search="{{ $commande->id }} {{ $commande->reference ?? '' }} {{ $commande->billets->first()->evenement->titre ?? '' }}">
                                <td class="px-3 py-3">
                                    <div class="font-bold text-gray-900">#{{ $commande->id }}</div>
                                    @if($commande->reference)
                                        <div class="text-xs text-gray-500 font-mono mt-1">Réf: {{ $commande->reference }}</div>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-600 whitespace-nowrap">
                                    <div>{{ \Carbon\Carbon::parse($commande->date)->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($commande->date)->format('H:i') }}</div>
                                </td>
                                <td class="px-3 py-3">
                                    <div class="text-sm font-medium text-gray-800">
                                        {{ $commande->billets->groupBy('evenement_id')->count() }} événement(s)
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-ticket-alt mr-1"></i>
                                        {{ $commande->billets->count() }} billet(s)
                                    </div>
                                </td>
                                <td class="px-3 py-3">
                                    <span class="text-lg font-bold text-gray-900">{{ number_format($commande->montant_total, 2) }} DH</span>
                                </td>
                                <td class="px-3 py-3">
                                    @if($commande->statut == 'Confirmée')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                            Confirmée
                                        </span>
                                    @elseif($commande->statut == 'En attente')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                            <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5 animate-pulse"></span>
                                            En attente
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                                            Annulée
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-3">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('client.commandes.show', $commande->id) }}" 
                                           class="text-blue-600 hover:text-blue-800 transition-colors p-1.5 rounded-lg hover:bg-blue-50"
                                           title="Voir détails">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                       
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bottom -->
            @if($commandes->hasPages())
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sticky bottom-0">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Page {{ $commandes->currentPage() }} sur {{ $commandes->lastPage() }}
                    </div>
                    <div class="flex items-center gap-2">
                        {{-- Previous --}}
                        @if($commandes->onFirstPage())
                            <span class="px-2 py-1 text-gray-400 bg-gray-100 rounded text-xs">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $commandes->previousPageUrl() }}"
                               class="px-2 py-1 text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 text-xs transition">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Page Numbers --}}
                        @php
                            $start = max(1, $commandes->currentPage() - 2);
                            $end = min($commandes->lastPage(), $start + 4);
                            if($end - $start < 4 && $start > 1) $start = max(1, $end - 4);
                        @endphp

                        @if($start > 1)
                            <a href="{{ $commandes->url(1) }}" class="px-2 py-1 text-gray-700 hover:bg-gray-50 rounded text-xs">1</a>
                            @if($start > 2) <span class="px-1 text-gray-400">...</span> @endif
                        @endif

                        @for($i = $start; $i <= $end; $i++)
                            @if($i == $commandes->currentPage())
                                <span class="px-2 py-1 bg-blue-600 text-white rounded text-xs font-semibold">{{ $i }}</span>
                            @else
                                <a href="{{ $commandes->url($i) }}"
                                   class="px-2 py-1 text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 text-xs transition">
                                    {{ $i }}
                                </a>
                            @endif
                        @endfor

                        @if($end < $commandes->lastPage())
                            @if($end < $commandes->lastPage() - 1) <span class="px-1 text-gray-400">...</span> @endif
                            <a href="{{ $commandes->url($commandes->lastPage()) }}" class="px-2 py-1 text-gray-700 hover:bg-gray-50 rounded text-xs">
                                {{ $commandes->lastPage() }}
                            </a>
                        @endif

                        {{-- Next --}}
                        @if($commandes->hasMorePages())
                            <a href="{{ $commandes->nextPageUrl() }}"
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
        @else
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shopping-bag text-gray-400 text-4xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Aucune commande</h3>
                <p class="text-gray-500 mb-6">Vous n'avez pas encore passé de commande.</p>
                <a href="{{ route('client.evenements.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md">
                    <i class="fas fa-ticket-alt mr-2"></i>
                    Découvrir les événements
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const dateFilter = document.getElementById('dateFilter');
    const resetBtn = document.getElementById('resetFilters');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const rows = document.querySelectorAll('.order-row');
    const noResults = document.getElementById('noResults');
    const tableBody = document.getElementById('ordersTableBody');
    
    function filterOrders() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        const statusValue = statusFilter ? statusFilter.value : 'all';
        const dateValue = dateFilter ? dateFilter.value : 'all';
        
        let visibleCount = 0;
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        rows.forEach(row => {
            let show = true;
            
            // Search filter
            if (searchTerm) {
                const searchData = row.getAttribute('data-search')?.toLowerCase() || '';
                if (!searchData.includes(searchTerm)) {
                    show = false;
                }
            }
            
            // Status filter
            if (show && statusValue !== 'all') {
                const rowStatus = row.getAttribute('data-statut');
                if (rowStatus !== statusValue) {
                    show = false;
                }
            }
            
            // Date filter
            if (show && dateValue !== 'all') {
                const rowDate = new Date(row.getAttribute('data-date'));
                const now = new Date();
                const weekAgo = new Date(now.setDate(now.getDate() - 7));
                const monthAgo = new Date(now.setMonth(now.getMonth() - 1));
                const yearAgo = new Date(now.setFullYear(now.getFullYear() - 1));
                
                switch(dateValue) {
                    case 'today':
                        if (rowDate.toDateString() !== today.toDateString()) show = false;
                        break;
                    case 'week':
                        if (rowDate < weekAgo) show = false;
                        break;
                    case 'month':
                        if (rowDate < monthAgo) show = false;
                        break;
                    case 'year':
                        if (rowDate < yearAgo) show = false;
                        break;
                }
            }
            
            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });
        
        // Show/hide no results message
        if (noResults) {
            if (visibleCount === 0) {
                noResults.classList.remove('hidden');
                if (tableBody) tableBody.style.display = 'none';
            } else {
                noResults.classList.add('hidden');
                if (tableBody) tableBody.style.display = '';
            }
        }
    }
    
    // Event listeners
    if (searchInput) searchInput.addEventListener('keyup', filterOrders);
    if (statusFilter) statusFilter.addEventListener('change', filterOrders);
    if (dateFilter) dateFilter.addEventListener('change', filterOrders);
    
    // Reset filters
    function resetFilters() {
        if (searchInput) searchInput.value = '';
        if (statusFilter) statusFilter.value = 'all';
        if (dateFilter) dateFilter.value = 'all';
        filterOrders();
    }
    
    if (resetBtn) resetBtn.addEventListener('click', resetFilters);
    if (clearFiltersBtn) clearFiltersBtn.addEventListener('click', resetFilters);
    
    // Animation on load
    rows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(10px)';
        setTimeout(() => {
            row.style.transition = 'all 0.3s ease';
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        }, index * 50);
    });
});
</script>
@endpush
@endsection