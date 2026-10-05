@extends('organisateur.layouts.master')

@section('title', 'Ventes de l\'événement : ' . $evenement->titre)
@section('page-title', 'Ventes de l\'événement')
@section('page-description', 'Historique des commandes pour l\'événement "'.$evenement->titre.'"')

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap justify-between items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $evenement->titre }}</h2>
            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($evenement->date)->format('d/m/Y H:i') }} - {{ $evenement->lieu->nom ?? 'Lieu non défini' }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('organisateur.ventes.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50">
                <i class="fas fa-arrow-left"></i>
                Retour aux ventes
            </a>
            <a href="{{ route('organisateur.evenements.show', $evenement->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700">
                <i class="fas fa-calendar-day"></i>
                Voir l\'événement
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 bg-white rounded-2xl border border-gray-100 text-center">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Total commandes</p>
            <p class="text-3xl font-bold text-gray-800">{{ $totalVentes }}</p>
        </div>
        <div class="p-4 bg-white rounded-2xl border border-gray-100 text-center">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Chiffre d\'affaires</p>
            <p class="text-3xl font-bold text-green-600">{{ number_format($caTotal, 2) }} DH</p>
        </div>
        <div class="p-4 bg-white rounded-2xl border border-gray-100 text-center">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Billets vendus</p>
            <p class="text-3xl font-bold text-purple-600">{{ $totalBillets }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h3 class="font-bold text-gray-800">Commandes confirmées</h3>
            <span class="text-xs text-gray-500">{{ $ventes->total() }} trouvées</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Client</th>
                        <th class="px-4 py-3">Billets</th>
                        <th class="px-4 py-3">Montant</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($ventes as $index => $commande)
                        <tr class="hover:bg-blue-50">
                            <td class="px-4 py-3">{{ str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-4 py-3">{{ $commande->client->prenom ?? 'N/A' }} {{ $commande->client->nom ?? '' }}</td>
                            <td class="px-4 py-3">{{ $commande->billets->count() }}</td>
                            <td class="px-4 py-3 text-green-600 font-semibold">{{ number_format($commande->montant_total, 2) }} DH</td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($commande->date)->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('organisateur.ventes.show', $commande->id) }}" class="px-3 py-1 text-white bg-blue-600 rounded-lg text-xs">Détails</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucune commande confirmée pour cet événement.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-white border-t border-gray-100">{{ $ventes->links() }}</div>
    </div>

</div>
@endsection