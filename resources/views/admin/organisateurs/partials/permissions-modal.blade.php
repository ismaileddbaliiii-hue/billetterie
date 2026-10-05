{{-- resources/views/admin/organisateurs/partials/permissions-modal.blade.php --}}
<form action="{{ route('admin.organisateurs.permissions.update', $user) }}" method="POST" id="permissions-form">
    @csrf
    @method('PUT')
    
    <div class="space-y-6">
        <!-- Informations de l'organisateur -->
        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-4 border border-purple-100">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-md">
                        {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}
                    </div>
                </div>
                <div class="flex-1">
                    <h4 class="text-base font-semibold text-gray-900">{{ $user->prenom }} {{ $user->nom }}</h4>
                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                    <div class="flex items-center mt-1">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $user->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $user->active ? 'bg-green-500' : 'bg-red-500' }} mr-1"></span>
                            {{ $user->active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Section des permissions -->
        <div>
            <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6-4h12a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 4v2a2 2 0 01-2 2H8a2 2 0 01-2-2v-2"></path>
                </svg>
                Permissions
            </h4>
            
            <div class="space-y-3">
                <!-- Permission Création d'événements -->
                <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200 hover:border-purple-200 transition-all duration-200">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Création d'événements</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 ml-7">Permet à l'organisateur de créer de nouveaux événements sur la plateforme</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="perm_creation_event" value="1" class="sr-only peer" 
                               {{ $user->perm_creation_event ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                    </label>
                </div>
                
                <!-- Permission Modification d'événements -->
                <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200 hover:border-purple-200 transition-all duration-200">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Modification d'événements</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 ml-7">Permet de modifier les événements existants</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="perm_modification_event" value="1" class="sr-only peer" 
                               {{ $user->perm_modification_event ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                    </label>
                </div>
                
                <!-- Permission Suppression d'événements -->
                <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200 hover:border-purple-200 transition-all duration-200">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Suppression d'événements</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 ml-7">Permet de supprimer des événements (attention : irréversible)</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="perm_suppression_event" value="1" class="sr-only peer" 
                               {{ $user->perm_suppression_event ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                    </label>
                </div>
                
                <!-- Permission Visualisation des statistiques -->
                <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200 hover:border-purple-200 transition-all duration-200">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Visualisation des statistiques</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 ml-7">Permet d'accéder aux tableaux de bord et statistiques détaillées</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="perm_view_stats" value="1" class="sr-only peer" 
                               {{ $user->perm_view_stats ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Boutons d'action -->
        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
            <button type="button" onclick="closePermissionsModal()" 
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200 font-medium">
                Annuler
            </button>
            <button type="submit" 
                    class="px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-lg transition-all duration-200 font-medium shadow-sm">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Enregistrer les permissions
            </button>
        </div>
    </div>
</form>

<script>
// Gestionnaire de soumission du formulaire
document.getElementById('permissions-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Désactiver le bouton et afficher le chargement
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent inline-block mr-2"></div> Enregistrement...';
    
    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            // Notification de succès
            if (typeof showNotification === 'function') {
                showNotification('Permissions mises à jour avec succès !', 'success');
            } else {
                alert('Permissions mises à jour avec succès !');
            }
            
            // Fermer le modal après 1 seconde
            setTimeout(() => {
                if (typeof closePermissionsModal === 'function') {
                    closePermissionsModal();
                }
                // Recharger la page pour actualiser les données
                location.reload();
            }, 1000);
        } else {
            throw new Error(data.error || 'Erreur lors de la mise à jour des permissions');
        }
    } catch (error) {
        console.error('Error:', error);
        if (typeof showNotification === 'function') {
            showNotification(error.message, 'error');
        } else {
            alert('Erreur: ' + error.message);
        }
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});
</script>