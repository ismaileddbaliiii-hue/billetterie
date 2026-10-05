@extends('client.layouts.master')

@section('title', 'Billets pour ' . $evenement->titre)
@section('page-title', 'Mes Billets')
@section('page-description', $evenement->titre)

@section('content')
<div class="space-y-6">
    <!-- Event Header Card -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-lg overflow-hidden">
        <div class="relative">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mt-32 -mr-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -mb-24 -ml-24"></div>
            <div class="relative z-10 p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-ticket-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">{{ $evenement->titre }}</h1>
                            <div class="flex flex-wrap items-center gap-3 mt-1 text-blue-100 text-sm">
                                <div class="flex items-center gap-1">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>{{ \Carbon\Carbon::parse($evenement->date)->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="w-1 h-1 bg-blue-300 rounded-full"></div>
                                <div class="flex items-center gap-1">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $evenement->lieu->nom ?? 'Lieu indisponible' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2 text-center">
                            <p class="text-xs text-blue-100">Total billets</p>
                            <p class="text-2xl font-bold text-white">{{ $billets->count() }}</p>
                        </div>
                        <a href="{{ route('client.billets.index', ['evenement_id' => $evenement->id]) }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 text-white text-sm font-semibold rounded-xl transition-all duration-200 backdrop-blur-sm">
                            <i class="fas fa-arrow-left"></i>
                            Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($billets->count() > 0)
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-4">
            <div class="space-y-1">
                <p class="text-sm text-gray-500">Total billets</p>
                <p class="text-2xl font-bold text-gray-900">{{ $billets->count() }}</p>
            </div>
            <a href="{{ route('client.billets.event.download-all', $evenement->id) }}" class="inline-flex items-center gap-2 px-5 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-md">
                <i class="fas fa-download"></i>
                Télécharger tous les billets (PDF)
            </a>
        </div>
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm">Billets valides</p>
                        <p class="text-2xl font-bold">{{ $billets->where('statut', 'Valide')->count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-white"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">Prix total</p>
                        <p class="text-2xl font-bold">{{ number_format($billets->sum('prix'), 2) }} DH</p>
                    </div>
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-white"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">Types de billets</p>
                        <p class="text-2xl font-bold">{{ $billets->groupBy(function($b) { return $b->typeBillet->nom ?? $b->type ?? 'Standard'; })->count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tags text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tickets Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-ticket-alt text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Mes billets</h2>
                        <p class="text-xs text-gray-500">{{ $billets->count() }} billet(s) disponible(s)</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Prix</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Code unique</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">QR Code</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($billets as $billet)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $billet->typeBillet->nom ?? $billet->type ?? 'Standard' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ number_format($billet->prix, 2, ',', ' ') }} DH</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-700">{{ $billet->code_unique }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($billet->statut == 'Valide')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Valide</span>
                                    @elseif($billet->statut == 'Utilisé')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">Utilisé</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">{{ $billet->statut }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    @if($billet->statut == 'Valide')
                                        <button onclick="showQRCode('{{ $billet->code_unique }}', '{{ $billet->id }}', '{{ addslashes($evenement->titre) }}')" class="text-blue-600 hover:text-blue-800 font-medium">Afficher</button>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($billet->statut == 'Valide')
                                        <a href="{{ route('client.billets.download', $billet->id) }}" 
                                           class="inline-flex items-center gap-2 px-3 py-2 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 transition">PDF</a>
                                    @else
                                        <span class="text-gray-400">Indisponible</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Download All Button -->
        @if($billets->where('statut', 'Valide')->count() > 0)
            <div class="flex justify-center">
                <a href="{{ route('client.billets.event.download-all', $evenement->id) }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md">
                    <i class="fas fa-download"></i>
                    Télécharger tous les billets (PDF)
                </a>
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-ticket-alt text-gray-400 text-4xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Aucun billet trouvé</h3>
            <p class="text-gray-500 mb-6">Vous n'avez pas encore acheté de billets pour cet événement.</p>
            <a href="{{ route('client.evenements.show', $evenement->id) }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md">
                <i class="fas fa-calendar-alt"></i>
                Voir l'événement
            </a>
        </div>
    @endif
</div>

<!-- QR Code Modal -->
<div id="qrModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4 transform transition-all scale-95 opacity-0" id="qrModalContent">
        <div class="relative">
            <div class="absolute -top-12 left-1/2 transform -translate-x-1/2">
                <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-qrcode text-white text-3xl"></i>
                </div>
            </div>
            <div class="pt-12 p-6">
                <div class="text-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800" id="qrModalTitle">QR Code du billet</h3>
                    <p class="text-xs text-gray-500 mt-1" id="qrModalEvent"></p>
                </div>
                <div id="qrModalCode" class="flex justify-center p-4 bg-gray-50 rounded-xl">
                    <!-- QR Code will be inserted here -->
                </div>
                <p class="text-center text-sm text-gray-600 mt-4 flex items-center justify-center gap-2">
                    <i class="fas fa-info-circle text-blue-500"></i>
                    Présentez ce QR Code à l'entrée
                </p>
                <button onclick="closeQRModal()" 
                        class="mt-5 w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-2.5 rounded-xl transition-all duration-200 shadow-md">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs2-fix@1.0.0/qrcode.min.js"></script>
<script>
    let currentQRModal = null;
    
    function showQRCode(code, id, eventTitle) {
        let modal = document.getElementById('qrModal');
        let modalContent = document.getElementById('qrModalContent');
        let modalCode = document.getElementById('qrModalCode');
        let modalEvent = document.getElementById('qrModalEvent');
        
        if (modalEvent) {
            modalEvent.textContent = eventTitle || 'Billet d\'événement';
        }
        
        modalCode.innerHTML = '';
        
        let qrDiv = document.createElement('div');
        qrDiv.id = 'modal-qr-' + id;
        modalCode.appendChild(qrDiv);
        
        new QRCode(qrDiv, {
            text: code,
            width: 200,
            height: 200,
            colorDark: '#1e293b',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
    
    function closeQRModal() {
        let modal = document.getElementById('qrModal');
        let modalContent = document.getElementById('qrModalContent');
        
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 300);
    }
    
    // Load QR codes for visible tickets
    document.addEventListener('DOMContentLoaded', function() {
        @foreach($billets as $billet)
            @if($billet->statut == 'Valide')
                let container = document.getElementById('qr-{{ $billet->id }}');
                if (container) {
                    container.innerHTML = '';
                    let qrDiv = document.createElement('div');
                    qrDiv.className = 'inline-block';
                    container.appendChild(qrDiv);
                    
                    new QRCode(qrDiv, {
                        text: '{{ $billet->code_unique }}',
                        width: 80,
                        height: 80,
                        colorDark: '#1e293b',
                        colorLight: '#ffffff',
                        correctLevel: QRCode.CorrectLevel.H
                    });
                }
            @endif
        @endforeach
    });
    
    window.onclick = function(event) {
        let modal = document.getElementById('qrModal');
        if (event.target == modal) {
            closeQRModal();
        }
    }
</script>
@endpush
@endsection