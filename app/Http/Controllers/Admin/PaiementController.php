<?php
// app/Http/Controllers/Admin/PaiementController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Models\Commande;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaiementController extends Controller
{
    /**
     * Afficher la liste des paiements
     */
    public function index(Request $request)
    {
        $query = Paiement::with(['commande.client']);
        
        // Filtre par recherche
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('reference', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('transaction_id', 'LIKE', '%' . $request->search . '%');
            });
        }
        
        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        
        // Filtre par prestataire
        if ($request->filled('prestataire')) {
            $query->where('prestataire', $request->prestataire);
        }
        
        // Filtre par date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        
        $paiements = $query->latest()->paginate(15);
        
        $stats = [
            'total' => Paiement::count(),
            'payes' => Paiement::where('statut', 'Payé')->count(),
            'echecs' => Paiement::where('statut', 'Échec')->count(),
            'montant_total' => Paiement::where('statut', 'Payé')->sum('montant'),
        ];
        
        // Notification de consultation
        $this->createNotification(
            auth()->id(),
            'Consultation des paiements',
            "Vous avez consulté la liste des paiements. Total: {$stats['total']} transactions",
            'info',
            'admin.paiements.index'
        );
        
        return view('admin.paiements.index', compact('paiements', 'stats'));
    }

    /**
     * Afficher les détails d'un paiement
     */
    public function show(Paiement $paiement)
    {
        $paiement->load(['commande.client', 'commande.billets.evenement']);
        
        $this->createNotification(
            auth()->id(),
            'Consultation paiement',
            "Vous avez consulté le paiement #{$paiement->id} - {$paiement->reference}",
            'info',
            'admin.paiements.show',
            ['paiement_id' => $paiement->id]
        );
        
        return view('admin.paiements.show', compact('paiement'));
    }

    /**
     * Réessayer un paiement échoué
     */
    public function retry(Paiement $paiement)
    {
        if ($paiement->statut !== 'Échec') {
            return back()->with('error', 'Seuls les paiements échoués peuvent être réessayés.');
        }
        
        try {
            DB::beginTransaction();
            
            // Ici, implémentez la logique de réessai avec votre prestataire de paiement
            // Par exemple, avec Stripe ou PayPal
            
            // Simuler une nouvelle tentative
            $success = $this->processRetryPayment($paiement);
            
            if ($success) {
                $paiement->update([
                    'statut' => 'Payé',
                    'date_paiement' => now(),
                ]);
                
                // Mettre à jour la commande
                $paiement->commande->update(['statut' => 'Confirmée']);
                
                $this->createNotification(
                    auth()->id(),
                    'Paiement réessayé',
                    "Le paiement #{$paiement->id} a été réessayé avec succès",
                    'success',
                    'admin.paiements.show',
                    ['paiement_id' => $paiement->id]
                );
                
                DB::commit();
                return back()->with('success', 'Paiement réessayé avec succès !');
            } else {
                throw new \Exception('La tentative de paiement a échoué');
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur réessai paiement', [
                'paiement_id' => $paiement->id,
                'error' => $e->getMessage()
            ]);
            
            $this->createNotification(
                auth()->id(),
                'Erreur réessai paiement',
                "Le réessai du paiement #{$paiement->id} a échoué: " . $e->getMessage(),
                'error',
                'admin.paiements.show',
                ['paiement_id' => $paiement->id]
            );
            
            return back()->with('error', 'Erreur lors du réessai: ' . $e->getMessage());
        }
    }

    /**
     * Rembourser un paiement
     */
    public function refund(Paiement $paiement)
    {
        if ($paiement->statut !== 'Payé') {
            return back()->with('error', 'Seuls les paiements payés peuvent être remboursés.');
        }
        
        try {
            DB::beginTransaction();
            
            // Ici, implémentez la logique de remboursement avec votre prestataire de paiement
            $success = $this->processRefund($paiement);
            
            if ($success) {
                $paiement->update(['statut' => 'Remboursé']);
                
                // Mettre à jour la commande
                $paiement->commande->update(['statut' => 'Remboursée']);
                
                // Annuler les billets associés
                foreach ($paiement->commande->billets as $billet) {
                    $billet->update(['statut' => 'Remboursé']);
                }
                
                $this->createNotification(
                    auth()->id(),
                    'Paiement remboursé',
                    "Le paiement #{$paiement->id} a été remboursé avec succès",
                    'success',
                    'admin.paiements.show',
                    ['paiement_id' => $paiement->id]
                );
                
                $this->createNotification(
                    $paiement->commande->client_id,
                    'Remboursement effectué',
                    "Votre paiement de {$paiement->montant} DH a été remboursé",
                    'success',
                    'client.commandes.show',
                    ['commande_id' => $paiement->commande_id]
                );
                
                DB::commit();
                return back()->with('success', 'Paiement remboursé avec succès !');
            } else {
                throw new \Exception('Le remboursement a échoué');
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur remboursement', [
                'paiement_id' => $paiement->id,
                'error' => $e->getMessage()
            ]);
            
            $this->createNotification(
                auth()->id(),
                'Erreur remboursement',
                "Le remboursement du paiement #{$paiement->id} a échoué: " . $e->getMessage(),
                'error',
                'admin.paiements.show',
                ['paiement_id' => $paiement->id]
            );
            
            return back()->with('error', 'Erreur lors du remboursement: ' . $e->getMessage());
        }
    }
    
    /**
     * Exporter les paiements
     */
    public function export(Request $request)
    {
        $query = Paiement::with(['commande.client']);
        
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        
        $paiements = $query->get();
        
        $csv = "ID,Référence,Transaction ID,Client,Montant,Prestataire,Statut,Date\n";
        
        foreach ($paiements as $paiement) {
            $client = $paiement->commande->client ?? null;
            $csv .= "{$paiement->id},{$paiement->reference},{$paiement->transaction_id},";
            $csv .= ($client ? $client->prenom . ' ' . $client->nom : 'N/A') . ",";
            $csv .= "{$paiement->montant} DH,{$paiement->prestataire},{$paiement->statut},";
            $csv .= "{$paiement->created_at->format('d/m/Y H:i')}\n";
        }
        
        $this->createNotification(
            auth()->id(),
            'Export des paiements',
            "Vous avez exporté " . $paiements->count() . " paiements",
            'info',
            'admin.paiements.index'
        );
        
        return response($csv)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="paiements_' . date('Y-m-d') . '.csv"');
    }
    
    /**
     * Traiter le réessai de paiement (à implémenter avec votre API)
     */
    private function processRetryPayment(Paiement $paiement)
    {
        // À implémenter avec Stripe, PayPal, etc.
        // Exemple avec Stripe:
        // $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        // try {
        //     $charge = $stripe->charges->create([
        //         'amount' => $paiement->montant * 100,
        //         'currency' => 'mad',
        //         'source' => $paiement->source_token,
        //         'description' => 'Réessai paiement #' . $paiement->id,
        //     ]);
        //     $paiement->update(['transaction_id' => $charge->id]);
        //     return true;
        // } catch (\Exception $e) {
        //     return false;
        // }
        
        // Pour l'instant, simulation
        return true;
    }
    
    /**
     * Traiter le remboursement (à implémenter avec votre API)
     */
    private function processRefund(Paiement $paiement)
    {
        // À implémenter avec Stripe, PayPal, etc.
        // Exemple avec Stripe:
        // $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        // try {
        //     $refund = $stripe->refunds->create([
        //         'charge' => $paiement->transaction_id,
        //     ]);
        //     return true;
        // } catch (\Exception $e) {
        //     return false;
        // }
        
        // Pour l'instant, simulation
        return true;
    }
    
    /**
     * Créer une notification
     */
    private function createNotification($userId, $title, $message, $type = 'info', $route = null, $data = null)
    {
        try {
            Notification::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => $data,
                'is_read' => false
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur création notification', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'title' => $title
            ]);
        }
    }
}