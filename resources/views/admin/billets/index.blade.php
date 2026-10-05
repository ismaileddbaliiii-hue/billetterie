@extends('admin.layouts.master')

@section('title', 'Gestion des Billets')
@section('page-title', 'Billets')
@section('page-description', 'Gestion et contrôle des billets vendus')

@section('page-actions')
    
    <a href="{{ route('admin.billets.export', request()->query()) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-all duration-200 shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
        </svg>
        Exporter CSV
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filtres & Recherche -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-base font-medium text-gray-900">Filtrer les billets</h3>
        </div>
        <form method="GET" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Recherche -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                    <input type="text" name="search" placeholder="Code billet, email..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           value="{{ request('search') }}">
                    <p class="text-xs text-gray-500 mt-1">Recherche par code ou email client</p>
                </div>

                <!-- Événement -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Événement</label>
                    <select name="evenement_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Tous les événements</option>
                        @foreach($evenements as $evenement)
                            <option value="{{ $evenement->id }}" {{ request('evenement_id') == $evenement->id ? 'selected' : '' }}>
                                {{ $evenement->titre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Client -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Client</label>
                    <select name="client_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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

                <!-- Statut -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Validation</label>
                    <select name="valide" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Tous</option>
                        <option value="utilise" {{ request('valide') == 'utilise' ? 'selected' : '' }}>Utilisés</option>
                        <option value="disponible" {{ request('valide') == 'disponible' ? 'selected' : '' }}>Disponibles</option>
                        <option value="annule" {{ request('valide') == 'annule' ? 'selected' : '' }}>Annulés</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Rechercher
                    </button>
                    <a href="{{ route('admin.billets.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Réinitialiser
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tableau des billets -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                    <h3 class="text-base font-medium text-gray-900">
                        Billets
                        <span class="text-sm font-normal text-gray-500">({{ $billets->total() }} total)</span>
                    </h3>
                </div>
                
                <!-- Actions groupées -->
                <div id="bulk-actions" class="flex items-center gap-2 opacity-50 pointer-events-none">
                    <span class="text-sm text-gray-600 mr-2"><span id="selected-count">0</span> sélectionné(s)</span>
                    </button>
                    <button type="button" 
                            id="select-available"
                            class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Disponibles
                    </button>
                    <button type="button" 
                            id="deselect-all"
                            class="inline-flex items-center px-3 py-1.5 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Désélectionner
                    </button>
                    <form id="bulk-validate-form" action="{{ route('admin.billets.bulk-validate') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="billet_ids" id="bulk-validate-ids">
                        <button type="submit" 
                                class="inline-flex items-center px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
                                onclick="return confirm('Marquer les billets sélectionnés comme utilisés ?')">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Valider
                        </button>
                    </form>
                    <form id="bulk-delete-form" action="{{ route('admin.billets.bulk-delete') }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="billet_ids" id="bulk-delete-ids">
                        <button type="submit" 
                                class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer les billets sélectionnés ?')">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Événement</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Validation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Créé</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($billets as $billet)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <input type="checkbox" class="billet-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" 
                                       value="{{ $billet->id }}" data-status="{{ $billet->statut }}">
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.billets.show', $billet) }}" class="text-blue-600 hover:text-blue-800 font-mono text-sm font-medium">
                                    {{ $billet->code }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.evenements.show', $billet->evenement) }}" class="text-gray-900 hover:text-blue-600 text-sm">
                                    {{ $billet->evenement?->titre ?? 'N/A' }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    @if($billet->commande?->client)
                                        <p class="font-medium text-gray-900">{{ $billet->commande->client->prenom }} {{ $billet->commande->client->nom }}</p>
                                        <p class="text-gray-500 text-xs">{{ $billet->commande->client->email }}</p>
                                    @else
                                        <span class="text-gray-400">Client supprimé</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $billet->typeBillet?->nom ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                @if(in_array($billet->statut, ['Valide', 'Utilisé']))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>
                                        {{ $billet->statut ?? 'Valide' }}
                                    </span>
                                @elseif($billet->statut === 'Annulé')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1"></span>
                                        Annulé
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1"></span>
                                        {{ $billet->statut ?? 'Inconnu' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($billet->statut === 'Utilisé')
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Validé
                                        </span>
                                        @if($billet->date_validation)
                                            <span class="text-xs text-gray-500">{{ $billet->date_validation->format('d/m/Y H:i') }}</span>
                                        @endif
                                    </div>
                                @elseif($billet->statut === 'Valide')
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Disponible
                                        </span>
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        {{ $billet->statut }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                {{ $billet->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.billets.show', $billet) }}" 
                                       class="text-gray-600 hover:text-blue-600 p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                       title="Voir les détails">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.billets.download', $billet) }}" 
                                       class="text-gray-600 hover:text-green-600 p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                       title="Télécharger le PDF">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                    </a>
                                    @if($billet->statut !== 'Utilisé')
                                        <form action="{{ route('admin.billets.valider', $billet) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="text-gray-600 hover:text-purple-600 p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                                    title="Marquer comme validé"
                                                    onclick="return confirm('Marquer ce billet comme validé ?')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.billets.destroy', $billet) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-gray-600 hover:text-red-600 p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                                title="Supprimer"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce billet ?')">
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
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-medium">Aucun billet trouvé</p>
                                <p class="text-gray-400 text-sm mt-1">Essayez de modifier vos filtres de recherche</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $billets->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const billetCheckboxes = document.querySelectorAll('.billet-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    const bulkValidateIds = document.getElementById('bulk-validate-ids');
    const bulkDeleteIds = document.getElementById('bulk-delete-ids');

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.billet-checkbox:checked');
        const count = checkedBoxes.length;
        
        selectedCount.textContent = count;
        
        if (count > 0) {
            bulkActions.classList.remove('opacity-50', 'pointer-events-none');
            bulkActions.classList.add('opacity-100');
            const ids = Array.from(checkedBoxes).map(cb => cb.value);
            bulkValidateIds.value = JSON.stringify(ids);
            bulkDeleteIds.value = JSON.stringify(ids);
        } else {
            bulkActions.classList.add('opacity-50', 'pointer-events-none');
            bulkActions.classList.remove('opacity-100');
        }
    }

    function updateSelectAllState() {
        const totalCheckboxes = billetCheckboxes.length;
        const checkedBoxes = document.querySelectorAll('.billet-checkbox:checked').length;
        
        if (totalCheckboxes === 0) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        } else {
            selectAllCheckbox.checked = checkedBoxes === totalCheckboxes;
            selectAllCheckbox.indeterminate = checkedBoxes > 0 && checkedBoxes < totalCheckboxes;
        }
    }

    // Gestion de la case "Tout sélectionner"
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            billetCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });
    }

    // Gestion des cases individuelles
    billetCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAllState();
            updateBulkActions();
        });
    });

    // Bouton "Sélectionner tous visibles"
    const selectAllVisibleBtn = document.getElementById('select-all-visible');
    if (selectAllVisibleBtn) {
        selectAllVisibleBtn.addEventListener('click', function() {
            billetCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            }
            updateBulkActions();
        });
    }

    // Bouton "Sélectionner disponibles"
    const selectAvailableBtn = document.getElementById('select-available');
    if (selectAvailableBtn) {
        selectAvailableBtn.addEventListener('click', function() {
            billetCheckboxes.forEach(checkbox => {
                checkbox.checked = checkbox.getAttribute('data-status') === 'Valide';
            });
            updateSelectAllState();
            updateBulkActions();
        });
    }

    // Bouton "Désélectionner"
    const deselectAllBtn = document.getElementById('deselect-all');
    if (deselectAllBtn) {
        deselectAllBtn.addEventListener('click', function() {
            billetCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
            updateBulkActions();
        });
    }

    // Initialisation
    updateSelectAllState();
    updateBulkActions();
});
</script>
@endpush