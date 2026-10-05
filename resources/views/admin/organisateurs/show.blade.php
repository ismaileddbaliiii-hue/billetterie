{{-- resources/views/admin/organisateurs/show.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Organisateur - ' . $user->prenom . ' ' . $user->nom)
@section('page-title', 'Détails organisateur')
@section('page-description', 'Visualisation des informations de l’organisateur')

@section('page-actions')
    <a href="{{ route('admin.organisateurs.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Retour à la liste
    </a>
    <a href="{{ route('admin.organisateurs.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm ml-2">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h6m2 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h6m5 0l-1-1m0 0l-4 4m4-4L11 9"></path>
        </svg>
        Modifier
    </a>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="relative">
                <div class="h-32 bg-gradient-to-r from-indigo-600 to-blue-500"></div>
                <div class="absolute -bottom-12 left-6">
                    <div class="h-24 w-24 rounded-full bg-white overflow-hidden border-4 border-white shadow-lg">
                        @if($user->profile_image && Storage::disk('public')->exists($user->profile_image))
                            <img src="{{ Storage::url($user->profile_image) }}" alt="{{ $user->prenom }} {{ $user->nom }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-blue-100 text-blue-600 font-bold text-xl">
                                {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="pt-16 pb-6 px-6">
                <div class="flex flex-wrap justify-between items-center gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $user->prenom }} {{ $user->nom }}</h2>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $user->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->active ? 'Actif' : 'Inactif' }}
                        </span>
                        <p class="text-xs text-gray-500 mt-1">Inscrit le {{ $user->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                <div>
                    <p class="font-medium text-gray-900">Prénom</p>
                    <p>{{ $user->prenom }}</p>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Nom</p>
                    <p>{{ $user->nom }}</p>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Téléphone</p>
                    <p>{{ $user->telephone ?? 'Non défini' }}</p>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Role</p>
                    <p>{{ $user->role }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="font-medium text-gray-900">Biographie</p>
                    <p class="text-gray-600">{{ $user->bio ?: 'Aucune biographie renseignée.' }}</p>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Événements</p>
                    <p>{{ $user->evenements_count ?? $user->evenements()->count() }}</p>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Dernière mise à jour</p>
                    <p>{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('admin.organisateurs.edit', $user) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">Modifier</a>
            <a href="{{ route('admin.organisateurs.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg text-sm font-medium">Retour à la liste</a>
        </div>
    </div>
@endsection  
