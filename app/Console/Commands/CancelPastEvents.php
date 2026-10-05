<?php

namespace App\Console\Commands;

use App\Models\Evenement;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CancelPastEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:complete-past';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marque automatiquement comme "Terminé" les événements dont la date est passée';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $now = Carbon::now();
            
            // Mettre à jour les événements passés qui ne sont pas encore terminés
            $updated = Evenement::where('date', '<', $now)
                ->where('statut', '!=', 'Terminé')
                ->update(['statut' => 'Terminé']);
            
            if ($updated > 0) {
                $this->info("✓ {$updated} événement(s) marqué(s) comme terminé(s)");
            } else {
                $this->info("✓ Aucun événement à terminer");
            }
            
        } catch (\Exception $e) {
            $this->error('Erreur: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
