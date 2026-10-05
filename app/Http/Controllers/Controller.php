<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    /**
     * Vérifie si l'utilisateur possède une permission donnée.
     * Si non, retourne une erreur 403.
     */
    protected function checkPermission(string $permission): void
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Non authentifié');
        }

        // Les admins ont toutes les permissions
        if ($user->role === 'Admin') {
            return;
        }

        // Pour les autres rôles, vérifier la permission directement
        if ($user->$permission !== true) {
            abort(403, 'Vous n\'avez pas la permission de voir les statistiques.');
        }
    }
}
