{{-- resources/views/admin/organisateurs/permissions.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Gérer les permissions - ' . $user->prenom . ' ' . $user->nom)
@section('page-title', 'Gérer les permissions')
@section('page-description', $user->prenom . ' ' . $user->nom)

@section('page-actions')
    <a href="{{ route('admin.organisateurs.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Retour
    </a>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow max-w-2xl mx-auto">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}
            </div>
            <div>
                <h3 class="text-lg font-semibold">{{ $user->prenom }} {{ $user->nom }}</h3>
                <p class="text-gray-500">{{ $user->email }}</p>
                <p class="text-sm text-gray-400 mt-1">
                    <i class="fas fa-calendar-alt mr-1"></i> Inscrit le {{ $user->created_at->format('d/m/Y') }}
                </p>
            </div>
        </div>
    </div>
    
    <form action="{{ route('admin.organisateurs.permissions.update', $user) }}" method="POST" class="p-6">
        @csrf
        {{-- Pas besoin de @method('PUT') car on utilise POST --}}
        
        <div class="mb-4 p-3 bg-blue-50 rounded-lg">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-2"></i>
                <p class="text-sm text-blue-700">
                    Activez les permissions que vous souhaitez attribuer à cet organisateur. 
                    Les permissions désactivées seront refusées.
                </p>
            </div>
        </div>
        
        <div class="space-y-4">
            <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                <label class="flex items-start cursor-pointer">
                    <input type="checkbox" name="perm_creation_event" value="1" 
                           {{ $user->perm_creation_event ? 'checked' : '' }}
                           class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <div class="ml-3">
                        <p class="font-medium text-gray-800">Créer des événements</p>
                        <p class="text-sm text-gray-500">Permet à l'organisateur de créer de nouveaux événements</p>
                    </div>
                </label>
            </div>
            
            <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                <label class="flex items-start cursor-pointer">
                    <input type="checkbox" name="perm_modification_event" value="1" 
                           {{ $user->perm_modification_event ? 'checked' : '' }}
                           class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <div class="ml-3">
                        <p class="font-medium text-gray-800">Modifier des événements</p>
                        <p class="text-sm text-gray-500">Permet à l'organisateur de modifier ses événements existants</p>
                    </div>
                </label>
            </div>
            
            <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                <label class="flex items-start cursor-pointer">
                    <input type="checkbox" name="perm_suppression_event" value="1" 
                           {{ $user->perm_suppression_event ? 'checked' : '' }}
                           class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <div class="ml-3">
                        <p class="font-medium text-gray-800">Supprimer des événements</p>
                        <p class="text-sm text-gray-500">Permet à l'organisateur de supprimer ses événements (s'il n'y a pas de ventes)</p>
                    </div>
                </label>
            </div>
            
            <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                <label class="flex items-start cursor-pointer">
                    <input type="checkbox" name="perm_view_stats" value="1" 
                           {{ $user->perm_view_stats ? 'checked' : '' }}
                           class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <div class="ml-3">
                        <p class="font-medium text-gray-800">Voir les statistiques</p>
                        <p class="text-sm text-gray-500">Permet à l'organisateur de consulter les statistiques de ventes</p>
                    </div>
                </label>
            </div>
        </div>
        
        <div class="mt-6 pt-4 border-t flex justify-end space-x-3">
            <a href="{{ route('admin.organisateurs.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Annuler
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-save mr-2"></i>
                Enregistrer les permissions
            </button>
        </div>
    </form>
</div>
@endsection