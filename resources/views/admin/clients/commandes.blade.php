{{-- resources/views/admin/clients/commandes.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Commandes de ' . $user->prenom . ' ' . $user->nom)
@section('page-title', 'Commandes du client')
@section('page-description', $user->prenom . ' ' . $user->nom)

@section('page-actions')
    <a href="{{ route('admin.clients.edit', $user) }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Retour au client
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Informations client -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow p-6 text-white">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center text-2xl font-bold">
                {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-2xl font-bold">{{ $user->prenom }} {{ $user->nom }}</h2>
                <p class="text-blue-100">{{ $user->email }}</p>
                <p class="text-blue-100 text-sm mt-1">
                    <i class="fas fa-shopping-cart mr-1"></i>
                    {{ $user->commandes_count ?? 0 }} commandes | 
                    <i class="fas fa-ticket-alt ml-2 mr-1"></i>
                    {{ $user->billets_count ?? 0 }} billets |
                    <i class="fas fa-money-bill-wave ml-2 mr-1"></i>
                    {{ number_format($user->total_depense ?? 0, 2, ',', ' ') }} DH
                </p>
            </div>
        </div>
    </div>
    
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total commandes</p>
                    <p class="text-2xl font-bold">{{ $commandes->total() }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Commandes confirmées</p>
                    <p class="text-2xl font-bold text-green-600">
                        {{ $commandes->where('statut', 'Confirmée')->count() }}
                    </p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Montant total</p>
                    <p class="text-2xl font-bold text-yellow-600">
                        {{ number_format($commandes->where('statut', 'Confirmée')->sum('montant_total'), 2, ',', ' ') }} DH
                    </p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-yellow-600"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tableau des commandes -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Référence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Billets</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paiement</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($commandes as $commande)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $commande->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm">{{ $commande->reference }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $commande->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-semibold text-gray-800">{{ number_format($commande->montant_total, 2, ',', ' ') }} DH</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'Confirmée' => 'success',
                                    'En attente' => 'warning',
                                    'Annulée' => 'danger',
                                    'Remboursée' => 'info',
                                ];
                                $color = $statusColors[$commande->statut] ?? 'secondary';
                            @endphp
                            <span class="badge badge-{{ $color }}">{{ $commande->statut }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $commande->billets->count() }} billet(s)
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($commande->paiement)
                                <span class="text-sm">
                                    {{ $commande->paiement->prestataire }}
                                    <span class="text-xs text-gray-500">
                                        ({{ $commande->paiement->created_at->format('d/m/Y') }})
                                    </span>
                                </span>
                            @else
                                <span class="text-sm text-gray-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('admin.commandes.show', $commande) }}" 
                               class="text-blue-600 hover:text-blue-800 transition" title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-shopping-cart text-4xl mb-3 opacity-50"></i>
                            <p>Aucune commande trouvée pour ce client</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(method_exists($commandes, 'links'))
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $commandes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection