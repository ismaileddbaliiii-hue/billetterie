<?php
// app/Http/Controllers/Api/TicketController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Billet;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    /**
     * Vérifier un billet par son code unique (sans validation)
     */
    public function verify($code)
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
    
    /**
     * Valider un billet (scan)
     */
    public function validateTicket($code, Request $request)
    {
        $billet = Billet::where('code_unique', $code)->first();
        
        if (!$billet) {
            return response()->json([
                'success' => false,
                'message' => 'Billet non trouvé'
            ], 404);
        }
        
        // Vérifier si l'utilisateur est authentifié
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentification requise'
            ], 401);
        }
        
        $evenement = $billet->evenement;
        
        // Vérifier les droits (Admin ou organisateur de l'événement)
        if ($user->role !== 'Admin' && $evenement->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à valider ce billet'
            ], 403);
        }
        
        // Vérifier si le billet est déjà validé
        if ($billet->statut !== 'Valide') {
            return response()->json([
                'success' => false,
                'message' => 'Ce billet n\'est plus valide',
                'statut' => $billet->statut
            ], 400);
        }
        
        // Vérifier si l'événement n'est pas passé
        if ($evenement->date->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Cet événement est déjà terminé'
            ], 400);
        }
        
        // Vérifier si l'événement est annulé
        if ($evenement->statut === 'Annulé') {
            return response()->json([
                'success' => false,
                'message' => 'Cet événement a été annulé'
            ], 400);
        }
        
        // Valider le billet
        $billet->update([
            'statut' => 'Valide',
            'date_validation' => now(),
            'valide_par' => $user->id
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Billet validé avec succès',
            'data' => [
                'code' => $billet->code_unique,
                'spectateur' => $billet->nom_spectateur,
                'email' => $billet->email_spectateur,
                'event' => $evenement->titre,
                'date_validation' => now()->format('d/m/Y H:i:s')
            ]
        ]);
    }
    
    /**
     * Générer le QR code d'un billet
     */
    public function generateQRCode($code)
    {
        $billet = Billet::where('code_unique', $code)->first();
        
        if (!$billet) {
            return response()->json([
                'success' => false,
                'message' => 'Billet non trouvé'
            ], 404);
        }
        
        // Générer le QR code en SVG (ne nécessite pas l'extension imagick)
        $qrCode = QrCode::format('svg')->size(300)->generate($billet->code_unique);
        
        return response()->json([
            'success' => true,
            'qr_code' => $qrCode,
            'code' => $billet->code_unique
        ]);
    }
    
    /**
     * Télécharger le billet en PDF
     */
    public function download($code)
    {
        $billet = Billet::where('code_unique', $code)->first();
        
        if (!$billet) {
            abort(404, 'Billet non trouvé');
        }
        
        // Vérifier l'accès
        $user = auth()->user();
        if (!$user) {
            abort(401, 'Authentification requise');
        }
        
        $billet->load(['evenement', 'typeBillet']);
        
        $pdf = Pdf::loadView('pdf.ticket', compact('billet'));
        
        return $pdf->download('ticket_' . $billet->code_unique . '.pdf');
    }
    
    /**
     * Trouver un billet par son code
     */
    public function findByCode($code)
    {
        $billet = Billet::where('code_unique', $code)->first();
        
        if (!$billet) {
            return response()->json([
                'success' => false,
                'message' => 'Billet non trouvé'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'billet_id' => $billet->id,
            'event_id' => $billet->evenement_id,
            'code' => $billet->code_unique,
            'statut' => $billet->statut
        ]);
    }
    
    /**
     * Liste des tickets de l'utilisateur connecté
     */
    public function myTickets(Request $request)
    {
        $user = auth()->user();
        
        $billets = Billet::where('email_spectateur', $user->email)
            ->orWhereHas('commande', function($query) use ($user) {
                $query->where('client_id', $user->id);
            })
            ->with(['evenement', 'typeBillet'])
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));
        
        return response()->json([
            'success' => true,
            'data' => $billets->map(function($billet) {
                return [
                    'id' => $billet->id,
                    'code' => $billet->code_unique,
                    'spectateur' => $billet->nom_spectateur,
                    'email' => $billet->email_spectateur,
                    'prix' => $billet->prix,
                    'statut' => $billet->statut,
                    'evenement' => [
                        'id' => $billet->evenement->id,
                        'titre' => $billet->evenement->titre,
                        'date' => $billet->evenement->date->format('d/m/Y H:i'),
                        'lieu' => $billet->evenement->lieu ? $billet->evenement->lieu->nom : null,
                    ],
                    'type' => $billet->typeBillet->nom ?? 'Standard',
                    'date_achat' => $billet->created_at->format('d/m/Y H:i'),
                ];
            }),
            'pagination' => [
                'current_page' => $billets->currentPage(),
                'last_page' => $billets->lastPage(),
                'per_page' => $billets->perPage(),
                'total' => $billets->total(),
            ]
        ]);
    }
    
    /**
     * Détail d'un ticket
     */
    public function show($code)
    {
        $billet = Billet::where('code_unique', $code)
            ->with(['evenement', 'typeBillet', 'commande'])
            ->first();
        
        if (!$billet) {
            return response()->json([
                'success' => false,
                'message' => 'Billet non trouvé'
            ], 404);
        }
        
        // Vérifier l'accès
        $user = auth()->user();
        if ($user) {
            if ($user->role !== 'Admin' && 
                $billet->email_spectateur !== $user->email && 
                $billet->commande->client_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $billet->id,
                'code' => $billet->code_unique,
                'spectateur' => $billet->nom_spectateur,
                'email' => $billet->email_spectateur,
                'prix' => $billet->prix,
                'statut' => $billet->statut,
                'qr_code_url' => route('api.tickets.qrcode', $billet->code_unique),
                'download_url' => route('api.tickets.download', $billet->code_unique),
                'evenement' => [
                    'id' => $billet->evenement->id,
                    'titre' => $billet->evenement->titre,
                    'description' => $billet->evenement->description,
                    'date' => $billet->evenement->date->format('d/m/Y H:i'),
                    'lieu' => $billet->evenement->lieu ? [
                        'nom' => $billet->evenement->lieu->nom,
                        'adresse' => $billet->evenement->lieu->adresse,
                        'ville' => $billet->evenement->lieu->ville,
                    ] : null,
                ],
                'type' => [
                    'id' => $billet->typeBillet->id ?? null,
                    'nom' => $billet->typeBillet->nom ?? 'Standard',
                ],
                'commande' => [
                    'id' => $billet->commande->id,
                    'reference' => $billet->commande->reference,
                    'date' => $billet->commande->date->format('d/m/Y H:i'),
                ],
                'date_validation' => $billet->date_validation ? $billet->date_validation->format('d/m/Y H:i') : null,
                'created_at' => $billet->created_at->format('d/m/Y H:i'),
            ]
        ]);
    }
}