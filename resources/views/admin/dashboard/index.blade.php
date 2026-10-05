@extends('admin.layouts.master')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')
@section('page-description', 'Vue d\'ensemble de votre plateforme')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 to-slate-800 rounded-2xl p-6 text-white">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mt-20 -mr-20"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -mb-16 -ml-16"></div>
        <div class="relative flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Bonjour, {{ Auth::user()->prenom ?? 'Admin' }} 👋</h1>
                <p class="text-slate-300 text-sm mt-1">Voici les performances de votre plateforme aujourd'hui</p>
            </div>
            <div class="text-right">
                <p class="text-3xl font-bold">{{ now()->format('d') }}</p>
                <p class="text-slate-300 text-sm">{{ now()->translatedFormat('F Y') }}</p>
            </div>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Organisateurs -->
        <div class="group bg-white rounded-xl border border-gray-200 p-5 hover:shadow-lg hover:border-blue-200 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Organisateurs</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($totalOrganisateurs ?? 0) }}</p>
                </div>
                <div class="w-11 h-11 bg-blue-50 rounded-lg flex items-center justify-center group-hover:bg-blue-100 transition">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-100">
                <span class="inline-flex items-center text-xs font-medium text-green-600 bg-green-50 px-2 py-0.5 rounded-full">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    +{{ $nouveauxOrganisateurs ?? 0 }}
                </span>
                <span class="text-xs text-gray-400">ce mois</span>
            </div>
        </div>

        <!-- Clients -->
        <div class="group bg-white rounded-xl border border-gray-200 p-5 hover:shadow-lg hover:border-emerald-200 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Clients</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($totalClients ?? 0) }}</p>
                </div>
                <div class="w-11 h-11 bg-emerald-50 rounded-lg flex items-center justify-center group-hover:bg-emerald-100 transition">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-100">
                <span class="inline-flex items-center text-xs font-medium text-green-600 bg-green-50 px-2 py-0.5 rounded-full">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    +{{ $nouveauxClients ?? 0 }}
                </span>
                <span class="text-xs text-gray-400">cette semaine</span>
            </div>
        </div>

        <!-- Événements -->
        <div class="group bg-white rounded-xl border border-gray-200 p-5 hover:shadow-lg hover:border-purple-200 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Événements</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($totalEvenements ?? 0) }}</p>
                </div>
                <div class="w-11 h-11 bg-purple-50 rounded-lg flex items-center justify-center group-hover:bg-purple-100 transition">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-center gap-4 mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    <span class="text-xs text-gray-600">{{ $evenementsPublies ?? 0 }} <span class="text-gray-400">publiés</span></span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 bg-amber-400 rounded-full"></span>
                    <span class="text-xs text-gray-600">{{ $evenementsBrouillon ?? 0 }} <span class="text-gray-400">brouillons</span></span>
                </div>
            </div>
        </div>

        <!-- Chiffre d'affaires -->
        <div class="group bg-white rounded-xl border border-gray-200 p-5 hover:shadow-lg hover:border-amber-200 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Chiffre d'affaires</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">
                        {{ isset($caTotal) ? number_format($caTotal, 0, ',', ' ') : '0' }}
                        <span class="text-sm font-normal text-gray-400">MAD</span>
                    </p>
                </div>
                <div class="w-11 h-11 bg-amber-50 rounded-lg flex items-center justify-center group-hover:bg-amber-100 transition">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-100">
                <span class="inline-flex items-center text-xs font-medium text-green-600 bg-green-50 px-2 py-0.5 rounded-full">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    +{{ $croissanceCA ?? 0 }}%
                </span>
                <span class="text-xs text-gray-400">vs mois dernier</span>
            </div>
        </div>
    </div>

    <!-- Secondary Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider">Billets vendus</p>
                <p class="text-xl font-bold text-gray-900">{{ number_format($totalBilletsVendus ?? 0) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider">Revenus du mois</p>
                <p class="text-xl font-bold text-gray-900">{{ isset($revenusMois) ? number_format($revenusMois, 0, ',', ' ') : '0' }} MAD</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider">Aujourd'hui</p>
                <p class="text-xl font-bold text-gray-900">{{ $transactionsAujourdhui ?? 0 }} transactions</p>
                <p class="text-xs text-gray-400">{{ isset($montantAujourdhui) ? number_format($montantAujourdhui, 0, ',', ' ') : '0' }} MAD</p>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Sales Chart -->
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-gray-900">Évolution des ventes</h3>
                    <p class="text-xs text-gray-500 mt-0.5">30 derniers jours</p>
                </div>
                <select id="ventesPeriod" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="7">7 jours</option>
                    <option value="30" selected>30 jours</option>
                    <option value="90">3 mois</option>
                </select>
            </div>
            <canvas id="ventesChart" height="220"></canvas>
        </div>

        <!-- Top Events Chart -->
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="mb-4">
                <h3 class="font-semibold text-gray-900">Top événements</h3>
                <p class="text-xs text-gray-500 mt-0.5">Par nombre de billets vendus</p>
            </div>
            <canvas id="topEvenementsChart" height="220"></canvas>
        </div>
    </div>

    <!-- Lists Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Organizers -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-900">Derniers organisateurs</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Inscriptions récentes</p>
                </div>
                <a href="{{ route('admin.organisateurs.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                    Voir tout →
                </a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($derniersOrganisateurs ?? [] as $org)
                <div class="p-4 hover:bg-gray-50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                            {{ strtoupper(substr($org->prenom ?? 'O', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900 text-sm">{{ $org->prenom ?? '' }} {{ $org->nom ?? '' }}</p>
                            <p class="text-xs text-gray-500">{{ $org->email ?? '' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-400">{{ $org->created_at ? $org->created_at->diffForHumans() : '' }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-400">
                    <p class="text-sm">Aucun organisateur récent</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-900">Événements à venir</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Prochains rendez-vous</p>
                </div>
                <a href="{{ route('admin.evenements.index') }}" class="text-sm text-purple-600 hover:text-purple-700 font-medium">
                    Voir tout →
                </a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($evenementsAVenir ?? [] as $event)
                <div class="p-4 hover:bg-gray-50 transition">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900 text-sm">{{ $event->titre ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $event->organisateur_nom ?? 'Organisateur' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900">{{ isset($event->date) ? $event->date->format('d/m/Y') : 'N/A' }}</p>
                            <p class="text-xs text-gray-400">{{ isset($event->date) ? $event->date->format('H:i') : '' }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-400">
                    <p class="text-sm">Aucun événement à venir</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-gray-900">Dernières transactions</h3>
                <p class="text-xs text-gray-500 mt-0.5">Activité financière récente</p>
            </div>
            <a href="{{ route('admin.paiements.index') }}" class="text-sm text-amber-600 hover:text-amber-700 font-medium">
                Voir tout →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Référence</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Client</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Montant</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Statut</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($dernieresTransactions ?? [] as $transaction)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 text-sm font-medium text-gray-900">{{ $transaction->reference ?? 'N/A' }}</td>
                        <td class="px-5 py-3 text-sm text-gray-600">{{ $transaction->client_nom ?? 'Client' }}</td>
                        <td class="px-5 py-3 text-sm font-semibold text-gray-900">
                            {{ isset($transaction->montant) ? number_format($transaction->montant, 2, ',', ' ') : '0,00' }} MAD
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                {{ ($transaction->statut ?? '') == 'Confirmée' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ ($transaction->statut ?? '') == 'Confirmée' ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                {{ $transaction->statut ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-500">{{ isset($transaction->created_at) ? $transaction->created_at->format('d/m/Y H:i') : '' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-gray-400">
                            <p class="text-sm">Aucune transaction récente</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données pour les graphiques
    var ventesLabels = {!! json_encode($ventesLabels ?? ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim']) !!};
    var ventesData = {!! json_encode($ventesData ?? [1200, 1900, 1500, 2500, 2200, 3000, 2800]) !!};
    var topLabels = {!! json_encode($topEvenementsLabels ?? ['Concert', 'Festival', 'Théâtre', 'Conférence', 'Sport']) !!};
    var topData = {!! json_encode($topEvenementsData ?? [45, 38, 32, 28, 25]) !!};
    
    // Ventes Chart
    var ctx1 = document.getElementById('ventesChart');
    var ventesChart = null;
    
    if (ctx1) {
        ventesChart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ventesLabels,
                datasets: [{
                    label: 'Ventes (MAD)',
                    data: ventesData,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.05)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
    
    // Period selector
    document.getElementById('ventesPeriod')?.addEventListener('change', function() {
        var period = this.value;
        fetch('/admin/dashboard/ventes-data?period=' + period)
            .then(res => res.json())
            .then(data => {
                if (data.success && ventesChart) {
                    ventesChart.data.labels = data.labels;
                    ventesChart.data.datasets[0].data = data.values;
                    ventesChart.update();
                }
            });
    });
    
    // Top Events Chart
    var ctx2 = document.getElementById('topEvenementsChart');
    if (ctx2) {
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: topLabels,
                datasets: [{
                    label: 'Billets vendus',
                    data: topData,
                    backgroundColor: '#3b82f6',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
});
</script>
@endpush
@endsection