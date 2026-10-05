<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TypeBillet;
use Illuminate\Support\Facades\DB;

class DeduplicateTicketTypes extends Command
{
    protected $signature = 'types:deduplicate';
    protected $description = 'Remove duplicate ticket types, keeping the first of each type per event';

    public function handle()
    {
        // Find all duplicates
        $duplicates = DB::table('types_billets')
            ->selectRaw('evenement_id, nom, COUNT(*) as count, MIN(id) as first_id')
            ->groupBy('evenement_id', 'nom')
            ->having(DB::raw('COUNT(*)'), '>', 1)
            ->get();

        if ($duplicates->isEmpty()) {
            $this->info('✅ No duplicate types found!');
            return 0;
        }

        $this->warn('Found ' . $duplicates->count() . ' duplicate type(s):\n');

        $totalDeleted = 0;
        foreach ($duplicates as $dup) {
            $this->line("Event {$dup->evenement_id} - Type '{$dup->nom}': {$dup->count} copies");
            
            // Delete all EXCEPT the first one
            $deleted = TypeBillet::where('evenement_id', $dup->evenement_id)
                ->where('nom', $dup->nom)
                ->where('id', '!=', $dup->first_id)
                ->delete();
            
            $this->line("  ✓ Deleted {$deleted} duplicate(s)");
            $totalDeleted += $deleted;
        }

        $this->info("\n✅ Cleanup complete! Deleted {$totalDeleted} duplicate type(s).\n");
        return 0;
    }
}
