@extends('client.layouts.master')

@section('title', 'Événements')
@section('page-title', 'Événements')
@section('page-description', 'Découvrez, réservez et vivez des expériences uniques près de chez vous')
@section('main-class', 'flex-1 overflow-y-auto p-0 bg-transparent')

@section('content')
<div class="min-h-[calc(100vh-5rem)] w-full bg-gradient-to-br from-slate-50 via-gray-50 to-blue-50">
    <div class="w-full px-4 py-8">
        <!-- Header avec design moderne et animé -->
        <div class="mb-12 text-center md:text-left">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="relative">
                    <div class="absolute -top-10 -left-10 w-32 h-32 bg-blue-400 rounded-full opacity-20 blur-3xl animate-pulse"></div>
                    <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-purple-400 rounded-full opacity-20 blur-3xl animate-pulse delay-1000"></div>
                    <h1 class="text-5xl md:text-6xl font-bold bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent mb-4 animate-gradient">
                        Événements
                    </h1>
                    <p class="text-gray-600 text-lg max-w-2xl">Découvrez, réservez et vivez des expériences uniques près de chez vous</p>
                </div>
                <div class="flex flex-wrap items-center justify-center md:justify-end gap-3">
                    <div class="glass-card rounded-full px-5 py-2.5 shadow-lg">
                        <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                        <span class="text-gray-700 font-semibold">
                            @php
                                $disponibles = isset($evenements_disponible) ? $evenements_disponible : (isset($evenements) ? $evenements->filter(fn($e) => ($e->capacite - ($e->billets_vendus ?? 0)) > 0)->count() : 0);
                            @endphp
                            {{ $disponibles }} disponibles
                        </span>
                    </div>
                    <div class="glass-card rounded-full px-5 py-2.5 shadow-lg">
                        <i class="fas fa-chart-line text-green-500 mr-2"></i>
                        <span class="text-gray-700 font-semibold">
                            @php
                                $total = isset($evenements) ? ($evenements->total() ?? $evenements->count()) : 0;
                            @endphp
                            {{ $total }} événements
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filters avec design moderne glassmorphisme -->
        <div class="glass-card rounded-2xl shadow-xl p-6 mb-10 backdrop-blur-md bg-white/70 border border-white/50">
            <form method="GET" action="{{ route('client.evenements.index') }}" class="space-y-6" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="relative group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 transform transition-all duration-200 group-focus-within:text-blue-600">
                            <i class="fas fa-search text-blue-500 mr-1"></i> Recherche
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Titre, lieu, description..." 
                               class="w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition-all duration-300">
                        <i class="fas fa-search absolute left-4 top-[42px] text-gray-400 group-focus-within:text-blue-500 transition-colors duration-300"></i>
                    </div>
                    
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 transition-all duration-200 group-focus-within:text-blue-600">
                            <i class="fas fa-calendar-day text-blue-500 mr-1"></i> Date
                        </label>
                        <input type="date" name="date" value="{{ request('date') }}" 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition-all duration-300"
                               min="{{ date('Y-m-d') }}">
                    </div>
                    
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 transition-all duration-200 group-focus-within:text-blue-600">
                            <i class="fas fa-map-marker-alt text-blue-500 mr-1"></i> Lieu
                        </label>
                        <select name="lieu_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition-all duration-300 cursor-pointer">
                            <option value="">Tous les lieux</option>
                            @foreach($lieux ?? [] as $lieu)
                                <option value="{{ $lieu->id }}" {{ request('lieu_id') == $lieu->id ? 'selected' : '' }}>
                                    {{ $lieu->nom }} - {{ $lieu->ville }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 transition-all duration-200 group-focus-within:text-blue-600">
                            <i class="fas fa-tag text-blue-500 mr-1"></i> Type
                        </label>
                        <select name="type" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition-all duration-300 cursor-pointer">
                            <option value="">Tous les types</option>
                            <option value="Concert" {{ request('type') == 'Concert' ? 'selected' : '' }}>🎵 Concert</option>
                            <option value="Sport" {{ request('type') == 'Sport' ? 'selected' : '' }}>⚽ Sport</option>
                            <option value="Theatre" {{ request('type') == 'Theatre' ? 'selected' : '' }}>🎭 Théâtre</option>
                            <option value="Conference" {{ request('type') == 'Conference' ? 'selected' : '' }}>💡 Conférence</option>
                            <option value="Festival" {{ request('type') == 'Festival' ? 'selected' : '' }}>🎪 Festival</option>
                            <option value="Exposition" {{ request('type') == 'Exposition' ? 'selected' : '' }}>🖼️ Exposition</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
                    <div class="flex gap-3">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-sliders-h mr-2"></i> Appliquer les filtres
                        </button>
                        @if(request()->anyFilled(['search', 'date', 'lieu_id', 'type']))
                            <a href="{{ route('client.evenements.index') }}" class="btn-secondary" id="resetFilters">
                                <i class="fas fa-undo-alt mr-2"></i> Réinitialiser
                            </a>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <label class="text-sm font-medium text-gray-600">Trier par :</label>
                        <select name="sort" id="sortSelect" class="px-4 py-2 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 cursor-pointer bg-white">
                            <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>📅 Date (plus proche)</option>
                            <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>📅 Date (plus lointaine)</option>
                            <option value="prix_asc" {{ request('sort') == 'prix_asc' ? 'selected' : '' }}>💰 Prix (croissant)</option>
                            <option value="prix_desc" {{ request('sort') == 'prix_desc' ? 'selected' : '' }}>💰 Prix (décroissant)</option>
                            <option value="places" {{ request('sort') == 'places' ? 'selected' : '' }}>🎫 Places disponibles</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Events Grid avec design moderne néomorphisme -->
@if(isset($evenements) && $evenements->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($evenements as $evenement)
            @php
                $places_restantes = $evenement->capacite - ($evenement->billets_vendus ?? 0);
                $est_disponible = $places_restantes > 0;
                $est_bientot_complet = $est_disponible && $places_restantes <= 10;
                $date_evenement = \Carbon\Carbon::parse($evenement->date);
                $est_passe = $date_evenement->isPast();
            @endphp
            
            <div class="group relative">
                <!-- Carte principale avec effet glassmorphisme -->
                <div class="relative bg-white/90 backdrop-blur-sm rounded-2xl overflow-hidden shadow-lg transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 border border-white/50">
                    
                    <!-- Section image avec overlay moderne -->
                    <div class="relative h-56 overflow-hidden cursor-pointer" onclick="window.location='{{ route('client.evenements.show', $evenement->id) }}'">
                        @if($evenement->affiche)
                            <img src="{{ asset('storage/' . ltrim($evenement->affiche, '/')) }}" 
                                 alt="Affiche {{ $evenement->titre }}" 
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                 loading="lazy">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-white text-7xl opacity-30"></i>
                            </div>
                        @endif
                        
                        <!-- Gradient overlay moderne -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <!-- Badge type moderne avec icône -->
                        <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-full text-sm font-semibold shadow-lg border border-white/50">
                            @switch($evenement->type)
                                @case('Concert') 🎵 Concert @break
                                @case('Sport') ⚽ Sport @break
                                @case('Theatre') 🎭 Théâtre @break
                                @case('Conference') 💡 Conférence @break
                                @case('Festival') 🎪 Festival @break
                                @case('Exposition') 🖼️ Exposition @break
                                @default 🎉 Événement
                            @endswitch
                        </div>
                        
                        <!-- Badge disponibilité animé -->
                        @if($est_passe)
                            <div class="absolute top-4 right-4 bg-gray-600/95 backdrop-blur-md px-3 py-1.5 rounded-full text-xs font-semibold shadow-lg text-white">
                                <i class="fas fa-clock mr-1"></i> Terminé
                            </div>
                        @elseif($est_disponible)
                            <div class="absolute top-4 right-4 {{ $est_bientot_complet ? 'bg-orange-500/95' : 'bg-green-500/95' }} backdrop-blur-md px-3 py-1.5 rounded-full text-xs font-semibold shadow-lg text-white">
                                <i class="fas {{ $est_bientot_complet ? 'fa-exclamation-triangle' : 'fa-check-circle' }} mr-1"></i>
                                {{ $est_bientot_complet ? 'Dernières places' : 'Disponible' }}
                            </div>
                        @else
                            <div class="absolute top-4 right-4 bg-red-500/95 backdrop-blur-md px-3 py-1.5 rounded-full text-xs font-semibold shadow-lg text-white">
                                <i class="fas fa-times-circle mr-1"></i> Complet
                            </div>
                        @endif
                        

                        <!-- Overlay moderne au survol -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-center justify-center">
                            <div class="transform scale-90 group-hover:scale-100 transition-all duration-300">
                                <div class="bg-white/95 backdrop-blur-xl rounded-2xl px-6 py-3 shadow-2xl">
                                    <i class="fas fa-eye text-blue-600 mr-2"></i>
                                    <span class="text-gray-800 font-semibold">Explorer l'événement</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contenu de la carte -->
                    <div class="p-5">
                        <!-- Date et heure avec style moderne -->
                        <div class="flex items-center justify-between text-sm mb-4 pb-3 border-b border-gray-100">
                            <div class="flex items-center text-gray-600">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-2">
                                    <i class="far fa-calendar-alt text-blue-600 text-xs"></i>
                                </div>
                                <span class="font-medium">{{ $date_evenement->translatedFormat('d M Y') }}</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center mr-2">
                                    <i class="far fa-clock text-indigo-600 text-xs"></i>
                                </div>
                                <span class="font-medium">{{ $date_evenement->format('H:i') }}</span>
                            </div>
                        </div>
                        
                        <!-- Titre avec effet gradient au hover -->
                        <h3 class="text-xl font-bold mb-3 transition-colors duration-300 line-clamp-2 min-h-[3.5rem] bg-gradient-to-r from-gray-800 to-gray-800 group-hover:from-blue-600 group-hover:to-purple-600 bg-clip-text group-hover:text-transparent">
                            {{ $evenement->titre }}
                        </h3>
                        
                        <!-- Informations supplémentaires -->
                        <div class="space-y-3 mb-4">
                            <!-- Lieu -->
                            <div class="flex items-start text-gray-600">
                                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                    <i class="fas fa-map-marker-alt text-red-500 text-xs"></i>
                                </div>
                                <span class="text-sm flex-1 line-clamp-1">{{ $evenement->lieu->nom }}, {{ $evenement->lieu->ville }}</span>
                            </div>
                            
                            <!-- Places disponibles (si non passé) -->
                            @if(!$est_passe)
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                                <i class="fas fa-users text-green-600 text-xs"></i>
                                            </div>
                                            <span class="text-sm text-gray-600">Places disponibles</span>
                                        </div>
                                        <span class="font-bold {{ $est_bientot_complet ? 'text-orange-500 animate-pulse' : ($est_disponible ? 'text-green-600' : 'text-red-600') }}">
                                            {{ max(0, $places_restantes) }}/{{ $evenement->capacite }}
                                        </span>
                                    </div>
                                    @if($est_disponible && $places_restantes > 0)
                                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                            <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-full h-2 transition-all duration-700 ease-out" 
                                                 style="width: {{ ($places_restantes / $evenement->capacite) * 100 }}%"></div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            
                            <!-- Prix -->
                            <div class="flex items-center justify-between pt-2">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-ticket-alt text-purple-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <span class="font-bold text-2xl text-blue-600">
                                            {{ number_format($evenement->prix_min, 0, ',', ' ') }}
                                        </span>
                                        <span class="text-gray-500 text-sm"> DH</span>
                                        @if($evenement->prix_max && $evenement->prix_max != $evenement->prix_min)
                                            <span class="text-gray-400 text-xs"> - {{ number_format($evenement->prix_max, 0, ',', ' ') }} DH</span>
                                        @endif
                                    </div>
                                </div>
                                @if(isset($evenement->prix_earlybird) && $evenement->prix_earlybird < $evenement->prix_min && !$est_passe)
                                    <span class="text-xs bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 px-2 py-1 rounded-full font-semibold animate-pulse">
                                        -{{ number_format((1 - $evenement->prix_earlybird/$evenement->prix_min) * 100, 0) }}% Early
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Boutons d'action -->
                        <div class="grid grid-cols-2 gap-3 mt-4">
                            <a href="{{ route('client.evenements.show', $evenement->id) }}" 
                               class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border-2 border-gray-200 text-gray-700 font-semibold transition-all duration-300 hover:border-blue-400 hover:bg-blue-50 hover:text-blue-600 group">
                                <i class="fas fa-info-circle text-sm transition-transform group-hover:scale-110"></i>
                                <span>Détails</span>
                            </a>
                            
                            @if(!$est_passe && $est_disponible)
                                <a href="{{ route('client.evenements.show', $evenement->id) }}?action=reserver" 
                                   class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold transition-all duration-300 hover:from-blue-700 hover:to-blue-800 hover:shadow-lg hover:-translate-y-0.5 group">
                                    <i class="fas fa-ticket-alt text-sm transition-transform group-hover:rotate-12"></i>
                                    <span>Réserver</span>
                                </a>
                            @elseif($est_passe)
                                <button disabled class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-gray-200 text-gray-400 font-semibold cursor-not-allowed">
                                    <i class="fas fa-clock text-sm"></i>
                                    <span>Terminé</span>
                                </button>
                            @else
                                <button disabled class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-gray-200 text-gray-400 font-semibold cursor-not-allowed">
                                    <i class="fas fa-ban text-sm"></i>
                                    <span>Complet</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Pagination élégante -->
    @if(method_exists($evenements, 'links'))
        <div class="mt-12">
            {{ $evenements->appends(request()->query())->links() }}
        </div>
    @endif
@else
    <!-- Message vide amélioré -->
    <div class="text-center py-16">
        <div class="relative inline-block">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full blur-2xl opacity-30 animate-pulse"></div>
            <div class="relative w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl">
                <i class="fas fa-calendar-times text-gray-400 text-5xl"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-800 mb-2">Aucun événement trouvé</h3>
        <p class="text-gray-500 mb-6 max-w-md mx-auto">
            Aucun événement ne correspond à vos critères. Essayez avec d'autres filtres.
        </p>
        <a href="{{ route('client.evenements.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl">
            <i class="fas fa-sync-alt"></i>
            <span>Réinitialiser les filtres</span>
        </a>
    </div>
@endif
           
    </div>
</div>

<style>
    /* Styles de base modernes */
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
    }
    
    /* Animation gradient */
    @keyframes gradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    .animate-gradient {
        background-size: 200% 200%;
        animation: gradient 3s ease infinite;
    }
    
    /* Cartes néomorphisme */
    .card-hover {
        background: white;
        border-radius: 1rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    }
    
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    /* Badges modernes */
    .badge-glass {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
        color: #1f2937;
        border: 1px solid rgba(255, 255, 255, 0.8);
    }
    
    .badge-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
    }
    
    .badge-warning {
        background: linear-gradient(135deg, #f59e0b, #ea580c);
        color: white;
        border: none;
        animation: pulse 2s infinite;
    }
    
    .badge-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        border: none;
    }
    
    .badge-gray {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
        border: none;
    }
    
    /* Boutons modernes */
    .btn-primary {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        font-weight: 600;
        padding: 0.625rem 1.5rem;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
    }
    
    .btn-secondary {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
        font-weight: 600;
        padding: 0.625rem 1.5rem;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
    }
    
    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(107, 114, 128, 0.4);
    }
    
    .btn-outline {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: white;
        color: #3b82f6;
        font-weight: 600;
        padding: 0.625rem 1.5rem;
        border-radius: 0.75rem;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .btn-outline:hover {
        border-color: #3b82f6;
        background: #eff6ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }
    
    .btn-disabled {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: #e5e7eb;
        color: #9ca3af;
        font-weight: 600;
        padding: 0.625rem 1.5rem;
        border-radius: 0.75rem;
        cursor: not-allowed;
    }
    
    /* Pagination moderne */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .pagination .page-item {
        list-style: none;
    }
    
    .pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 2.75rem;
        padding: 0.625rem 0.875rem;
        border-radius: 0.75rem;
        color: #4b5563;
        background: white;
        border: 2px solid #e5e7eb;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 500;
    }
    
    .pagination .page-link:hover {
        border-color: #3b82f6;
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        color: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }
    
    .pagination .active .page-link {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        border-color: #3b82f6;
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .pagination .disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .pagination .disabled .page-link:hover {
        transform: none;
        box-shadow: none;
    }
    
    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.9; transform: scale(1.05); }
    }
    
    .group {
        animation: fadeInUp 0.6s ease-out backwards;
    }
    
    /* Stagger animation */
    .group:nth-child(1) { animation-delay: 0s; }
    .group:nth-child(2) { animation-delay: 0.05s; }
    .group:nth-child(3) { animation-delay: 0.1s; }
    .group:nth-child(4) { animation-delay: 0.15s; }
    .group:nth-child(5) { animation-delay: 0.2s; }
    .group:nth-child(6) { animation-delay: 0.25s; }
    .group:nth-child(7) { animation-delay: 0.3s; }
    .group:nth-child(8) { animation-delay: 0.35s; }
    .group:nth-child(9) { animation-delay: 0.4s; }
    .group:nth-child(10) { animation-delay: 0.45s; }
    .group:nth-child(11) { animation-delay: 0.5s; }
    .group:nth-child(12) { animation-delay: 0.55s; }
    
    /* Line clamp */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Scrollbar personnalisée */
    ::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
    }
    
    /* Responsive */
    @media (max-width: 640px) {
        .pagination .page-link {
            padding: 0.375rem 0.5rem;
            min-width: 2.25rem;
            font-size: 0.875rem;
        }
        
        .group .p-6 {
            padding: 1rem;
        }
    }
    
    /* Focus styles */
    input:focus, select:focus {
        outline: none;
    }
    
    /* Smooth transitions */
    * {
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation d'entrée des cartes
    const cards = document.querySelectorAll('.group');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        setTimeout(() => {
            card.style.opacity = '1';
        }, index * 50);
    });
    
    // Tri automatique
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    }
    
    // Sauvegarde des filtres
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        const savedFilters = sessionStorage.getItem('eventFilters');
        if (savedFilters && !window.location.search) {
            const filters = JSON.parse(savedFilters);
            for (const [key, value] of Object.entries(filters)) {
                const input = filterForm.querySelector(`[name="${key}"]`);
                if (input && value) {
                    input.value = value;
                }
            }
            if (Object.keys(filters).length > 0) {
                filterForm.submit();
            }
        }
        
        filterForm.addEventListener('submit', function() {
            const formData = new FormData(filterForm);
            const filters = {};
            for (const [key, value] of formData.entries()) {
                if (value) filters[key] = value;
            }
            sessionStorage.setItem('eventFilters', JSON.stringify(filters));
        });
    }
    
    // Réinitialisation des filtres
    const resetBtn = document.getElementById('resetFilters');
    if (resetBtn) {
        resetBtn.addEventListener('click', function(e) {
            sessionStorage.removeItem('eventFilters');
        });
    }
    
    // Toast notification
    window.showToast = function(message, type = 'success') {
        const toast = document.createElement('div');
        const colors = {
            success: 'bg-gradient-to-r from-green-500 to-emerald-500',
            error: 'bg-gradient-to-r from-red-500 to-rose-500',
            info: 'bg-gradient-to-r from-blue-500 to-indigo-500'
        };
        toast.className = `fixed bottom-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-xl shadow-2xl z-50 transform transition-all duration-300 translate-y-0 opacity-100`;
        toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : (type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle')} mr-2"></i>${message}`;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.transform = 'translateY(100px)';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    };
    
    // Vérifier les messages flash
    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif
    
    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif
});
</script>
@endsection