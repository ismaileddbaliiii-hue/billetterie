@extends('organisateur.layouts.master')

@section('title', 'Modifier - ' . $evenement->titre)
@section('page-title', 'Modifier l\'événement')
@section('page-description', $evenement->titre)

@section('content')
<div class="space-y-6">
    <form action="{{ route('organisateur.evenements.update', $evenement) }}" method="POST" enctype="multipart/form-data" class="space-y-6" 
          x-data="eventForm()" x-init="init()">
        @csrf
        @method('PUT')
        
        <!-- General Information -->
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
                <!-- Title -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Titre <span class="text-red-500">*</span></label>
                    <input type="text" name="titre" value="{{ old('titre', $evenement->titre) }}" required
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                    @error('titre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Description -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all resize-none">{{ old('description', $evenement->description) }}</textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Type d'événement</label>
                        <select name="type" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 bg-white">
                            <option value="">Sélectionner</option>
                            @foreach(['Concert'=>'🎵 Concert','Sport'=>'⚽ Sport','Théâtre'=>'🎭 Théâtre','Conférence'=>'🎤 Conférence','Festival'=>'🎪 Festival','Exposition'=>'🖼️ Exposition'] as $val=>$lbl)
                                <option value="{{ $val }}" {{ old('type', $evenement->type)==$val?'selected':'' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Date (Read-only) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Date <span class="text-red-500">*</span></label>
                        <input type="datetime-local" value="{{ optional($evenement->date)->format('Y-m-d\TH:i') }}" readonly disabled
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-100 text-gray-500 cursor-not-allowed">
                        <p class="text-xs text-gray-500 mt-1">La date n'est pas modifiable après création</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Venue & Capacity -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="text-lg font-bold text-gray-900">Lieu et capacité</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Venue -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Lieu <span class="text-red-500">*</span></label>
                        <select name="lieu_id" required @change="updateCapacity()" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 bg-white">
                            <option value="">Sélectionner</option>
                            @foreach($lieux as $lieu)
                                <option value="{{ $lieu->id }}" data-capacite="{{ $lieu->capacite }}" {{ old('lieu_id', $evenement->lieu_id)==$lieu->id?'selected':'' }}>
                                    {{ $lieu->nom }} - {{ $lieu->ville }}
                                </option>
                            @endforeach
                        </select>
                        @error('lieu_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Capacity -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Capacité <span class="text-red-500">*</span></label>
                        <input type="number" name="capacite" id="capacite" value="{{ old('capacite', $evenement->capacite) }}" required min="{{ $evenement->billets_vendus ?? 0 }}" @input="updateCapacity()"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        @error('capacite') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                @if(($evenement->billets_vendus ?? 0) > 0)
                <div class="mt-6 pt-5 border-t border-gray-100">
                    <p class="text-xs text-amber-700 bg-amber-50 p-3 rounded-lg">
                        ⚠️ La capacité ne peut pas être inférieure aux billets vendus ({{ $evenement->billets_vendus }})
                    </p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Image -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="text-lg font-bold text-gray-900">Image</h3>
            </div>
            <div class="p-6">
                @if($evenement->affiche)
                    <div class="mb-4"><img src="{{ Storage::url($evenement->affiche) }}" class="h-24 rounded-lg"></div>
                @endif
                <input type="file" name="affiche" id="affiche" accept="image/*" class="hidden">
                <label for="affiche" class="cursor-pointer text-blue-600 hover:text-blue-700 text-sm">📷 Changer l'image</label>
                @error('affiche') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        
        <!-- Ticket Types -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="text-lg font-bold text-gray-900">Types de billets</h3>
            </div>
            <div class="p-6">
                <template x-for="(ticket, index) in ticketTypes" :key="ticket.key">
                    <div x-show="ticket._destroy !== '1'" class="flex flex-wrap gap-3 mb-4 p-5 bg-gray-50 rounded-xl border">
                        <input type="hidden" :name="`types[${index}][id]`" x-model="ticket.id">
                        <input type="hidden" :name="`types[${index}][_destroy]`" x-model="ticket._destroy">
                        
                        <div class="flex-1 min-w-[160px]">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Type *</label>
                            <select :name="`types[${index}][type]`" x-model="ticket.type" @change="updateQuantite()" required
                                    class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-indigo-500 bg-white">
                                <option value="">Sélectionner</option>
                                @foreach($availableTypes as $typeKey => $typeLabel)
                                    <option value="{{ $typeKey }}">{{ $typeLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="w-44">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Prix (DH)</label>
                            <input type="number" :name="`types[${index}][prix]`" x-model="ticket.prix" step="0.01" min="0" required
                                   class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        
                        <div class="w-44">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Quantité</label>
                            <input type="number" :name="`types[${index}][quantite]`" x-model="ticket.quantite" @input="updateQuantite()" min="0" required
                                   class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        
                        <button type="button" @click="removeType(index)" class="text-red-400 hover:text-red-600 p-2">🗑️</button>
                    </div>
                </template>
                
                <button type="button" @click="addType()" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">+ Ajouter un type</button>
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
                        <input type="radio" name="statut" value="Brouillon" {{ old('statut', $evenement->statut)=='Brouillon'?'checked':'' }} class="mt-0.5">
                        <div><span class="text-sm font-semibold">Brouillon</span><p class="text-xs text-gray-500">Non visible</p></div>
                    </label>
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="radio" name="statut" value="Publié" {{ old('statut', $evenement->statut)=='Publié'?'checked':'' }} class="mt-0.5">
                        <div><span class="text-sm font-semibold">Publier</span><p class="text-xs text-gray-500">Visible</p></div>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex justify-end gap-3 bg-white p-5 rounded-2xl shadow-lg border border-gray-200 sticky bottom-4">
            <a href="{{ route('organisateur.evenements.show', $evenement) }}" class="px-6 py-2.5 border-2 border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 font-semibold">Annuler</a>
            <button type="submit" class="px-8 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-md">Mettre à jour</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
const billetsVendus = {{ $evenement->billets_vendus ?? 0 }};

function eventForm() {
    return {
        capacite: Number('{{ old('capacite', $evenement->capacite) }}') || 0,
        ticketTypes: @json($typesBillets),
        
        init() {
            if (!this.ticketTypes.some(t => t.quantite > 0) && this.capacite > 0) this.recalcStandard();
        },
        
        addType() {
            if (this.ticketTypes.length < 3) {
                this.ticketTypes.push({ id: null, type: '', prix: 0, quantite: 0, _destroy: '0', key: 'new_'+Date.now() });
                this.recalcStandard();
            } else { alert('Maximum 3 types'); }
        },
        
        removeType(index) {
            if (this.ticketTypes.length > 1) {
                if (this.ticketTypes[index].id) this.ticketTypes[index]._destroy = '1';
                else this.ticketTypes.splice(index, 1);
                this.recalcStandard();
            }
        },
        
        updateCapacity() {
            const cap = Number(document.querySelector('input[name=capacite]').value) || 0;
            if (cap < billetsVendus) { alert('Capacité >= billets vendus'); document.querySelector('input[name=capacite]').value = billetsVendus; }
            this.capacite = Number(document.querySelector('input[name=capacite]').value);
            this.recalcStandard();
        },
        
        updateQuantite() { this.recalcStandard(); },
        
        recalcStandard() {
            const capacity = Number(this.capacite) || 0;
            const others = this.ticketTypes.filter(t => t.type!=='Standard' && t._destroy!=='1').reduce((s,t)=>s+(Number(t.quantite)||0),0);
            const std = this.ticketTypes.find(t => t.type==='Standard' && t._destroy!=='1');
            if (std && capacity>0) std.quantite = Math.max(0, capacity - others);
        }
    };
}

// Image preview
document.getElementById('affiche')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file?.size > 2*1024*1024) { alert('Max 2MB'); this.value=''; return; }
    if (file) {
        const reader = new FileReader();
        reader.onload = ev => {
            const prev = document.createElement('div');
            prev.className = 'mb-4';
            prev.innerHTML = '<img src="'+ev.target.result+'" class="h-24 rounded-lg">';
            this.parentNode.insertBefore(prev, this.nextSibling);
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
@endsection