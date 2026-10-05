{{-- resources/views/admin/commandes/index.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Gestion des commandes')
@section('page-title', 'Commandes')
@section('page-description', 'Gérez les commandes passées et suivez leur statut')

@section('page-actions')
    <a href="{{ route('admin.commandes.export', request()->only('statut')) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
        </svg>
        Exporter
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Total commandes</p>
            <p class="text-4xl font-bold text-gray-900 mt-4">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Confirmées</p>
            <p class="text-4xl font-bold text-green-600 mt-4">{{ $stats['confirmees'] }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">En attente</p>
            <p class="text-4xl font-bold text-yellow-600 mt-4">{{ $stats['en_attente'] }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Annulées</p>
            <p class="text-4xl font-bold text-red-600 mt-4">{{ $stats['annulees'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h3 class="text-base font-bold text-gray-900">Filtrer les commandes</h3>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('admin.commandes.index') }}" class="space-y-4 md:grid md:grid-cols-4 md:gap-4 md:items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Recherche</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Référence..." class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Client</label>
                    <select name="client_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Tous les clients</option>
                        @if(isset($clients))
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->prenom }} {{ $client->nom }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Statut</label>
                    <select name="statut" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Tous les statuts</option>
                        <option value="Confirmée" {{ request('statut') == 'Confirmée' ? 'selected' : '' }}>Confirmée</option>
                        <option value="En attente" {{ request('statut') == 'En attente' ? 'selected' : '' }}>En attente</option>
                        <option value="Annulée" {{ request('statut') == 'Annulée' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>
                <div class="space-x-2 flex items-center">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition">Appliquer</button>
                    @if(request()->filled('search') || request()->filled('statut'))
                        <a href="{{ route('admin.commandes.index') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition">Réinitialiser</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Référence</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($commandes as $commande)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $commande->reference }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $commande->client ? $commande->client->prenom . ' ' . $commande->client->nom : 'Client inconnu' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($commande->montant_total, 2, ',', ' ') }} DH</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $commande->statut == 'Confirmée' ? 'bg-green-100 text-green-700' : ($commande->statut == 'En attente' ? 'bg-yellow-100 text-yellow-700' : ($commande->statut == 'Annulée' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700')) }}">
                                    {{ $commande->statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <a href="{{ route('admin.commandes.show', $commande) }}" class="text-blue-600 hover:text-blue-800">Voir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">Aucune commande trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50">
            {{ $commandes->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
