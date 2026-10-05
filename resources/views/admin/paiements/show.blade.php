{{-- resources/views/admin/paiements/show.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Détails du paiement')
@section('page-title', 'Détails du paiement')
@section('page-description', 'Transaction #' . $paiement->id)

@section('page-actions')
    <a href="{{ route('admin.paiements.index', request()->query()) }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
    @if($paiement->statut == 'Échec')
        <form action="{{ route('admin.paiements.retry', $paiement) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="btn-primary bg-yellow-600 hover:bg-yellow-700">
                <i class="fas fa-sync-alt"></i> Réessayer
            </button>
        </form>
    @endif
    
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Informations principales -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Informations transaction -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informations de la transaction</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">ID Transaction</p>
                    <p class="font-medium">#{{ $paiement->id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Référence</p>
                    <p class="font-mono text-sm">{{ $paiement->reference }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">ID Transaction Prestataire</p>
                    <p class="font-mono text-sm">{{ $paiement->transaction_id ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Prestataire</p>
                    <div class="flex items-center space-x-2">
                        @if($paiement->prestataire == 'Stripe')
                            <i class="fab fa-stripe text-blue-600 text-xl"></i>
                        @elseif($paiement->prestataire == 'PayPal')
                            <i class="fab fa-paypal text-blue-800 text-xl"></i>
                        @endif
                        <span>{{ $paiement->prestataire }}</span>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Montant</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($paiement->montant, 2, ',', ' ') }} DH</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Statut</p>
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
                </div>
                <div>
                    <p class="text-sm text-gray-500">Date de la transaction</p>
                    <p>{{ $paiement->created_at->format('d/m/Y H:i:s') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Dernière mise à jour</p>
                    <p>{{ $paiement->updated_at->format('d/m/Y H:i:s') }}</p>
                </div>
            </div>
        </div>
        
        <!-- Informations commande -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Commande associée</h3>
            @if($paiement->commande)
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">ID Commande</p>
                        <a href="{{ route('admin.commandes.show', $paiement->commande) }}" class="text-blue-600 hover:text-blue-800">
                            #{{ $paiement->commande->id }}
                        </a>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Référence</p>
                        <p class="font-mono">{{ $paiement->commande->reference }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Client</p>
                        <p>{{ $paiement->commande->client->prenom ?? 'N/A' }} {{ $paiement->commande->client->nom ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $paiement->commande->client->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Statut commande</p>
                        <span class="badge badge-{{ $paiement->commande->statut == 'Confirmée' ? 'success' : 'warning' }}">
                            {{ $paiement->commande->statut }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total commande</p>
                        <p class="font-semibold">{{ number_format($paiement->commande->montant_total, 2, ',', ' ') }} DH</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Date commande</p>
                        <p>{{ $paiement->commande->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Aucune commande associée</p>
            @endif
        </div>
    </div>
    
    <!-- Sidebar - Informations supplémentaires -->
    <div class="space-y-6">
        <!-- Statistiques -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informations</h3>
            <div class="space-y-3">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500">Temps écoulé</p>
                    <p class="text-xl font-bold">{{ $paiement->created_at->diffForHumans() }}</p>
                </div>
                
                @if($paiement->statut == 'Payé')
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
                            <p class="text-sm text-green-800">Cette transaction a été traitée avec succès.</p>
                        </div>
                    </div>
                @elseif($paiement->statut == 'Échec')
                    <div class="bg-red-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl mr-3"></i>
                            <p class="text-sm text-red-800">Cette transaction a échoué. Vous pouvez réessayer.</p>
                        </div>
                        @if($paiement->erreur_message)
                            <p class="text-xs text-red-600 mt-2">Erreur: {{ $paiement->erreur_message }}</p>
                        @endif
                    </div>
                @elseif($paiement->statut == 'Remboursé')
                    <div class="bg-purple-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-undo-alt text-purple-600 text-xl mr-3"></i>
                            <p class="text-sm text-purple-800">Cette transaction a été remboursée.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Données JSON (si disponibles) -->
        @if($paiement->reponse_prestataire)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Réponse du prestataire</h3>
            <pre class="text-xs bg-gray-50 p-3 rounded-lg overflow-x-auto">{{ json_encode($paiement->reponse_prestataire, JSON_PRETTY_PRINT) }}</pre>
        </div>
        @endif
    </div>
</div>
@endsection