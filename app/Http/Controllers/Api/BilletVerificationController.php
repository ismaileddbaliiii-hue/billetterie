<?php
// app/Http/Controllers/Api/BilletVerificationController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Billet;
use Illuminate\Http\Request;

class BilletVerificationController extends Controller
{
    /**
     * Vérifier un billet par son code unique
     */
    public function verify($code, Request $request)
    {
        $billet = Billet::where('code_unique', $code)
            ->with(['evenement', 'typeBillet'])
            ->first();
        
        if (!$billet) {
            return response()->json([
                'valid' => false,
                'reason' => 'Billet non trouvé'
            ], 404);
        }
        
        // Vérifier si le billet est valide
        if ($billet->statut !== 'Valide') {
            return response()->json([
                'valid' => false,
                'reason' => 'Billet non valide',
                'statut' => $billet->statut
            ]);
        }
        
        // Vérifier si l'événement n'est pas passé
        if ($billet->evenement->date->isPast()) {
            return response()->json([
                'valid' => false,
                'reason' => 'Événement déjà terminé'
            ]);
        }
        
        // Vérifier si l'événement est annulé
        if ($billet->evenement->statut === 'Annulé') {
            return response()->json([
                'valid' => false,
                'reason' => 'Événement annulé'
            ]);
        }
        
        return response()->json([
            'valid' => true,
            'event' => [
                'id' => $billet->evenement->id,
                'titre' => $billet->evenement->titre,
                'date' => $billet->evenement->date->format('d/m/Y H:i'),
                'lieu' => $billet->evenement->lieu ? $billet->evenement->lieu->nom : null,
                'adresse' => $billet->evenement->lieu ? $billet->evenement->lieu->adresse : null,
            ],
            'holder' => [
                'nom' => $billet->nom_spectateur,
                'email' => $billet->email_spectateur,
            ],
            'type' => $billet->typeBillet->nom ?? 'Standard',
            'prix' => $billet->prix,
            'code' => $billet->code_unique,
        ]);
    }
}