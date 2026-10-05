{{-- resources/views/admin/logs/index.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Logs d\'activité')
@section('page-title', 'Logs d\'activité')
@section('page-description', 'Historique des actions effectuées sur la plateforme')

@section('page-actions')
    <a href="{{ route('admin.logs.export', request()->query()) }}" 
       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
        </svg>
        Exporter CSV
    </a>
    <button onclick="openCleanModal()" 
            class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
        </svg>
        Nettoyer
    </button>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Statistiques -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
        <div class="bg-white rounded shadow p-3 text-center">
            <div class="text-xl font-bold text-gray-800">{{ $stats['total'] }}</div>
            <div class="text-xs text-gray-500">Total</div>
        </div>
        <div class="bg-white rounded shadow p-3 text-center">
            <div class="text-xl font-bold text-red-600">{{ $stats['critical'] }}</div>
            <div class="text-xs text-gray-500">Critique</div>
        </div>
        <div class="bg-white rounded shadow p-3 text-center">
            <div class="text-xl font-bold text-orange-600">{{ $stats['error'] }}</div>
            <div class="text-xs text-gray-500">Erreur</div>
        </div>
        <div class="bg-white rounded shadow p-3 text-center">
            <div class="text-xl font-bold text-yellow-600">{{ $stats['warning'] }}</div>
            <div class="text-xs text-gray-500">Alerte</div>
        </div>
        <div class="bg-white rounded shadow p-3 text-center">
            <div class="text-xl font-bold text-blue-600">{{ $stats['info'] }}</div>
            <div class="text-xs text-gray-500">Info</div>
        </div>
        <div class="bg-white rounded shadow p-3 text-center">
            <div class="text-xl font-bold text-gray-800">{{ $stats['today'] }}</div>
            <div class="text-xs text-gray-500">Aujourd'hui</div>
        </div>
        <div class="bg-white rounded shadow p-3 text-center">
            <div class="text-xl font-bold text-gray-800">{{ $stats['this_month'] }}</div>
            <div class="text-xs text-gray-500">Ce mois</div>
        </div>
    </div>

    @php
        $isPaginator = method_exists($logs, 'currentPage') && method_exists($logs, 'lastPage');
        $firstItem = $isPaginator ? $logs->firstItem() : ($logs->count() ? 1 : 0);
        $lastItem = $isPaginator ? $logs->lastItem() : $logs->count();
        $totalItems = $isPaginator ? $logs->total() : $logs->count();
    @endphp
    <div class="mt-4 bg-slate-50 border border-slate-200 rounded-2xl p-4 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
        <div class="space-y-1">
            <p class="text-sm text-slate-500">Affichage</p>
            <p class="text-base font-semibold text-slate-900">
                Affiche {{ $firstItem }} à {{ $lastItem }} sur {{ $totalItems }} résultats
            </p>
        </div>
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
            @if(request()->anyFilled(['search', 'action', 'niveau', 'user_id', 'date_from', 'date_to']))
                <a href="{{ route('admin.logs.index') }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg shadow-sm hover:bg-slate-50 transition">Réinitialiser les filtres</a>
            @endif
            @if($isPaginator)
                <span class="inline-flex items-center rounded-full bg-blue-50 text-blue-700 text-xs font-semibold px-3 py-1">Page {{ $logs->currentPage() }} / {{ $logs->lastPage() }}</span>
            @endif
        </div>
    </div>
    
    <!-- Filtres simplifiés -->
    <div class="bg-white rounded shadow">
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-sm font-medium text-gray-700">Filtres</h3>
        </div>
        <div class="p-4">
            <form method="GET" action="{{ route('admin.logs.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div class="lg:col-span-2">
                        <label class="block text-xs text-gray-600 mb-1">Recherche</label>
                        <input type="search" name="search" value="{{ request('search') }}"
                               placeholder="Recherche par action, cible ou IP"
                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Action</label>
                        <select name="action" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="">Toutes</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>{{ $action }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Niveau</label>
                        <select name="niveau" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="">Tous</option>
                            <option value="INFO" {{ request('niveau') == 'INFO' ? 'selected' : '' }}>INFO</option>
                            <option value="WARNING" {{ request('niveau') == 'WARNING' ? 'selected' : '' }}>WARNING</option>
                            <option value="ERROR" {{ request('niveau') == 'ERROR' ? 'selected' : '' }}>ERROR</option>
                            <option value="CRITICAL" {{ request('niveau') == 'CRITICAL' ? 'selected' : '' }}>CRITICAL</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Utilisateur</label>
                        <select name="user_id" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="">Tous</option>
                            @forelse($users as $user)
                                <option value="{{ $user->id }}" {{ (string) request('user_id') === (string) $user->id ? 'selected' : '' }}>
                                    {{ trim($user->prenom . ' ' . $user->nom) ?: 'Utilisateur #' . $user->id }}
                                </option>
                            @empty
                                <option value="" disabled>Aucun utilisateur trouvé</option>
                            @endforelse
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-3">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Date début</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Date fin</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </div>
                    <div class="flex items-end space-x-2 lg:col-span-3">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                            Filtrer
                        </button>
                        @if(request()->anyFilled(['search', 'action', 'niveau', 'user_id', 'date_from', 'date_to']))
                            <a href="{{ route('admin.logs.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded text-sm hover:bg-gray-400">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Tableau des logs -->
    <div class="bg-white rounded shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Utilisateur</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Action</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Niveau</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">IP</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-sm text-gray-800">{{ $log->created_at->format('d/m/Y H:i') }}</div>
                        </td>
                        <td class="px-4 py-3">
                            @if($log->user)
                                <div class="text-sm text-gray-800">{{ trim($log->user->prenom . ' ' . $log->user->nom) ?: 'Utilisateur #' . $log->user->id }}</div>
                            @elseif($log->user_id)
                                <span class="text-sm text-gray-400">Utilisateur supprimé (#{{ $log->user_id }})</span>
                            @else
                                <span class="text-sm text-gray-500">Système</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-sm text-blue-600">{{ $log->action }}</span>
                            @if($log->table_cible)
                                <div class="text-xs text-gray-400">{{ $log->table_cible }} @if($log->record_id) #{{ $log->record_id }} @endif</div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $colors = [
                                    'CRITICAL' => 'text-red-600',
                                    'ERROR' => 'text-orange-600',
                                    'WARNING' => 'text-yellow-600',
                                    'INFO' => 'text-blue-600',
                                ];
                                $color = $colors[$log->niveau] ?? 'text-gray-600';
                            @endphp
                            <span class="text-sm font-medium {{ $color }}">{{ $log->niveau }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $log->ip ?? '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('admin.logs.show', $log->id) }}" class="text-blue-600 hover:text-blue-800" title="Voir">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.logs.destroy', $log->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Supprimer" onclick="return confirm('Supprimer ce log ?')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                            Aucun log trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(method_exists($logs, 'links') && $logs->hasPages())
            <div class="px-4 py-3 border-t">
                {{ $logs->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Nettoyer -->
<div id="cleanModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-5 max-w-sm w-full mx-4">
        <h3 class="text-lg font-semibold mb-3">Nettoyer les logs</h3>
        <form method="POST" action="{{ route('admin.logs.clean') }}">
            @csrf
            <p class="text-sm text-gray-600 mb-3">Supprimer les logs plus anciens que :</p>
            <select name="months" class="w-full px-3 py-2 border rounded mb-4 text-sm">
                <option value="1">1 mois</option>
                <option value="3">3 mois</option>
                <option value="6">6 mois</option>
                <option value="12" selected>12 mois</option>
            </select>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeCleanModal()" class="px-3 py-1.5 bg-gray-300 rounded text-sm">Annuler</button>
                <button type="submit" class="px-3 py-1.5 bg-red-600 text-white rounded text-sm">Supprimer</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openCleanModal() {
        document.getElementById('cleanModal').classList.remove('hidden');
        document.getElementById('cleanModal').classList.add('flex');
    }
    
    function closeCleanModal() {
        document.getElementById('cleanModal').classList.add('hidden');
        document.getElementById('cleanModal').classList.remove('flex');
    }
    
    window.onclick = function(event) {
        const modal = document.getElementById('cleanModal');
        if (event.target == modal) closeCleanModal();
    }
</script>
@endpush
@endsection