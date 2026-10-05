@extends('organisateur.layouts.master')

@section('title', 'Mes événements')
@section('page-title', 'Mes événements')
@section('page-description', 'Gérez tous vos événements en un seul endroit')

@section('content')
<div class="space-y-6">
    <!-- Modern Stats Grid with Gradients -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="group bg-white rounded-2xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-400">Total événements</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $evenements->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <p class="text-xs text-gray-400">Tous vos événements</p>
            </div>
        </div>
        
        <div class="group bg-white rounded-2xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-400">Publiés</p>
                    <p class="text-3xl font-bold text-emerald-600 mt-2">{{ $evenements->where('statut', 'Publié')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <p class="text-xs text-emerald-600">Visible par le public</p>
            </div>
        </div>
        
        <div class="group bg-white rounded-2xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-400">Brouillons</p>
                    <p class="text-3xl font-bold text-amber-600 mt-2">{{ $evenements->where('statut', 'Brouillon')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <p class="text-xs text-amber-600">En cours de création</p>
            </div>
        </div>
        
        <div class="group bg-white rounded-2xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-400">Capacité totale</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($evenements->sum('capacite')) }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <p class="text-xs text-gray-400">Places disponibles totales</p>
            </div>
        </div>
    </div>

    <!-- Enhanced Header Actions -->
    <div class="flex flex-wrap justify-between items-center gap-4">
        <div class="flex flex-wrap gap-3">
            <!-- Search with icon -->
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" 
                       id="search" 
                       placeholder="Rechercher un événement..."
                       class="pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-gray-300 focus:ring-1 focus:ring-gray-200 w-72 bg-gray-50/50 hover:bg-white transition">
            </div>
            
            <!-- Modern Filter Select -->
            <div class="relative">
                <select id="filter-statut" class="pl-3 pr-8 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-gray-300 appearance-none bg-gray-50/50 hover:bg-white cursor-pointer transition">
                    <option value="">📋 Tous les statuts</option>
                    <option value="Publié">✅ Publié</option>
                    <option value="Brouillon">✏️ Brouillon</option>
                    <option value="Terminé">🏁 Terminé</option>
                    <option value="Annulé">❌ Annulé</option>
                </select>
                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-3 h-3 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            
            <!-- Reset Button with icon -->
            <button id="resetFilters" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm text-gray-500 hover:text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Réinitialiser
            </button>
        </div>
        
        @if(Auth::user()->perm_creation_event)
            <a href="{{ route('organisateur.evenements.create') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-gray-900 to-gray-800 hover:from-gray-800 hover:to-gray-700 text-white text-sm font-medium rounded-xl transition-all shadow-sm hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouvel événement
            </a>
        @endif
    </div>
    
    <!-- Enhanced Events Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Événement</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lieu</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Places</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    \)\
                </thead>
                <tbody class="divide-y divide-gray-100" id="eventsTableBody">
                    @forelse($evenements as $evenement)
                        <tr class="group hover:bg-gray-50/80 transition-colors duration-200 event-row cursor-pointer"
                            data-titre="{{ strtolower($evenement->titre) }}"
                            data-statut="{{ $evenement->statut }}"
                            onclick="window.location='{{ route('organisateur.evenements.show', $evenement->id) }}'">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($evenement->affiche)
                                        <img src="{{ asset('storage/'.$evenement->affiche) }}" 
                                             alt="{{ $evenement->titre }}" 
                                             class="w-10 h-10 rounded-xl object-cover shadow-sm group-hover:scale-105 group-hover:shadow-md transition-all">
                                    @else
                                        <div class="w-10 h-10 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-gray-900 group-hover:text-gray-700 transition">{{ $evenement->titre }}</p>
                                        <p class="text-xs text-gray-400">ID #{{ $evenement->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($evenement->date)->format('d/m/Y') }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($evenement->date)->format('H:i') }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ $evenement->lieu->nom ?? '—' }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $evenement->lieu->ville ?? 'Lieu non défini' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-baseline gap-1.5 mb-1">
                                    <span class="text-sm font-bold text-gray-900">{{ number_format($evenement->getBilletVendus()) }}</span>
                                    <span class="text-xs text-gray-300">/</span>
                                    <span class="text-sm text-gray-500">{{ number_format($evenement->capacite) }}</span>
                                </div>
                                <div class="w-24 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-gradient-to-r from-gray-500 to-gray-600 h-1.5 rounded-full transition-all duration-500" style="width: {{ min(($evenement->getBilletVendus() / $evenement->capacite) * 100, 100) }}%"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($evenement->isPast())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                        Terminé
                                    </span>
                                @elseif($evenement->statut == 'Publié')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                        Publié
                                    </span>
                                @elseif($evenement->statut == 'Brouillon')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                        Brouillon
                                    </span>
                                @elseif($evenement->statut == 'Terminé')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                        Terminé
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                        Annulé
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right" onclick="event.stopPropagation()">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('organisateur.evenements.show', $evenement->id) }}" 
                                       class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-all"
                                       title="Voir les détails">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    
                                    @if(Auth::user()->perm_modification_event && $evenement->statut != 'Terminé')
                                        <a href="{{ route('organisateur.evenements.edit', $evenement->id) }}" 
                                           class="p-2 text-gray-400 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition-all"
                                           title="Modifier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    
                                    <a href="{{ route('organisateur.ventes.event', $evenement->id) }}" 
                                       class="p-2 text-gray-400 hover:text-purple-600 rounded-lg hover:bg-purple-50 transition-all"
                                       title="Statistiques de vente">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                                        </svg>
                                    </a>
                                    
                                    @if(Auth::user()->perm_suppression_event && $evenement->statut != 'Publié' && ($evenement->billets_vendus ?? 0) == 0)
                                        <form action="{{ route('organisateur.evenements.destroy', $evenement->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('⚠️ Attention : Cette action est irréversible. Supprimer définitivement cet événement ?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-all"
                                                    title="Supprimer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 text-sm font-medium">Aucun événement pour le moment</p>
                                    <p class="text-gray-400 text-xs mt-1">Commencez par créer votre premier événement</p>
                                    @if(Auth::user()->perm_creation_event)
                                        <a href="{{ route('organisateur.evenements.create') }}" 
                                           class="inline-flex items-center gap-2 mt-4 px-4 py-2 text-sm text-white bg-gray-900 hover:bg-gray-800 rounded-xl transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Créer un événement
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($evenements->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
                {{ $evenements->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
const searchInput = document.getElementById('search');
const statusFilter = document.getElementById('filter-statut');
const resetBtn = document.getElementById('resetFilters');
const rows = document.querySelectorAll('.event-row');

function filterEvents() {
    const searchTerm = searchInput?.value.toLowerCase() || '';
    const statusValue = statusFilter?.value || '';
    let visibleCount = 0;
    
    rows.forEach(row => {
        let show = true;
        
        // Filtre par recherche
        if (searchTerm) {
            const title = row.getAttribute('data-titre') || '';
            if (!title.includes(searchTerm)) show = false;
        }
        
        // Filtre par statut
        if (show && statusValue) {
            const rowStatus = row.getAttribute('data-statut');
            if (rowStatus !== statusValue) show = false;
        }
        
        row.style.display = show ? '' : 'none';
        if (show) visibleCount++;
    });
    
    // Gérer l'affichage "aucun résultat"
    const tbody = document.getElementById('eventsTableBody');
    const existingNoResult = document.getElementById('noResultsMsg');
    
    if (visibleCount === 0 && rows.length > 0) {
        if (!existingNoResult) {
            const tr = document.createElement('tr');
            tr.id = 'noResultsMsg';
            tr.innerHTML = `
                <td colspan="6" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <p class="text-gray-400 text-sm">Aucun événement trouvé</p>
                        <p class="text-gray-300 text-xs mt-1">Essayez d'autres critères de recherche</p>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        }
    } else if (existingNoResult) {
        existingNoResult.remove();
    }
}

function resetFilters() {
    if (searchInput) searchInput.value = '';
    if (statusFilter) statusFilter.value = '';
    filterEvents();
}

// Écouteurs d'événements
if (searchInput) searchInput.addEventListener('keyup', filterEvents);
if (statusFilter) statusFilter.addEventListener('change', filterEvents);
if (resetBtn) resetBtn.addEventListener('click', resetFilters);

// Animation d'entrée pour les lignes
document.addEventListener('DOMContentLoaded', () => {
    rows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateX(-10px)';
        setTimeout(() => {
            row.style.transition = 'all 0.3s ease-out';
            row.style.opacity = '1';
            row.style.transform = 'translateX(0)';
        }, index * 50);
    });
});
</script>
@endpush
@endsection