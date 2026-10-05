@extends('organisateur.layouts.master')

@section('title', 'Créer un événement')
@section('page-title', 'Créer un événement')
@section('page-description', 'Ajoutez un nouvel événement et commencez à vendre vos billets')

@section('content')
<div class="space-y-6" x-data="eventForm()" x-init="init()">
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="group bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-5 text-white transform hover:-translate-y-0.5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Événements créés</p>
                    <p class="text-3xl font-bold mt-2">{{ \App\Models\Evenement::where('user_id', Auth::id())->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
        </div>
        
        <div class="group bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-5 text-white transform hover:-translate-y-0.5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-emerald-100 text-sm font-medium">Billets vendus</p>
                    <p class="text-3xl font-bold mt-2">{{ \App\Models\Billet::whereHas('commande', fn($q) => $q->where('statut', 'Confirmée'))->whereHas('evenement', fn($q) => $q->where('user_id', Auth::id()))->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                </div>
            </div>
        </div>
        
        <div class="group bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-5 text-white transform hover:-translate-y-0.5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Capacité totale</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format(\App\Models\Evenement::where('user_id', Auth::id())->sum('capacite')) }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('organisateur.evenements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <!-- Informations générales -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Informations générales</h3>
                </div>
            </div>
            <div class="p-6 space-y-5">
                <!-- Titre -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Titre <span class="text-red-500">*</span></label>
                    <input type="text" name="titre" value="{{ old('titre') }}" required
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                           placeholder="Ex: Concert de Jazz...">
                    @error('titre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Description -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all resize-none"
                              placeholder="Décrivez votre événement...">{{ old('description') }}</textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Type d'événement</label>
                        <select name="type" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 bg-white">
                            <option value="">Sélectionner</option>
                            @foreach(['Concert'=>'🎵 Concert','Sport'=>'⚽ Sport','Théâtre'=>'🎭 Théâtre','Conférence'=>'🎤 Conférence','Festival'=>'🎪 Festival','Exposition'=>'🖼️ Exposition'] as $val=>$lbl)
                                <option value="{{ $val }}" {{ old('type')==$val?'selected':'' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Date <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="date" id="eventDate" value="{{ old('date') }}" min="{{ now()->format('Y-m-d\TH:i') }}" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Lieu & Capacité -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Lieu et capacité</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Lieu existant -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Lieu existant</label>
                        <select name="lieu_id" id="lieu_id" @change="updateCapacity()" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 bg-white">
                            <option value="">Sélectionner un lieu</option>
                            @foreach($lieux as $lieu)
                                <option value="{{ $lieu->id }}" data-capacite="{{ $lieu->capacite }}" {{ old('lieu_id')==$lieu->id?'selected':'' }}>
                                    {{ $lieu->nom }} - {{ $lieu->ville }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nouveau lieu toggle -->
                    <div>
                        <button type="button" onclick="toggleNewLieu()" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            + Ajouter un nouveau lieu
                        </button>
                        <div id="newLieuForm" class="{{ old('nouveau_lieu_nom')?'':'hidden' }} mt-4 p-5 bg-gray-50 rounded-xl space-y-3 border border-gray-200">
                            <div class="grid grid-cols-2 gap-3">
                                <input type="text" name="nouveau_lieu_nom" value="{{ old('nouveau_lieu_nom') }}" placeholder="Nom du lieu *" class="px-4 py-2 border rounded-lg">
                                <input type="text" name="nouveau_lieu_ville" value="{{ old('nouveau_lieu_ville') }}" placeholder="Ville *" class="px-4 py-2 border rounded-lg">
                            </div>
                            <input type="text" name="nouveau_lieu_adresse" value="{{ old('nouveau_lieu_adresse') }}" placeholder="Adresse" class="w-full px-4 py-2 border rounded-lg">
                            <div class="grid grid-cols-2 gap-3">
                                <input type="text" name="nouveau_lieu_code_postal" value="{{ old('nouveau_lieu_code_postal') }}" placeholder="Code postal" class="px-4 py-2 border rounded-lg">
                                <input type="number" name="nouveau_lieu_capacite" value="{{ old('nouveau_lieu_capacite') }}" min="1" placeholder="Capacité" class="px-4 py-2 border rounded-lg">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Capacité -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Capacité totale <span class="text-red-500">*</span></label>
                        <input type="number" name="capacite" id="capacite" value="{{ old('capacite') }}" required min="1" @input="updateCapacity()"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200" placeholder="Nombre de places">
                        @error('capacite') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Image -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="text-lg font-bold text-gray-900">Image de l'événement</h3>
            </div>
            <div class="p-6">
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-emerald-400 hover:bg-emerald-50/30 transition cursor-pointer" onclick="document.getElementById('affiche').click()">
                    <svg class="w-10 h-10 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-sm font-medium text-gray-700">Cliquez pour télécharger</p>
                    <p class="text-xs text-gray-400">JPG, PNG, GIF, WEBP • Max 2MB</p>
                    <input type="file" name="affiche" id="affiche" accept="image/*" class="hidden">
                    <div id="imagePreview" class="mt-4 hidden"><img src="" class="h-32 mx-auto rounded-xl shadow-md object-cover"></div>
                </div>
                @error('affiche') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>
        </div>
        
        <!-- Types de billets -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="text-lg font-bold text-gray-900">Types de billets</h3>
                <p class="text-xs text-gray-500">Maximum 3 types différents</p>
            </div>
            <div class="p-6">
                
                <template x-for="(ticket, index) in ticketTypes" :key="ticket.key">
                    <div x-show="ticket._destroy !== '1'" 
                         class="flex flex-wrap gap-3 mb-4 items-start p-5 bg-gray-50 rounded-xl border transition-all"
                         :class="ticket.type === '' ? 'border-red-300 bg-red-50' : 'border-gray-200'">
                        
                        <input type="hidden" :name="`types[${index}][id]`" :value="ticket.id">
                        <input type="hidden" :name="`types[${index}][key]`" :value="ticket.key">
                        <input type="hidden" :name="`types[${index}][_destroy]`" :value="ticket._destroy">
                        
                        <div class="flex-1 min-w-[160px]">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Type *</label>
                            <select :name="`types[${index}][type]`" 
                                    x-model="ticket.type"
                                    @change="onTypeChange(index)"
                                    required
                                    class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white"
                                    :class="ticket.type===''?'border-red-400':'border-gray-300'">
                                <option value="">Sélectionner</option>
                                @foreach($availableTypes as $typeKey => $typeLabel)
                                    <option value="{{ $typeKey }}">{{ $typeLabel }}</option>
                                @endforeach
                            </select>
                            <p x-show="ticket.type==='' && ticket._destroy!=='1'" class="text-red-500 text-xs mt-1">Requis</p>
                        </div>
                        
                        <div class="w-44">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Prix (DH)</label>
                            <div class="relative">
                                <input type="number" :name="`types[${index}][prix]`" x-model="ticket.prix" step="0.01" min="0" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" placeholder="0.00">
                                <span class="absolute right-3 top-2.5 text-gray-400 text-sm">DH</span>
                            </div>
                        </div>
                        
                        <div class="w-44">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Quantité</label>
                            <input type="number" :name="`types[${index}][quantite]`" x-model="ticket.quantite" @input="recalcStandard()" min="0" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" placeholder="Nombre">
                        </div>
                        
                        <div class="flex items-center pt-6">
                            <button type="button" @click="removeType(index)" class="text-red-400 hover:text-red-600 p-2" title="Supprimer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </template>
                
                <button type="button" @click="addType()" class="mt-2 inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Ajouter un type
                </button>
                <p class="text-xs text-gray-400 mt-3">Au moins un type requis</p>
            </div>
        </div>
        
        <!-- Publication -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="text-lg font-bold text-gray-900">Publication</h3>
            </div>
            <div class="p-6">
                <div class="flex gap-6">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="radio" name="statut" value="Brouillon" checked class="mt-0.5">
                        <div>
                            <span class="text-sm font-semibold">Brouillon</span>
                            <p class="text-xs text-gray-500">Non visible</p>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="radio" name="statut" value="Publié" class="mt-0.5">
                        <div>
                            <span class="text-sm font-semibold">Publier</span>
                            <p class="text-xs text-gray-500">Visible immédiatement</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex justify-end gap-3 bg-white p-5 rounded-2xl shadow-lg border border-gray-200 sticky bottom-4">
            <a href="{{ route('organisateur.evenements.index') }}" class="px-6 py-2.5 border-2 border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 font-semibold">Annuler</a>
            <button type="submit" class="px-8 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-md">
                Créer l'événement
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function eventForm() {
    return {
        capacite: Number('{{ old('capacite', 0) }}') || 0,
        ticketTypes: [],
        nextKey: 1,
        
        init() {
            const oldTypes = @json(old('types', []));
            if (oldTypes?.length > 0) {
                this.ticketTypes = oldTypes.map((t, i) => ({
                    ...t,
                    key: t.key || `t_${Date.now()}_${i}_${Math.random().toString(36).substr(2,9)}`
                }));
            } else {
                this.addType();
            }
            this.nextKey = this.ticketTypes.length + 1;
            this.recalcStandard();
        },
        
        addType() {
            if (this.getActiveCount() >= 3) {
                alert('Maximum 3 types autorisés');
                return;
            }
            const available = @json(array_keys($availableTypes ?? []));
            const used = this.ticketTypes.filter(t => t._destroy!=='1').map(t => t.type);
            const defaultType = available.find(t => !used.includes(t)) || '';
            
            this.ticketTypes.push({
                key: `t_${Date.now()}_${this.nextKey++}_${Math.random().toString(36).substr(2,9)}`,
                id: null, type: defaultType, prix: 0, quantite: 0, _destroy: '0'
            });
            this.recalcStandard();
        },
        
        removeType(index) {
            if (this.getActiveCount() <= 1) {
                alert('Au moins un type requis');
                return;
            }
            this.ticketTypes[index]._destroy = '1';
            this.recalcStandard();
        },
        
        onTypeChange(index) {
            if (this.ticketTypes[index].type === 'Standard') {
                this.recalcStandard();
            }
        },
        
        updateCapacity() {
            const cap = Number(document.querySelector('input[name=capacite]')?.value) || 0;
            this.capacite = cap;
            this.recalcStandard();
        },
        
        recalcStandard() {
            const capacity = Number(this.capacite) || 0;
            const others = this.ticketTypes
                .filter(t => t.type !== 'Standard' && t._destroy !== '1')
                .reduce((sum, t) => sum + (Number(t.quantite)||0), 0);
            
            const std = this.ticketTypes.find(t => t.type==='Standard' && t._destroy!=='1');
            if (std) std.quantite = Math.max(0, capacity - others);
        },
        
        getActiveCount() {
            return this.ticketTypes.filter(t => t._destroy !== '1').length;
        }
    };
}

// Venue capacity auto-fill
document.querySelector('select[name="lieu_id"]')?.addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    const cap = opt.getAttribute('data-capacite');
    if (cap) {
        const input = document.getElementById('capacite');
        input.value = cap;
        input.dispatchEvent(new Event('input'));
    }
});

// Image preview
document.getElementById('affiche')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file?.size > 2*1024*1024) { alert('Max 2MB'); this.value=''; return; }
    if (file) {
        const reader = new FileReader();
        reader.onload = ev => {
            const prev = document.getElementById('imagePreview');
            prev.querySelector('img').src = ev.target.result;
            prev.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});

// Toggle nouveau lieu
function toggleNewLieu() {
    const form = document.getElementById('newLieuForm');
    const sel = document.getElementById('lieu_id');
    form.classList.toggle('hidden');
    sel.disabled = !form.classList.contains('hidden');
    if (form.classList.contains('hidden')) sel.value = '';
}

// Form validation
document.querySelector('form')?.addEventListener('submit', function(e) {
    const date = document.getElementById('eventDate');
    if (date?.value && new Date(date.value) <= new Date()) {
        e.preventDefault(); alert('Date future requise'); date.focus(); return false;
    }
    
    const lieu = document.getElementById('lieu_id')?.value;
    const nouveau = document.getElementById('nouveau_lieu_nom')?.value;
    if (!lieu && !nouveau) { e.preventDefault(); alert('Sélectionnez ou créez un lieu'); return false; }
    
    const types = document.querySelectorAll('[name*="[type]"]');
    if (![...types].some(i => i.value?.trim())) { e.preventDefault(); alert('Au moins un type requis'); return false; }
    
    return true;
});
</script>
@endpush
@endsection