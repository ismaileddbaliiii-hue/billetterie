@extends('organisateur.layouts.master')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Aperçu de votre activité')

@push('styles')
<style>
    #ventesChart {
        width: 100% !important;
        height: 250px !important;
        display: block !important;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-gray-900 to-gray-800 rounded-2xl p-8 text-white">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mt-20 -mr-20"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -mb-16 -ml-16"></div>
        <div class="relative flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold">
                    Bonjour, {{ Auth::user()->prenom }} 👋
                </h1>
                <p class="text-gray-300 text-sm mt-2">
                    Voici ce qui se passe avec vos événements aujourd'hui
                </p>
            </div>
            @if(Auth::user()->perm_creation_event)
                <a href="{{ route('organisateur.evenements.create') }}" 
                   class="inline-flex items-center gap-2 px-5 py-3 bg-white hover:bg-gray-100 text-gray-900 text-sm font-semibold rounded-xl transition-all duration-200 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nouvel événement
                </a>
            @endif
        </div>
    </div>

    <!-- Stats Grid - 3 COLONNES -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <!-- Card 1 - Événements -->
        <div class="group bg-white rounded-2xl border border-gray-200 p-6 hover:border-gray-300 hover:shadow-lg transition-all duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Événements</p>
                    <p class="text-4xl font-bold text-gray-900 mt-2">{{ $totalEvenements }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center group-hover:bg-blue-100 transition">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex gap-4 mt-4 pt-4 border-t border-gray-100">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    <span class="text-xs text-gray-600">{{ $evenementsPublies }} <span class="text-gray-400">publiés</span></span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-amber-400 rounded-full"></span>
                    <span class="text-xs text-gray-600">{{ $evenementsBrouillon }} <span class="text-gray-400">brouillons</span></span>
                </div>
            </div>
        </div>

        <!-- Card 2 - Billets vendus -->
        <div class="group bg-white rounded-2xl border border-gray-200 p-6 hover:border-gray-300 hover:shadow-lg transition-all duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Billets vendus</p>
                    <p class="text-4xl font-bold text-gray-900 mt-2">{{ number_format($totalBilletsVendus) }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center group-hover:bg-emerald-100 transition">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-4 pt-4 border-t border-gray-100">Tous événements confondus</p>
        </div>

        <!-- Card 3 - Chiffre d'affaires -->
        <div class="group bg-white rounded-2xl border border-gray-200 p-6 hover:border-gray-300 hover:shadow-lg transition-all duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Chiffre d'affaires</p>
                    <p class="text-4xl font-bold text-gray-900 mt-2">
                        {{ number_format($caTotal, 0, ',', ' ') }}
                        <span class="text-lg font-normal text-gray-400">DH</span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center group-hover:bg-purple-100 transition">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-4 pt-4 border-t border-gray-100">Commission 0%</p>
        </div>
    </div>

    <!-- Charts Row -->
    @if(Auth::user()->perm_view_stats)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Sales Chart -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="font-semibold text-gray-900 text-lg">Évolution des ventes</h3>
                        <p class="text-xs text-gray-500 mt-1">Nombre de commandes par mois</p>
                    </div>
                    <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>
                    </div>
                </div>
                
                <canvas id="ventesChart"></canvas>
            </div>

            <!-- Top Events -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="font-semibold text-gray-900 text-lg">Meilleurs événements</h3>
                        <p class="text-xs text-gray-500 mt-1">Classés par nombre de ventes</p>
                    </div>
                    <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
                <div class="space-y-5">
                    @forelse($topEvenements as $index => $evenement)
                        <div class="group">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-2xl font-bold text-gray-300 group-hover:text-gray-400 transition">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $evenement->titre }}</p>
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                {{ \Carbon\Carbon::parse($evenement->date)->format('d M Y') }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-gray-900">{{ number_format($evenement->billets_vendus) }}</p>
                                            <p class="text-xs text-gray-400">ventes</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($evenement->capacite > 0)
                            <div class="ml-11">
                                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                    @php
                                        $pourcentage = ($evenement->billets_vendus / $evenement->capacite) * 100;
                                    @endphp
                                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-500" 
                                         style="width: {{ min($pourcentage, 100) }}%"></div>
                                </div>
                                <div class="flex justify-between mt-2">
                                    <span class="text-xs text-gray-400">{{ number_format($evenement->ca, 0, ',', ' ') }} DH</span>
                                    
                                </div>
                            </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <p class="text-gray-400 text-sm">Aucune donnée disponible</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @else
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-amber-500 rounded-lg p-6 flex items-start gap-4">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M7 9h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V11a2 2 0 012-2z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-amber-900">Statistiques non disponibles</h3>
                <p class="text-sm text-amber-700 mt-1">
                    Vous n'avez pas la permission de voir les statistiques. Contactez votre administrateur pour accéder à ces données.
                </p>
            </div>
        </div>
    @endif

    <!-- Recent Events Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex flex-wrap justify-between items-center gap-3">
            <div>
                <h3 class="font-semibold text-gray-900 text-lg">Événements récents</h3>
                <p class="text-xs text-gray-500 mt-1">Vos derniers événements créés</p>
            </div>
            <a href="{{ route('organisateur.evenements.index') }}" 
               class="inline-flex items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-700 transition">
                Voir tous
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
        
        <div class="overflow-x-auto">
            @if($evenementsRecents->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Événement</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lieu</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Ventes</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($evenementsRecents as $evenement)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <span class="font-medium text-gray-900">{{ $evenement->titre }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($evenement->date)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $evenement->lieu->nom ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-semibold text-gray-900">{{ number_format($evenement->billets_vendus ?? 0) }}</span>
                                    <span class="text-xs text-gray-400"> / {{ number_format($evenement->capacite) }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium
                                        @if($evenement->statut == 'Publié') bg-emerald-50 text-emerald-700
                                        @elseif($evenement->statut == 'Brouillon') bg-amber-50 text-amber-700
                                        @else bg-gray-100 text-gray-600 @endif">
                                        <span class="w-1.5 h-1.5 rounded-full 
                                            @if($evenement->statut == 'Publié') bg-emerald-500
                                            @elseif($evenement->statut == 'Brouillon') bg-amber-500
                                            @else bg-gray-500 @endif"></span>
                                        {{ $evenement->statut }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('organisateur.evenements.show', $evenement->id) }}" 
                                       class="inline-flex items-center gap-1 text-sm text-gray-400 hover:text-blue-600 transition-colors">
                                        <span>Détails</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-16">
                    <p class="text-gray-500 text-sm">Aucun événement créé</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('ventesChart');
    
    if (!ctx) return;
    
    // 🔧 Données GARANTIES
    const labels = @json($moisLabels);
    const data = @json($ventesData);
    
    console.log('📊 Création du graphique avec:', { labels, data });
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Commandes',
                data: data,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#111827',
                    titleColor: '#ffffff',
                    bodyColor: '#d1d5db',
                    padding: 12,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { 
                        stepSize: 1,
                        callback: (v) => Math.floor(v)
                    }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
});
</script>
@endpush
@endsection