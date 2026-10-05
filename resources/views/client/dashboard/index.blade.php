@extends('client.layouts.master')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')
@section('page-description', 'Bienvenue sur votre espace client')

@section('content')
<style>
    .stat-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.15);
    }
    
    .gradient-border {
        position: relative;
        background: white;
        border-radius: 1rem;
        overflow: hidden;
    }
    
    .gradient-border::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
    }
    
    .welcome-banner {
        background: linear-gradient(135deg, #1e1b2e 0%, #2d1b4e 50%, #1a1a2e 100%);
        position: relative;
        overflow: hidden;
    }
    
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 80%;
        height: 200%;
        background: radial-gradient(circle, rgba(139, 92, 246, 0.15) 0%, transparent 70%);
        transform: rotate(25deg);
        pointer-events: none;
    }
    
    .welcome-banner::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 60%;
        height: 150%;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
        pointer-events: none;
    }
    
    .event-card {
        transition: all 0.3s ease;
    }
    .event-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.15);
    }
    
    .quick-action {
        transition: all 0.2s ease;
    }
    .quick-action:hover {
        transform: translateY(-2px);
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }
    
    @keyframes pulse-ring {
        0% { transform: scale(0.8); opacity: 0.5; }
        100% { transform: scale(1.2); opacity: 0; }
    }
    
    .pulse-ring {
        animation: pulse-ring 2s infinite;
    }
</style>

<!-- Welcome Banner Professionnel -->
<div class="welcome-banner rounded-2xl shadow-2xl p-6 mb-8 text-white relative overflow-hidden">
    <div class="relative z-10">
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/30">
                        @if(Auth::user()->avatar)
                            <img src="{{ Storage::url(Auth::user()->avatar) }}" class="w-14 h-14 rounded-xl object-cover">
                        @else
                            <i class="fas fa-user-circle text-4xl text-white/80"></i>
                        @endif
                    </div>
                    <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
                </div>
                <div>
                    <h1 class="text-3xl font-bold mb-1">
                        Bonjour, {{ Auth::user()->prenom ?? Auth::user()->name ?? 'Client' }}!
                    </h1>
                    <p class="text-purple-200 text-sm flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-xs"></i>
                        Membre depuis {{ Auth::user()->created_at->format('F Y') }}
                    </p>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2 border border-white/20">
                    <div class="flex items-center gap-2 text-sm">
                        <i class="fas fa-shield-alt text-yellow-300"></i>
                        <span>Compte vérifié</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3">
                <p class="text-purple-200 text-xs">Taux de satisfaction</p>
                <p class="text-2xl font-bold">98%</p>
                <div class="w-full bg-white/20 rounded-full h-1.5 mt-2">
                    <div class="bg-green-400 h-1.5 rounded-full" style="width: 98%"></div>
                </div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3">
                <p class="text-purple-200 text-xs">Billets utilisés</p>
                <p class="text-2xl font-bold">{{ $billetsUtilises ?? 0 }}</p>
                <div class="w-full bg-white/20 rounded-full h-1.5 mt-2">
                    <div class="bg-blue-400 h-1.5 rounded-full" style="width: {{ ($billetsUtilises ?? 0) / max(($totalBillets ?? 1), 1) * 100 }}%"></div>
                </div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3">
                <p class="text-purple-200 text-xs">Prochain événement</p>
                <p class="text-lg font-bold truncate">{{ $prochainEvenement ?? 'Aucun' }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards Modernes -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <!-- Commandes -->
    <div class="stat-card bg-white rounded-2xl shadow-lg p-5 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Commandes</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalCommandes ?? 0 }}</p>
                <div class="flex items-center gap-1 mt-2">
                    <i class="fas fa-check-circle text-green-500 text-xs"></i>
                    <span class="text-xs text-gray-500">{{ $commandesConfirmees ?? 0 }} confirmées</span>
                </div>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-shopping-cart text-white text-2xl"></i>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-gray-100">
            <a href="{{ route('client.commandes.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium">
                Voir détails <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
    
    <!-- Billets -->
    <div class="stat-card bg-white rounded-2xl shadow-lg p-5 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Billets</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalBillets ?? 0 }}</p>
                <div class="flex items-center gap-1 mt-2">
                    <i class="fas fa-ticket-alt text-blue-500 text-xs"></i>
                    <span class="text-xs text-gray-500">{{ $billetsValides ?? 0 }} valides</span>
                </div>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-qrcode text-white text-2xl"></i>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-gray-100">
            <a href="{{ route('client.billets.index') }}" class="text-xs text-green-600 hover:text-green-700 font-medium">
                Voir mes billets <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
    
    <!-- Dépenses -->
    <div class="stat-card bg-white rounded-2xl shadow-lg p-5 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total dépensé</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($totalDepense ?? 0, 0, ',', ' ') }} DH</p>
                <div class="flex items-center gap-1 mt-2">
                    <i class="fas fa-chart-line text-purple-500 text-xs"></i>
                    <span class="text-xs text-gray-500">Ce mois-ci</span>
                </div>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-money-bill-wave text-white text-2xl"></i>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-gray-100">
            <a href="{{ route('client.commandes.index') }}" class="text-xs text-purple-600 hover:text-purple-700 font-medium">
                Historique <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
    
    <!-- Fidélité -->
    <div class="stat-card bg-white rounded-2xl shadow-lg p-5 border-l-4 border-orange-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Points fidélité</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $pointsFidelite ?? 0 }}</p>
                <div class="flex items-center gap-1 mt-2">
                    <i class="fas fa-star text-yellow-500 text-xs"></i>
                    <span class="text-xs text-gray-500">Niveau {{ $niveauFidelite ?? 'Bronze' }}</span>
                </div>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-gem text-white text-2xl"></i>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-gray-100">
            <a href="#" class="text-xs text-orange-600 hover:text-orange-700 font-medium">
                Avantages <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
</div>

<!-- Événements à venir et Dernières commandes - Layout 2 colonnes -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Événements à venir -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-gray-800 text-lg">Événements à venir</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Ne manquez pas les prochains événements</p>
                </div>
                <a href="{{ route('client.evenements.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1">
                    Voir tous <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
        <div class="p-5">
            @if(isset($evenementsRecents) && $evenementsRecents->count() > 0)
                <div class="space-y-4">
                    @foreach($evenementsRecents->take(3) as $evenement)
                        <div class="event-card bg-gray-50 rounded-xl p-3 hover:bg-white transition-all duration-300">
                            <div class="flex gap-3">
                                <div class="flex-shrink-0">
                                    @php
                                        $evenementImage = $evenement->affiche ?? $evenement->image ?? null;
                                    @endphp
                                    @if($evenementImage)
                                        <img src="{{ preg_match('/^https?:\/\//', $evenementImage) ? $evenementImage : Storage::url($evenementImage) }}" 
                                             alt="{{ $evenement->titre }}" 
                                             class="w-20 h-20 object-cover rounded-xl">
                                    @else
                                        <div class="w-20 h-20 bg-gradient-to-br from-blue-400 to-purple-500 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-calendar-alt text-white text-3xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-800 text-sm mb-1">{{ $evenement->titre }}</h3>
                                    <div class="space-y-1 text-xs text-gray-500">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-calendar-alt w-3 text-blue-500"></i>
                                            <span>{{ \Carbon\Carbon::parse($evenement->date)->format('d/m/Y à H:i') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-map-marker-alt w-3 text-red-500"></i>
                                            <span class="truncate">{{ $evenement->lieu->nom ?? 'Lieu à définir' }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-tag w-3 text-green-500"></i>
                                            <span class="font-semibold text-blue-600">{{ number_format($evenement->prix_min ?? 0, 0, ',', ' ') }} DH</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('client.evenements.show', $evenement->id) }}" 
                                       class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                        <i class="fas fa-chevron-right text-sm"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if(($evenementsRecents->count() - 3) > 0)
                    <div class="mt-4 text-center">
                        <p class="text-xs text-gray-400">+{{ $evenementsRecents->count() - 3 }} autres événements</p>
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-calendar-times text-gray-400 text-3xl"></i>
                    </div>
                    <p class="text-gray-500 font-medium">Aucun événement à venir</p>
                    <a href="{{ route('client.evenements.index') }}" class="inline-block mt-3 text-blue-600 text-sm hover:underline">
                        Explorer les événements
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Dernières commandes -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-gray-800 text-lg">Dernières commandes</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Historique de vos achats récents</p>
                </div>
                <a href="{{ route('client.commandes.index') }}" class="text-sm text-purple-600 hover:text-purple-700 font-medium flex items-center gap-1">
                    Voir toutes <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
        <div class="p-5">
            @if(isset($commandesRecentes) && $commandesRecentes->count() > 0)
                <div class="space-y-3">
                    @foreach($commandesRecentes->take(4) as $commande)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-white transition-all duration-200 border border-transparent hover:border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center 
                                    @if($commande->statut == 'Confirmée') bg-green-100
                                    @elseif($commande->statut == 'En attente') bg-yellow-100
                                    @else bg-red-100 @endif">
                                    <i class="fas fa-receipt 
                                        @if($commande->statut == 'Confirmée') text-green-600
                                        @elseif($commande->statut == 'En attente') text-yellow-600
                                        @else text-red-600 @endif"></i>
                                </div>
                                <div>
                                    <p class="font-mono text-sm font-semibold text-gray-800">#{{ $commande->id }}</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($commande->created_at)->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800">{{ number_format($commande->montant_total, 0, ',', ' ') }} DH</p>
                                @if($commande->statut == 'Confirmée')
                                    <span class="inline-block px-2 py-0.5 text-xs bg-green-100 text-green-700 rounded-full">Confirmée</span>
                                @elseif($commande->statut == 'En attente')
                                    <span class="inline-block px-2 py-0.5 text-xs bg-yellow-100 text-yellow-700 rounded-full">En attente</span>
                                @else
                                    <span class="inline-block px-2 py-0.5 text-xs bg-red-100 text-red-700 rounded-full">{{ $commande->statut }}</span>
                                @endif
                            </div>
                            <a href="{{ route('client.commandes.show', $commande->id) }}" class="text-blue-600 hover:text-blue-800 ml-2">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-shopping-bag text-gray-400 text-3xl"></i>
                    </div>
                    <p class="text-gray-500 font-medium">Aucune commande</p>
                    <a href="{{ route('client.evenements.index') }}" class="inline-block mt-3 text-blue-600 text-sm hover:underline">
                        Commencer mes achats
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick Actions Modernes -->
<div class="mb-6">
    <h3 class="text-sm font-semibold text-gray-500 mb-3 flex items-center gap-2">
        <i class="fas fa-bolt text-yellow-500"></i>
        Accès rapide
    </h3>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
        <a href="{{ route('client.evenements.index') }}" class="quick-action bg-white rounded-xl shadow-sm p-4 text-center hover:shadow-md transition-all duration-200 group">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mx-auto mb-2 shadow-md group-hover:scale-110 transition-transform">
                <i class="fas fa-calendar-alt text-white text-xl"></i>
            </div>
            <p class="text-sm font-medium text-gray-700">Événements</p>
            <p class="text-xs text-gray-400 mt-0.5">Explorer</p>
        </a>
        
        <a href="{{ route('client.billets.index') }}" class="quick-action bg-white rounded-xl shadow-sm p-4 text-center hover:shadow-md transition-all duration-200 group">
            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mx-auto mb-2 shadow-md group-hover:scale-110 transition-transform">
                <i class="fas fa-qrcode text-white text-xl"></i>
            </div>
            <p class="text-sm font-medium text-gray-700">Mes billets</p>
            <p class="text-xs text-gray-400 mt-0.5">QR codes</p>
        </a>
        
        <a href="{{ route('client.commandes.index') }}" class="quick-action bg-white rounded-xl shadow-sm p-4 text-center hover:shadow-md transition-all duration-200 group">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mx-auto mb-2 shadow-md group-hover:scale-110 transition-transform">
                <i class="fas fa-shopping-cart text-white text-xl"></i>
            </div>
            <p class="text-sm font-medium text-gray-700">Commandes</p>
            <p class="text-xs text-gray-400 mt-0.5">Historique</p>
        </a>
        

        
        <a href="{{ route('client.profil.edit') }}" class="quick-action bg-white rounded-xl shadow-sm p-4 text-center hover:shadow-md transition-all duration-200 group">
            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-amber-600 rounded-xl flex items-center justify-center mx-auto mb-2 shadow-md group-hover:scale-110 transition-transform">
                <i class="fas fa-user-cog text-white text-xl"></i>
            </div>
            <p class="text-sm font-medium text-gray-700">Mon profil</p>
            <p class="text-xs text-gray-400 mt-0.5">Paramètres</p>
        </a>
    </div>
</div>

<!-- Recommandations personnalisées -->
@if(isset($recommandations) && $recommandations->count() > 0)
<div class="bg-white rounded-2xl shadow-lg overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                    <i class="fas fa-magic text-purple-500"></i>
                    Recommandations personnalisées
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Basé sur vos préférences</p>
            </div>
        </div>
    </div>
    <div class="p-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($recommandations->take(4) as $recommandation)
                <div class="group cursor-pointer">
                    <div class="relative overflow-hidden rounded-xl">
                        @php
                            $recImage = $recommandation->affiche ?? $recommandation->image ?? null;
                        @endphp
                        @if($recImage)
                            <img src="{{ Storage::url($recImage) }}" alt="{{ $recommandation->titre }}" class="w-full h-32 object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-32 bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-white text-4xl"></i>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    <div class="mt-2">
                        <h4 class="font-semibold text-gray-800 text-sm truncate">{{ $recommandation->titre }}</h4>
                        <p class="text-xs text-gray-500">{{ number_format($recommandation->prix_min ?? 0, 0, ',', ' ') }} DH</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection