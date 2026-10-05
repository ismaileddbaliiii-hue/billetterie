{{-- resources/views/admin/evenements/billets.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Billets - ' . $evenement->titre)
@section('page-title', 'Gestion des billets')
@section('page-description', $evenement->titre)

@section('page-actions')
    <a href="{{ route('admin.evenements.show', $evenement) }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Retour à l'événement
    </a>
    @if($billets->count() > 0)
    <a href="{{ route('admin.evenements.download-all-tickets', $evenement) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm">
        <i class="fas fa-download mr-2"></i>
        Télécharger tous les billets
    </a>
    @endif
@endsection

@section('content')
<div class="space-y-6">
    <!-- Informations de l'événement -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">{{ $evenement->titre }}</h2>
                <p class="text-blue-100 mt-1">
                    <i class="fas fa-calendar-alt mr-1"></i> {{ $evenement->date->format('d/m/Y à H:i') }}
                    @if($evenement->lieu)
                        <i class="fas fa-map-marker-alt ml-3 mr-1"></i> {{ $evenement->lieu->nom }}, {{ $evenement->lieu->ville }}
                    @endif
                </p>
                <p class="text-blue-100 mt-1">
                    <i class="fas fa-user mr-1"></i> Organisateur: {{ $evenement->organisateur->prenom }} {{ $evenement->organisateur->nom }}
                </p>
            </div>
            <div class="text-right">
                <div class="bg-white/20 rounded-lg px-4 py-2">
                    <p class="text-sm">Capacité totale</p>
                    <p class="text-2xl font-bold">{{ $evenement->capacite }} places</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistiques des billets -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total billets</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-ticket-alt text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Billets valides</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['valides'] }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Billets annulés</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['annules'] }}</p>
                </div>
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Remboursés</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $stats['rembourses'] }}</p>
                </div>
                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-undo-alt text-orange-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Chiffre d'affaires</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['ca_total'], 0, ',', ' ') }} DH</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-yellow-600"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filtres améliorés -->
    <div class="bg-white rounded-lg shadow p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Recherche</label>
                <input type="text" id="searchBillet" placeholder="Code billet, spectateur, email..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Statut</label>
                <select id="filterStatut" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">Tous les statuts</option>
                    <option value="Valide">Valide</option>
                    <option value="Annulé">Annulé</option>
                    <option value="Remboursé">Remboursé</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Type de billet</label>
                <select id="filterType" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">Tous les types</option>
                    @foreach($evenement->typesBillets as $type)
                        <option value="{{ $type->id }}">{{ $type->nom }} ({{ number_format($type->prix, 2, ',', ' ') }} DH)</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Commande</label>
                <select id="filterCommande" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">Toutes les commandes</option>
                    @php
                        $commandes = $billets->pluck('commande_id')->unique()->sort();
                    @endphp
                    @foreach($commandes as $commandeId)
                        <option value="{{ $commandeId }}">Commande #{{ $commandeId }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Client</label>
                <select id="filterClient" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">Tous les clients</option>
                    @php
                        $clients = [];
                        foreach($billets as $billet) {
                            if($billet->commande && $billet->commande->client) {
                                $clientId = $billet->commande->client->id;
                                $clientName = $billet->commande->client->prenom . ' ' . $billet->commande->client->nom;
                                $clients[$clientId] = $clientName;
                            }
                        }
                    @endphp
                    @foreach($clients as $clientId => $clientName)
                        <option value="{{ $clientId }}">{{ $clientName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button onclick="applyFilters()" class="btn-primary">
                    <i class="fas fa-filter"></i> Filtrer
                </button>
                <button onclick="resetFilters()" class="btn-secondary">
                    <i class="fas fa-undo"></i> Réinitialiser
                </button>
            </div>
        </div>
    </div>
    
    <!-- Tableau des billets amélioré -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    46
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">N°</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Commande</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">QR</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Spectateur</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date achat</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="billetsTableBody">
                    @forelse($billets as $index => $billet)
                    <tr class="hover:bg-gray-50 transition-colors billet-row" 
                        data-code="{{ $billet->code_unique }}"
                        data-statut="{{ $billet->statut }}"
                        data-type="{{ $billet->type_billet_id }}"
                        data-commande="{{ $billet->commande_id }}"
                        data-client="{{ $billet->commande->client_id ?? '' }}">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }} </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                #{{ $billet->commande_id }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($billet->commande && $billet->commande->client)
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-bold text-blue-600">
                                            {{ strtoupper(substr($billet->commande->client->prenom, 0, 1)) }}{{ strtoupper(substr($billet->commande->client->nom, 0, 1)) }}
                                        </span>
                                    </div>
                                    <span class="text-sm">{{ $billet->commande->client->prenom }} {{ $billet->commande->client->nom }}</span>
                                </div>
                            @else
                                <span class="text-sm text-gray-400">Admin</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <code class="text-xs bg-gray-100 px-2 py-1 rounded font-mono">{{ substr($billet->code_unique, 0, 8) }}...{{ substr($billet->code_unique, -4) }}</code>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <button onclick="showQRCode('{{ $billet->code_unique }}')" 
                                    class="text-purple-600 hover:text-purple-800 transition" title="Afficher QR Code">
                                <i class="fas fa-qrcode text-lg"></i>
                            </button>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm font-medium text-gray-900">{{ $billet->nom_spectateur }}</div>
                            <div class="text-xs text-gray-500">{{ $billet->email_spectateur }}</div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="text-sm">{{ $billet->typeBillet->nom ?? 'Standard' }}</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($billet->prix, 2, ',', ' ') }} DH</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'Valide' => 'success',
                                    'Annulé' => 'danger',
                                    'Remboursé' => 'warning',
                                ];
                                $color = $statusColors[$billet->statut] ?? 'info';
                            @endphp
                            <span class="badge badge-{{ $color }}">
                                <i class="fas {{ $billet->statut == 'Valide' ? 'fa-check-circle' : ($billet->statut == 'Annulé' ? 'fa-times-circle' : 'fa-undo-alt') }} text-xs mr-1"></i>
                                {{ $billet->statut }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                            {{ $billet->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('admin.evenements.tickets.download-single', ['evenement' => $evenement->id, 'billet' => $billet->id]) }}" 
                                   class="text-green-600 hover:text-green-800 transition" title="Télécharger PDF">
                                    <i class="fas fa-download"></i>
                                </a>
                                @if($billet->statut == 'Valide')
                                <button onclick="validateTicket({{ $billet->id }}, '{{ addslashes($billet->code_unique) }}')" 
                                        class="text-blue-600 hover:text-blue-800 transition" title="Valider">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-ticket-alt text-5xl mb-3 opacity-50"></i>
                            <p>Aucun billet trouvé pour cet événement</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if(method_exists($billets, 'links'))
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $billets->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal QR Code -->
<div id="qrModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white text-center">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">QR Code du billet</h3>
            <button onclick="closeQRModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="qrCodeContainer" class="flex justify-center p-4 min-h-[200px]">
            <div class="w-48 h-48 bg-gray-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-spinner fa-spin text-2xl text-blue-500"></i>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-3">Scannez ce code pour valider l'entrée</p>
        <div class="mt-4">
            <button onclick="closeQRModal()" class="btn-secondary">Fermer</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Filtres améliorés avec client
function applyFilters() {
    const search = document.getElementById('searchBillet').value.toLowerCase().trim();
    const statut = document.getElementById('filterStatut').value;
    const type = document.getElementById('filterType').value;
    const commande = document.getElementById('filterCommande').value;
    const client = document.getElementById('filterClient').value;
    
    const rows = document.querySelectorAll('.billet-row');
    let visibleCount = 0;
    
    rows.forEach(row => {
        let show = true;
        
        // Filtre par recherche (code, spectateur, email)
        if (search) {
            const code = row.dataset.code?.toLowerCase() || '';
            const spectateurName = row.querySelector('td:nth-child(6) .text-sm')?.innerText.toLowerCase() || '';
            const spectateurEmail = row.querySelector('td:nth-child(6) .text-xs')?.innerText.toLowerCase() || '';
            const clientName = row.querySelector('td:nth-child(3) .text-sm')?.innerText.toLowerCase() || '';
            
            if (!code.includes(search) && !spectateurName.includes(search) && !spectateurEmail.includes(search) && !clientName.includes(search)) {
                show = false;
            }
        }
        
        // Filtre par statut
        if (statut && row.dataset.statut !== statut) {
            show = false;
        }
        
        // Filtre par type de billet
        if (type && row.dataset.type !== type) {
            show = false;
        }
        
        // Filtre par commande
        if (commande && row.dataset.commande !== commande) {
            show = false;
        }
        
        // Filtre par client
        if (client && row.dataset.client !== client) {
            show = false;
        }
        
        row.style.display = show ? '' : 'none';
        if (show) visibleCount++;
    });
    
    // Afficher un message si aucun résultat
    const tableBody = document.getElementById('billetsTableBody');
    const noResultRow = document.getElementById('noResultRow');
    
    if (visibleCount === 0 && rows.length > 0) {
        if (!noResultRow) {
            const tr = document.createElement('tr');
            tr.id = 'noResultRow';
            tr.innerHTML = `<td colspan="11" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-search text-4xl mb-2 opacity-50"></i>
                                <p>Aucun billet ne correspond à vos critères</p>
                             </td>`;
            tableBody.appendChild(tr);
        }
    } else if (noResultRow) {
        noResultRow.remove();
    }
}

function resetFilters() {
    document.getElementById('searchBillet').value = '';
    document.getElementById('filterStatut').value = '';
    document.getElementById('filterType').value = '';
    document.getElementById('filterCommande').value = '';
    document.getElementById('filterClient').value = '';
    
    document.querySelectorAll('.billet-row').forEach(row => {
        row.style.display = '';
    });
    
    const noResultRow = document.getElementById('noResultRow');
    if (noResultRow) noResultRow.remove();
}

// QR Code
function showQRCode(code) {
    const modal = document.getElementById('qrModal');
    const container = document.getElementById('qrCodeContainer');
    
    // API gratuite et fiable pour générer des QR codes
    const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(code)}`;
    
    container.innerHTML = `
        <div class="text-center">
            <img src="${qrCodeUrl}" alt="QR Code" class="mx-auto w-48 h-48">
            <p class="text-xs text-gray-500 mt-2">Code: ${code.substring(0, 8)}...</p>
        </div>
    `;
    
    modal.classList.remove('hidden');
}
function closeQRModal() {
    document.getElementById('qrModal').classList.add('hidden');
}

// Valider un billet (scan)
function validateTicket(billetId, code) {
    if (confirm('Valider ce billet ' + code + ' ?')) {
        const validateUrl = '{{ url('admin/billets') }}' + '/' + billetId + '/valider';
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = validateUrl;
        form.style.display = 'none';

        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = '{{ csrf_token() }}';
        form.appendChild(tokenInput);

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PATCH';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }
}

// Fermer les modals en cliquant en dehors
window.onclick = function(event) {
    const qrModal = document.getElementById('qrModal');
    if (event.target == qrModal) {
        closeQRModal();
    }
}
</script>
@endpush
@endsection