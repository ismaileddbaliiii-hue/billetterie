@extends('organisateur.layouts.master')

@section('title', $evenement->titre)
@section('page-title', $evenement->titre)
@section('page-description', 'Détails de l\'événement')

@section('content')
<div class="space-y-6">
    
    <!-- Event Header -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="relative h-48 md:h-64 bg-gradient-to-r from-gray-900 to-gray-800"
             @if($evenement->affiche) style="background-image: url('{{ asset('storage/'.$evenement->affiche) }}'); background-size: cover; background-position: center;" @endif>
            <div class="absolute inset-0 bg-gradient-to-r from-gray-900/90 via-gray-800/80 to-gray-900/90"></div>
            <div class="relative z-10 h-full px-6 py-8 flex flex-col justify-end">
                <div class="flex items-center gap-2 text-sm text-gray-400 mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>{{ \Carbon\Carbon::parse($evenement->date)->translatedFormat('l d F Y') }}</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold text-white">{{ $evenement->titre }}</h1>
                <div class="mt-3">
                    @if($evenement->statut == 'Publié')
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">✅ Publié</span>
                    @elseif($evenement->statut == 'Brouillon')
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">📝 Brouillon</span>
                    @else
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-gray-500/10 text-gray-400 border border-gray-500/20">⏹️ {{ $evenement->statut }}</span>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Stats -->
        <div class="grid grid-cols-3 gap-px bg-gray-100">
            <div class="bg-white p-4 text-center">
                <p class="text-xs text-gray-400 uppercase">Capacité</p>
                <p class="text-xl font-bold text-gray-900">{{ number_format($evenement->capacite) }}</p>
            </div>
            <div class="bg-white p-4 text-center">
                <p class="text-xs text-gray-400 uppercase">Vendus</p>
                <p class="text-xl font-bold text-emerald-600">{{ number_format($evenement->getBilletVendus()) }}</p>
            </div>
            <div class="bg-white p-4 text-center">
                <p class="text-xs text-gray-400 uppercase">Restantes</p>
                <p class="text-xl font-bold text-blue-600">{{ number_format($evenement->placesRestantes()) }}</p>
            </div>
        </div>
        
        <!-- Info -->
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-3">Description</h3>
                <p class="text-gray-600">{{ $evenement->description ?? 'Aucune description.' }}</p>
            </div>
            <div class="space-y-3">
                <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                    <div><p class="text-xs text-gray-400 uppercase">Type</p><p class="text-sm font-semibold">{{ $evenement->type ?? 'Non spécifié' }}</p></div>
                </div>
                <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <div><p class="text-xs text-gray-400 uppercase">Lieu</p><p class="text-sm font-semibold">{{ $evenement->lieu->nom ?? 'Non défini' }}</p><p class="text-xs text-gray-500">{{ $evenement->lieu->adresse ?? '' }}{{ $evenement->lieu->ville ? ', '.$evenement->lieu->ville : '' }}</p></div>
                </div>
                <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <div><p class="text-xs text-gray-400 uppercase">Date</p><p class="text-sm font-semibold">{{ \Carbon\Carbon::parse($evenement->date)->translatedFormat('l d F Y') }}</p><p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($evenement->date)->format('H:i') }} — {{ \Carbon\Carbon::parse($evenement->date_fin)->format('H:i') }}</p></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Ticket Types -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h3 class="text-base font-bold text-gray-900">Types de billets</h3>
        </div>
        <div class="p-6">
            @if($typesBillets->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($typesBillets as $type)
                        @php
                            $vendus = $type->quantite_vendue ?? 0;
                            $total = $type->quantite ?? 0;
                            $pct = $total > 0 ? ($vendus / $total) * 100 : 0;
                        @endphp
                        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-lg transition">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="font-bold text-gray-900 text-lg">{{ $type->nom }}</p>
                                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($type->prix, 2, ',', ' ') }} <span class="text-sm font-normal text-gray-400">DH</span></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-400">Vendus</p>
                                    <p class="text-sm font-semibold">{{ number_format($vendus) }}<span class="text-xs text-gray-400">/{{ $total>0?number_format($total):'∞' }}</span></p>
                                </div>
                            </div>
                            @if($vendus>0 && $total>0)
                                <div class="mt-3">
                                    <div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-emerald-500 h-2 rounded-full" style="width:{{min($pct,100)}}%"></div></div>
                                    <p class="text-xs text-gray-400 mt-1">{{ round($pct) }}% rempli</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-400 py-8">Aucun type de billet configuré</p>
            @endif
        </div>
    </div>
    
    <!-- Actions -->
    <div class="flex justify-between items-center">
        <a href="{{ route('organisateur.evenements.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">← Retour</a>
        <div class="flex gap-3">
            @if(Auth::user()->perm_modification_event && $evenement->statut != 'Terminé')
                <a href="{{ route('organisateur.evenements.edit', $evenement) }}" class="px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-xl transition">✏️ Modifier</a>
            @endif
            <a href="{{ route('organisateur.ventes.event', $evenement) }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition">📊 Statistiques</a>
        </div>
    </div>
</div>
@endsection