{{-- resources/views/admin/logs/show.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Détails du log')
@section('page-title', 'Détails du log')
@section('page-description', 'Log #' . $log->id)

@section('page-actions')
    <a href="{{ route('admin.logs.index', request()->query()) }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
    <form method="POST" action="{{ route('admin.logs.destroy', $log->id) }}" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-primary bg-red-600 hover:bg-red-700" onclick="return confirm('Supprimer ce log ?')">
            <i class="fas fa-trash"></i> Supprimer
        </button>
    </form>
@endsection

@section('content')
@php
    $logDetails = json_decode($log->details ?? '{}', true) ?: [];
@endphp
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informations du log</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">ID</p>
                    <p class="font-medium">#{{ $log->id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Date</p>
                    <p class="font-medium">{{ $log->created_at->format('d/m/Y H:i:s') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Utilisateur</p>
                    <p class="font-medium">{{ $log->user ? $log->user->email : 'Système' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">IP</p>
                    <p class="font-mono">{{ $log->ip ?? ($logDetails['ip'] ?? '-') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Action</p>
                    <p class="font-mono text-blue-600">{{ $log->action }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Niveau</p>
                    @php
                        $colors = [
                            'CRITICAL' => 'danger',
                            'ERROR' => 'danger',
                            'WARNING' => 'warning',
                            'INFO' => 'info',
                        ];
                        $color = $colors[$log->niveau] ?? 'secondary';
                    @endphp
                    <span class="badge badge-{{ $color }}">{{ $log->niveau }}</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Table cible</p>
                    <p class="font-medium">{{ $log->table_cible ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">ID enregistrement</p>
                    <p class="font-medium">{{ $log->record_id ?? '-' }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-sm text-gray-500">User Agent</p>
                    <p class="text-sm">{{ $log->user_agent ?? ($logDetails['user_agent'] ?? '-') }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-sm text-gray-500">Détails de la requête</p>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Route</p>
                            <p class="text-sm font-medium">{{ $logDetails['route'] ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Chemin</p>
                            <p class="text-sm font-medium">{{ $logDetails['path'] ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Méthode</p>
                            <p class="text-sm font-medium">{{ $logDetails['method'] ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Query</p>
                            <pre class="text-xs bg-gray-50 p-3 rounded-lg overflow-x-auto">{{ json_encode($logDetails['query'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Params</p>
                            <pre class="text-xs bg-gray-50 p-3 rounded-lg overflow-x-auto">{{ json_encode($logDetails['params'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        @if($log->old_values || $log->new_values)
        <div class="mt-6 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Changements</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($log->old_values)
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-2">Anciennes valeurs</p>
                    <pre class="text-xs bg-gray-50 p-3 rounded-lg overflow-x-auto">{{ json_encode(json_decode($log->old_values, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
                @endif
                @if($log->new_values)
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-2">Nouvelles valeurs</p>
                    <pre class="text-xs bg-gray-50 p-3 rounded-lg overflow-x-auto">{{ json_encode(json_decode($log->new_values, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
    
    <div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Actions</h3>
            <div class="space-y-2">
                <button onclick="window.print()" class="w-full btn-secondary">
                    <i class="fas fa-print mr-2"></i> Imprimer
                </button>
                <a href="{{ route('admin.logs.index', ['search' => $log->action]) }}" class="w-full btn-secondary block text-center">
                    <i class="fas fa-search mr-2"></i> Voir logs similaires
                </a>
            </div>
        </div>
    </div>
</div>
@endsection