@extends('organisateur.layouts.master')

@section('title', 'Vérifier billets')
@section('page-title', 'Vérification des billets')
@section('page-description', 'Scannez ou saisissez le code du billet pour valider l\'entrée')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Billets vérifiés aujourd'hui</p>
                    <p class="text-3xl font-bold mt-1">{{ $verifiesAujourdhui ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-emerald-100 text-sm">Entrées validées</p>
                    <p class="text-3xl font-bold mt-1">{{ $entreesValidees ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Taux d'occupation</p>
                    <p class="text-3xl font-bold mt-1">{{ $tauxOccupation ?? 0 }}%</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Selection & Bulk Validation -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Valider en masse</h3>
                    <p class="text-xs text-gray-500">Sélectionnez des billets et validez-les en bulk</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form id="bulkValidationForm" method="POST" onsubmit="return submitBulkValidation(event)">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Sélectionner un événement</label>
                        <select id="eventSelect" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">-- Tous les événements --</option>
                            @foreach($myEvenements as $event)
                                <option value="{{ $event->id }}">{{ $event->titre }} ({{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Filtrer par statut</label>
                        <select id="statusFilter" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">-- Tous les statuts --</option>
                            <option value="Valide">Valide</option>
                            <option value="Utilisé">Utilisé</option>
                            <option value="Annulé">Annulé</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="button" onclick="loadTickets()" class="w-full px-4 py-2.5 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 font-semibold rounded-xl transition">
                            Charger
                        </button>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                    <table class="w-full text-sm" id="ticketsTable">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left">
                                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)" class="w-4 h-4">
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600">Code</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600">Titulaire</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600">Statut</th>
                            </tr>
                        </thead>
                        <tbody id="ticketsBody">
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500 text-xs">
                                    Sélectionnez un événement pour afficher les billets
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        <span id="selectedCount">0</span> billet(s) sélectionné(s)
                    </p>
                    <button type="submit" id="validateBtn" disabled class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-semibold rounded-xl transition-all shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Valider sélection
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <!-- Résultat de vérification -->
    @if(session('verification'))
        <div class="animate-fadeInUp">
            <div class="rounded-2xl overflow-hidden {{ session('verification')['valid'] ? 'bg-gradient-to-r from-emerald-50 to-green-50 border-l-4 border-emerald-500' : 'bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500' }} shadow-md">
                <div class="p-6">
                    <div class="flex items-start gap-5">
                        <div class="w-14 h-14 rounded-2xl {{ session('verification')['valid'] ? 'bg-emerald-500' : 'bg-red-500' }} flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if(session('verification')['valid'])
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                @endif
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-wrap justify-between items-start gap-3">
                                <div>
                                    <h3 class="text-xl font-bold {{ session('verification')['valid'] ? 'text-emerald-800' : 'text-red-800' }}">
                                        {{ session('verification')['valid'] ? '✓ Billet valide' : '✗ Billet invalide' }}
                                    </h3>
                                    <p class="{{ session('verification')['valid'] ? 'text-emerald-700' : 'text-red-700' }} mt-1">
                                        {{ session('verification')['message'] }}
                                    </p>
                                </div>
                                @if(session('verification')['valid'] && session('verification')['billet'] && session('verification')['billet']->statut == 'Valide')
                                    <form action="{{ route('organisateur.billets.valider', session('verification')['billet']->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Valider l'entrée
                                        </button>
                                    </form>
                                @endif
                            </div>
                            
                            @if(session('verification')['valid'] && session('verification')['billet'])
                                <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Détails billet -->
                                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-3">Détails du billet</p>
                                        <div class="space-y-2">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-500">Code</span>
                                                <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded text-gray-700">{{ session('verification')['billet']->code_unique }}</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-500">Type</span>
                                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-semibold">
                                                    {{ session('verification')['billet']->typeBillet->nom ?? session('verification')['billet']->type ?? 'Standard' }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-500">Statut</span>
                                                @if(session('verification')['billet']->statut == 'Valide')
                                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-semibold">
                                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                                        Non utilisé
                                                    </span>
                                                @elseif(session('verification')['billet']->statut == 'Utilisé')
                                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-semibold">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Déjà utilisé
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-red-50 text-red-700 rounded-lg text-xs font-semibold">
                                                        {{ session('verification')['billet']->statut }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Infos événement et client -->
                                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-3">Informations</p>
                                        <div class="space-y-2">
                                            <div class="flex items-start gap-2">
                                                <svg class="w-3 h-3 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <div class="flex-1">
                                                    <p class="text-xs text-gray-500">Événement</p>
                                                    <p class="text-sm font-medium text-gray-800">{{ session('verification')['billet']->evenement->titre ?? 'N/A' }}</p>
                                                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse(session('verification')['billet']->evenement->date)->format('d/m/Y H:i') }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-start gap-2">
                                                <svg class="w-3 h-3 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                <div class="flex-1">
                                                    <p class="text-xs text-gray-500">Titulaire</p>
                                                    <p class="text-sm font-medium text-gray-800">{{ session('verification')['billet']->commande->client->prenom ?? 'N/A' }} {{ session('verification')['billet']->commande->client->nom ?? '' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Historique des dernières vérifications -->
    @if(isset($historique) && $historique->count() > 0)
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Dernières vérifications</h3>
                    <p class="text-xs text-gray-500">Historique des 10 dernières entrées validées</p>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Heure</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Code billet</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Titulaire</th>
                     </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($historique as $verif)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($verif->created_at)->format('H:i:s') }}</td>
                        <td class="px-6 py-3">
                            <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">{{ substr($verif->code_unique, 0, 16) }}...</span>
                        </td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-semibold">
                                {{ $verif->type ?? 'Standard' }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $verif->client_nom ?? 'N/A' }}</td>
                     </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fadeInUp {
    animation: fadeInUp 0.5s ease-out;
}
</style>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
<script>
    let html5QrCode;
    let currentCamera = "environment";
    
    function startScanner(cameraId) {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                initScanner(cameraId);
            }).catch(err => {
                console.error("Erreur arrêt scanner:", err);
                initScanner(cameraId);
            });
        } else {
            initScanner(cameraId);
        }
    }
    
    function initScanner(cameraId) {
        html5QrCode = new Html5Qrcode("qr-reader");
        const config = { 
            fps: 10, 
            qrbox: { width: 300, height: 300 },
            aspectRatio: 1.0
        };
        
        html5QrCode.start(
            { facingMode: cameraId },
            config,
            (decodedText) => {
                // Scan réussi
                document.getElementById('code').value = decodedText;
                document.getElementById('verifyForm').submit();
            },
            (errorMessage) => {
                // Ignorer les erreurs de scan en continu
                // console.log(errorMessage);
            }
        ).catch(err => {
            console.error("Erreur démarrage caméra:", err);
            document.getElementById('qr-reader').innerHTML = `
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-gray-500">Impossible d'accéder à la caméra</p>
                    <p class="text-xs text-gray-400 mt-1">Veuillez vérifier les permissions</p>
                </div>
            `;
        });
    }
    
    function switchCamera() {
        currentCamera = currentCamera === "environment" ? "user" : "environment";
        startScanner(currentCamera);
    }
    
    // Démarrer le scanner au chargement
    startScanner("environment");
    
    // Nettoyer le scanner lors de la navigation
    window.addEventListener('beforeunload', function() {
        if (html5QrCode && html5QrCode.isScanning) {
            html5QrCode.stop();
        }
    });

    // Bulk validation functions
    function loadTickets() {
        const eventId = document.getElementById('eventSelect').value;
        const status = document.getElementById('statusFilter').value;

        if (!eventId) {
            document.getElementById('ticketsBody').innerHTML = `
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-500 text-xs">
                        Sélectionnez un événement pour afficher les billets
                    </td>
                </tr>
            `;
            return;
        }

        fetch(`/organisateur/billets/event/${eventId}/tickets`)
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert('Erreur : ' + data.message);
                    return;
                }

                let billets = data.billets;
                
                // Filter by status if selected
                if (status) {
                    billets = billets.filter(b => b.statut === status);
                }

                if (billets.length === 0) {
                    document.getElementById('ticketsBody').innerHTML = `
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500 text-xs">
                                Aucun billet trouvé
                            </td>
                        </tr>
                    `;
                    return;
                }

                let html = '';
                billets.forEach(billet => {
                    const statusColor = billet.statut === 'Valide' ? 'bg-emerald-50 text-emerald-700' :
                                       billet.statut === 'Utilisé' ? 'bg-gray-100 text-gray-600' :
                                       'bg-red-50 text-red-700';
                    
                    html += `
                        <tr class="border-t border-gray-200 hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <input type="checkbox" name="billet_ids" value="${billet.id}" 
                                       onchange="updateSelectCount()" 
                                       class="billet-checkbox w-4 h-4">
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">${billet.code_unique.substring(0, 16)}</span>
                            </td>
                            <td class="px-4 py-3">${billet.type_billet?.nom || 'Standard'}</td>
                            <td class="px-4 py-3">${billet.commande?.client?.prenom || ''} ${billet.commande?.client?.nom || 'N/A'}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusColor}">
                                    ${billet.statut}
                                </span>
                            </td>
                        </tr>
                    `;
                });

                document.getElementById('ticketsBody').innerHTML = html;
                document.getElementById('selectAll').checked = false;
                updateSelectCount();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors du chargement des billets');
            });
    }

    function toggleSelectAll(checkbox) {
        const checkboxes = document.querySelectorAll('.billet-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = checkbox.checked;
        });
        updateSelectCount();
    }

    function updateSelectCount() {
        const checkboxes = document.querySelectorAll('.billet-checkbox:checked');
        const count = checkboxes.length;
        document.getElementById('selectedCount').textContent = count;
        document.getElementById('validateBtn').disabled = count === 0;

        // Update select all checkbox state
        const allCheckboxes = document.querySelectorAll('.billet-checkbox');
        const selectAllCheckbox = document.getElementById('selectAll');
        selectAllCheckbox.checked = allCheckboxes.length > 0 && allCheckboxes.length === checkboxes.length;
    }

    function submitBulkValidation(event) {
        event.preventDefault();
        
        const checkboxes = document.querySelectorAll('.billet-checkbox:checked');
        if (checkboxes.length === 0) {
            alert('Veuillez sélectionner au moins un billet');
            return false;
        }

        // Collect selected ticket IDs
        const billetIds = Array.from(checkboxes).map(cb => cb.value);

        // Create FormData and submit
        const formData = new FormData();
        formData.append('_token', document.querySelector('[name="_token"]').value);
        
        // Add billet_ids as array
        billetIds.forEach(id => {
            formData.append('billet_ids[]', id);
        });

        fetch('{{ route("organisateur.billets.validate-multiple") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
            }
        })
        .then(response => response.text())
        .then(html => {
            // Reload the page to show updated stats
            window.location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la validation');
        });

        return false;
    }

</script>
@endpush
@endsection