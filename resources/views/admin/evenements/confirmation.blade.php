@extends('client.layouts.master')

@section('title', 'Confirmation de commande')
@section('page-title', 'Commande confirmée !')
@section('page-description', 'Votre commande a été traitée avec succès')

@section('content')
<div class="space-y-6">
    <!-- Success Animation Card -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="relative">
            <div class="absolute top-0 right-0 w-64 h-64 bg-green-500/5 rounded-full -mt-32 -mr-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-500/5 rounded-full -mb-24 -ml-24"></div>
            
            <div class="relative z-10 p-8 text-center">
                <!-- Success Icon -->
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full mb-6 shadow-lg animate-pulse">
                    <i class="fas fa-check-circle text-white text-5xl"></i>
                </div>
                
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    Merci pour votre commande !
                </h1>
                <p class="text-gray-500 text-lg mb-6">
                    Votre paiement a été traité avec succès
                </p>
                
                <!-- Order Reference Badge -->
                <div class="inline-flex items-center gap-3 px-6 py-3 bg-gray-100 rounded-xl mb-6">
                    <i class="fas fa-receipt text-gray-500"></i>
                    <span class="text-gray-600">Référence commande :</span>
                    <span class="font-mono font-bold text-blue-600 text-lg">{{ $commande->reference ?? '#' . $commande->id }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Order Summary Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-receipt text-white"></i>
                    </div>
                    <h2 class="text-lg font-bold text-gray-800">Récapitulatif de la commande</h2>
                </div>
            </div>
            
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Date de commande</span>
                    <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($commande->date)->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Méthode de paiement</span>
                    <span class="font-medium text-gray-800">{{ $commande->paiement->prestataire ?? 'Carte bancaire' }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Statut</span>
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                        {{ $commande->statut }}
                    </span>
                </div>
                <div class="flex justify-between items-center pt-4 mt-2 border-t border-gray-200">
                    <span class="text-xl font-bold text-gray-800">Total payé</span>
                    <span class="text-2xl font-bold text-blue-600">{{ number_format($commande->montant_total, 2, ',', ' ') }} DH</span>
                </div>
            </div>
        </div>

        <!-- Next Steps Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-ticket-alt text-white"></i>
                    </div>
                    <h2 class="text-lg font-bold text-gray-800">Prochaines étapes</h2>
                </div>
            </div>
            
            <div class="p-6 space-y-4">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-blue-600 font-bold text-sm">1</span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Consultez vos billets</p>
                        <p class="text-sm text-gray-500 mt-1">Vos billets sont disponibles dans la section "Mes billets"</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-blue-600 font-bold text-sm">2</span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Téléchargez vos billets</p>
                        <p class="text-sm text-gray-500 mt-1">Présentez le QR Code à l'entrée de l'événement</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-blue-600 font-bold text-sm">3</span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Profitez de l'événement !</p>
                        <p class="text-sm text-gray-500 mt-1">Une confirmation a été envoyée à votre adresse email</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('client.billets.index') }}" 
           class="flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
            <i class="fas fa-qrcode"></i>
            Voir mes billets
        </a>
        
        <a href="{{ route('client.commandes.show', $commande->id) }}" 
           class="flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
            <i class="fas fa-receipt"></i>
            Détails de la commande
        </a>
        
        <a href="{{ route('client.evenements.index') }}" 
           class="flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
            <i class="fas fa-calendar-alt"></i>
            Explorer d'autres événements
        </a>
    </div>

    <!-- Share / Social Section -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 text-center">
        <div class="flex items-center justify-center gap-2 mb-3">
            <i class="fas fa-share-alt text-blue-600"></i>
            <h3 class="font-semibold text-gray-800">Partagez votre expérience</h3>
        </div>
        <p class="text-sm text-gray-600 mb-4">
            Invitez vos amis à découvrir les prochains événements !
        </p>
        <div class="flex justify-center gap-3">
            <button onclick="shareOnFacebook()" class="p-2 bg-white rounded-full shadow-md hover:shadow-lg transition-all hover:scale-110">
                <i class="fab fa-facebook-f text-blue-600 text-xl"></i>
            </button>
            <button onclick="shareOnTwitter()" class="p-2 bg-white rounded-full shadow-md hover:shadow-lg transition-all hover:scale-110">
                <i class="fab fa-twitter text-blue-400 text-xl"></i>
            </button>
            <button onclick="shareOnWhatsApp()" class="p-2 bg-white rounded-full shadow-md hover:shadow-lg transition-all hover:scale-110">
                <i class="fab fa-whatsapp text-green-500 text-xl"></i>
            </button>
            <button onclick="copyLink()" class="p-2 bg-white rounded-full shadow-md hover:shadow-lg transition-all hover:scale-110">
                <i class="fas fa-link text-gray-600 text-xl"></i>
            </button>
        </div>
    </div>

    <!-- Email Confirmation Note -->
    <div class="bg-amber-50 border-l-4 border-amber-500 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <i class="fas fa-envelope text-amber-600 text-xl mt-0.5"></i>
            <div>
                <p class="text-sm font-semibold text-amber-800">Email de confirmation envoyé</p>
                <p class="text-sm text-amber-700">
                    Un email de confirmation a été envoyé à <strong>{{ auth()->user()->email }}</strong>.
                    Consultez votre boîte de réception pour plus de détails.
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function shareOnFacebook() {
        window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(window.location.href), '_blank', 'width=600,height=400');
    }
    
    function shareOnTwitter() {
        window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent('Je viens d\'acheter des billets sur TicketFlow !') + '&url=' + encodeURIComponent(window.location.href), '_blank', 'width=600,height=400');
    }
    
    function shareOnWhatsApp() {
        window.open('https://wa.me/?text=' + encodeURIComponent('Je viens d\'acheter des billets sur TicketFlow ! ' + window.location.href), '_blank');
    }
    
    function copyLink() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Lien copié dans le presse-papier !');
        });
    }
    
    // Confetti effect on page load
    function createConfetti() {
        const colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
        for (let i = 0; i < 100; i++) {
            const confetti = document.createElement('div');
            confetti.className = 'fixed pointer-events-none';
            confetti.style.cssText = `
                position: fixed;
                width: 10px;
                height: 10px;
                background: ${colors[Math.floor(Math.random() * colors.length)]};
                top: -10px;
                left: ${Math.random() * window.innerWidth}px;
                opacity: 0.7;
                border-radius: 2px;
                animation: fall ${Math.random() * 3 + 2}s linear forwards;
                z-index: 9999;
            `;
            document.body.appendChild(confetti);
            
            setTimeout(() => confetti.remove(), 5000);
        }
    }
    
    // Add animation style
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fall {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(${window.innerHeight}px) rotate(360deg);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
    
    // Trigger confetti on page load
    setTimeout(createConfetti, 500);
</script>
@endpush
@endsection