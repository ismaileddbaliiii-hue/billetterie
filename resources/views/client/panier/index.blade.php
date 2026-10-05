@extends('client.layouts.master')

@section('title', 'Mon Panier')

@section('main-class', 'flex-1 overflow-y-auto bg-gray-50 min-h-screen')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <div class="max-w-6xl mx-auto px-3 sm:px-4 lg:px-6 py-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm mb-6 animate-fade-in">
            <a href="{{ route('client.evenements.index') }}" class="text-gray-500 hover:text-blue-600 transition-all duration-300 hover:translate-x-0.5">
                <i class="fas fa-home mr-1"></i> Accueil
            </a>
            <i class="fas fa-chevron-right text-xs text-gray-400"></i>
            <span class="text-gray-800 font-semibold">Mon panier</span>
        </nav>

        <!-- Page Header -->
        <div class="mb-8 animate-slide-up">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-shopping-cart text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">Mon panier</h1>
                    <p class="text-gray-500 text-sm mt-1">Revoyez vos billets avant de finaliser votre commande</p>
                </div>
            </div>
        </div>

        @if(!empty($panier) && count($panier) > 0)
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 p-5 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Articles</p>
                            <p class="text-3xl font-bold text-gray-900">{{ collect($panier)->sum('quantite') }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-shopping-cart text-white text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 p-5 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Types de billets</p>
                            <p class="text-3xl font-bold text-gray-900">{{ count($panier) }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-tags text-white text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 p-5 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Total TTC</p>
                            <p class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                {{ number_format($montantTotal, 2, ',', ' ') }} <span class="text-base">DH</span>
                            </p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-money-bill-wave text-white text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Table -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden animate-slide-up animation-delay-200">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Billet</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Quantité</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Prix unitaire</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Sous-total</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($panier as $idx => $item)
                                <tr class="group hover:bg-gradient-to-r hover:from-blue-50/50 hover:to-transparent transition-all duration-300 transform hover:scale-[1.01]">
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300">
                                                <i class="fas fa-ticket-alt text-blue-600 text-lg"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $item['type_nom'] }}</p>
                                                <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                                    {{ $item['evenement_titre'] ?? 'Événement' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center justify-center gap-3">
                                            <form action="{{ route('client.panier.modifier-quantite', $idx) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="quantite" value="{{ max(1, $item['quantite'] - 1) }}">
                                                <button type="submit" 
                                                        class="w-9 h-9 rounded-xl border-2 border-gray-200 hover:border-blue-400 hover:bg-blue-50 text-gray-600 hover:text-blue-600 transition-all duration-200 {{ $item['quantite'] <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                        {{ $item['quantite'] <= 1 ? 'disabled' : '' }}>
                                                    <i class="fas fa-minus text-xs"></i>
                                                </button>
                                            </form>
                                            <span class="w-12 text-center font-bold text-gray-800 text-lg">{{ $item['quantite'] }}</span>
                                            <form action="{{ route('client.panier.modifier-quantite', $idx) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="quantite" value="{{ $item['quantite'] + 1 }}">
                                                <button type="submit" class="w-9 h-9 rounded-xl border-2 border-gray-200 hover:border-blue-400 hover:bg-blue-50 text-gray-600 hover:text-blue-600 transition-all duration-200">
                                                    <i class="fas fa-plus text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <span class="text-gray-700 font-medium">{{ number_format($item['prix'], 2, ',', ' ') }} DH</span>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <span class="font-bold text-lg bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">{{ number_format($item['sous_total'], 2, ',', ' ') }} DH</span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <form action="{{ route('client.panier.supprimer', $idx) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-9 h-9 rounded-xl bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-600 transition-all duration-200 group-hover:scale-110"
                                                    onclick="return confirm('Supprimer cet article du panier ?')">
                                                <i class="fas fa-trash-alt text-sm"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Cart Footer -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-5 border-t-2 border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">
                                <i class="fas fa-ticket-alt text-blue-600"></i>
                            </div>
                            <span class="text-gray-700">
                                <strong class="text-gray-900 text-lg">{{ collect($panier)->sum('quantite') }}</strong> billet(s) dans votre panier
                            </span>
                        </div>
                        
                        <div class="flex flex-col items-end gap-3">
                            <div class="flex items-baseline gap-4">
                                <span class="text-gray-500 text-lg">Total TTC</span>
                                <span class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                    {{ number_format($montantTotal, 2, ',', ' ') }} DH
                                </span>
                            </div>
                            <a href="{{ route('client.panier.paiement') }}" 
                               class="group inline-flex items-center gap-2 px-8 py-3.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class="fas fa-credit-card group-hover:scale-110 transition-transform duration-200"></i>
                                Procéder au paiement
                                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-200"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Actions Footer -->
                <div class="px-6 py-4 border-t border-gray-100 bg-white flex flex-wrap justify-between items-center gap-3">
                    <a href="{{ route('client.evenements.index') }}" 
                       class="group inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 transition-all duration-300">
                        <i class="fas fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform duration-200"></i>
                        <span>Continuer mes achats</span>
                    </a>
                    
                    <form action="{{ route('client.panier.vider') }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="group inline-flex items-center gap-2 text-gray-400 hover:text-red-600 transition-all duration-300 px-3 py-1.5 rounded-lg hover:bg-red-50"
                                onclick="return confirm('⚠️ Attention : Vider tout le panier ? Cette action est irréversible.')">
                            <i class="fas fa-trash-alt group-hover:scale-110 transition-transform duration-200"></i>
                            <span class="text-sm">Vider le panier</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Security Assurance -->
            <div class="mt-8 bg-white rounded-2xl shadow-lg border border-gray-100 p-5 animate-slide-up animation-delay-400">
                <div class="flex flex-wrap items-center justify-center gap-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-lock text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Paiement sécurisé</p>
                            <p class="text-xs text-gray-500">SSL 256 bits</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-truck-fast text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Livraison rapide</p>
                            <p class="text-xs text-gray-500">Billets envoyés par email</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-headset text-purple-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Support client</p>
                            <p class="text-xs text-gray-500">7j/7 - 24h/24</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart State -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 py-20 px-4 text-center animate-fade-in">
                <div class="relative">
                    <div class="w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                        <i class="fas fa-shopping-cart text-gray-400 text-5xl"></i>
                    </div>
                    <div class="absolute top-0 right-1/3 transform translate-x-1/2 -translate-y-1/2">
                        <div class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center shadow-lg animate-bounce">
                            <i class="fas fa-frown text-white text-xl"></i>
                        </div>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-3">Votre panier est vide</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">
                    Vous n'avez pas encore ajouté de billets à votre panier.<br>
                    Découvrez nos événements et commencez vos achats !
                </p>
                <a href="{{ route('client.evenements.index') }}" 
                   class="group inline-flex items-center gap-2 px-8 py-3.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-calendar-alt group-hover:scale-110 transition-transform duration-200"></i>
                    Découvrir les événements
                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-200"></i>
                </a>
            </div>

            <!-- Recommended Events -->
            @if(isset($evenementsRecommandes) && $evenementsRecommandes->count() > 0)
                <div class="mt-12 animate-slide-up animation-delay-200">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center shadow-md">
                                <i class="fas fa-star text-white"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Événements recommandés pour vous</h2>
                        </div>
                        <a href="{{ route('client.evenements.index') }}" class="group text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1">
                            Voir tous 
                            <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform duration-200"></i>
                        </a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($evenementsRecommandes as $evenement)
                            <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl border border-gray-100 overflow-hidden transition-all duration-300 transform hover:-translate-y-2">
                                <div class="relative h-48 bg-gradient-to-br from-blue-500 to-purple-600 overflow-hidden">
                                    @if($evenement->image)
                                        <img src="{{ asset('storage/' . $evenement->image) }}" alt="{{ $evenement->titre }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-calendar-alt text-white text-6xl opacity-50"></i>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                                    <div class="absolute bottom-3 left-3 right-3">
                                        <span class="inline-block px-2 py-1 bg-white/90 backdrop-blur-sm rounded-lg text-xs font-semibold text-gray-800">
                                            <i class="far fa-clock mr-1"></i>
                                            {{ \Carbon\Carbon::parse($evenement->date)->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-5">
                                    <h3 class="font-bold text-gray-900 text-lg mb-2 group-hover:text-blue-600 transition-colors duration-300">{{ $evenement->titre }}</h3>
                                    <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $evenement->description ?? 'Découvrez cet événement exceptionnel' }}</p>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-xs text-gray-500">À partir de</p>
                                            <p class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                                {{ number_format($evenement->prix_min, 2, ',', ' ') }} DH
                                            </p>
                                        </div>
                                        <a href="{{ route('client.evenements.show', $evenement->id) }}" 
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white rounded-xl transition-all duration-300 group-hover:shadow-md">
                                            Réserver
                                            <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform duration-200"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    
    .animate-slide-up {
        animation: slideUp 0.5s ease-out forwards;
        opacity: 0;
    }
    
    .animation-delay-200 {
        animation-delay: 0.2s;
    }
    
    .animation-delay-400 {
        animation-delay: 0.4s;
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    .animate-bounce {
        animation: bounce 1s ease-in-out infinite;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Custom scrollbar for table */
    .overflow-x-auto::-webkit-scrollbar {
        height: 8px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate table rows sequentially
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateX(-20px)';
            setTimeout(() => {
                row.style.transition = 'all 0.3s ease-out';
                row.style.opacity = '1';
                row.style.transform = 'translateX(0)';
            }, index * 80);
        });
        
        // Add hover sound effect (optional, uncomment if needed)
        // const buttons = document.querySelectorAll('button, a');
        // buttons.forEach(btn => {
        //     btn.addEventListener('mouseenter', () => {
        //         // Play subtle sound effect if desired
        //     });
        // });
        
        // Show confirmation toast for cart actions
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (this.querySelector('button[type="submit"]') && 
                    this.querySelector('button[type="submit"]').innerHTML.includes('trash')) {
                    // Optional: Add custom confirmation dialog
                }
            });
        });
    });
</script>
@endpush
@endsection