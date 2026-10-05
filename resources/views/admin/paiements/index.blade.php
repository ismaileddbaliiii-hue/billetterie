{{-- resources/views/admin/paiements/index.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Gestion des paiements')
@section('page-title', 'Paiements')
@section('page-description', 'Suivi de toutes les transactions financières')

@section('page-actions')
    <a href="{{ route('admin.paiements.export', request()->query()) }}" class="btn-primary">
        <i class="fas fa-download"></i> Exporter
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Total transactions</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-credit-card text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-green-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Transactions payées</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['payes'] }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-red-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Transactions échouées</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['echecs'] }}</p>
                </div>
                <div class="bg-red-100 rounded-full p-3">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-yellow-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Montant total</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['montant_total'], 2, ',', ' ') }} DH</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <i class="fas fa-money-bill-wave text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('admin.paiements.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Référence, transaction..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Statut</label>
                <select name="statut" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">Tous</option>
                    <option value="En attente" {{ request('statut') == 'En attente' ? 'selected' : '' }}>En attente</option>
                    <option value="Payé" {{ request('statut') == 'Payé' ? 'selected' : '' }}>Payé</option>
                    <option value="Échec" {{ request('statut') == 'Échec' ? 'selected' : '' }}>Échec</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Prestataire</label>
                <select name="prestataire" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">Tous</option>
                    <option value="Carte" {{ request('prestataire') == 'Carte' ? 'selected' : '' }}>Carte bancaire</option>
                    <option value="PayPal" {{ request('prestataire') == 'PayPal' ? 'selected' : '' }}>PayPal</option>
                    <option value="Virement" {{ request('prestataire') == 'Virement' ? 'selected' : '' }}>Virement</option>
                    <option value="Espèces" {{ request('prestataire') == 'Espèces' ? 'selected' : '' }}>Espèces</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Date</label>
                <input type="date" name="date" value="{{ request('date') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-search"></i> Filtrer
                </button>
                @if(request()->anyFilled(['search', 'statut', 'prestataire', 'date']))
                    <a href="{{ route('admin.paiements.index') }}" class="btn-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
    
    <!-- Tableau des paiements -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Référence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prestataire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($paiements as $paiement)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $paiement->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-xs font-mono">{{ $paiement->reference }}</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($paiement->commande && $paiement->commande->client)
                                <div class="text-sm text-gray-900">{{ $paiement->commande->client->prenom }} {{ $paiement->commande->client->nom }}</div>
                                <div class="text-xs text-gray-500">{{ $paiement->commande->client->email }}</div>
                            @else
                                <span class="text-sm text-gray-500">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-semibold text-gray-900">{{ number_format($paiement->montant, 2, ',', ' ') }} DH</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                @if($paiement->prestataire == 'Stripe' || $paiement->prestataire == 'Carte')
                                    <i class="fab fa-cc-visa text-blue-600"></i>
                                @elseif($paiement->prestataire == 'PayPal')
                                    <i class="fab fa-paypal text-blue-800"></i>
                                @elseif($paiement->prestataire == 'Virement')
                                    <i class="fas fa-university text-green-600"></i>
                                @elseif($paiement->prestataire == 'Espèces')
                                    <i class="fas fa-money-bill-wave text-yellow-600"></i>
                                @else
                                    <i class="fas fa-credit-card text-gray-500"></i>
                                @endif
                                <span class="text-sm">{{ $paiement->prestataire }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $paiement->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'Payé' => 'success',
                                    'En attente' => 'warning',
                                    'Échec' => 'danger',
                                    'Remboursé' => 'info',
                                ];
                                $color = $statusColors[$paiement->statut] ?? 'secondary';
                            @endphp
                            <span class="badge badge-{{ $color }}">{{ $paiement->statut }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.paiements.show', $paiement) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($paiement->statut == 'Échec')
                                    <form action="{{ route('admin.paiements.retry', $paiement) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-900" title="Réessayer">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </form>
                                @endif
                                @if($paiement->statut == 'Payé')
                                    <form action="{{ route('admin.paiements.refund', $paiement) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900" 
                                                title="Rembourser"
                                                onclick="return confirm('Êtes-vous sûr de vouloir rembourser ce paiement ? Cette action est irréversible.')">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-credit-card text-4xl mb-2 opacity-50"></i>
                            <p>Aucun paiement trouvé</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(method_exists($paiements, 'links'))
            <div class="px-6 py-4 border-t">
                {{ $paiements->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection