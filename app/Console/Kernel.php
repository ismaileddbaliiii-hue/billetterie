<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Exécuter la commande d'annulation des événements passés toutes les heures
        $schedule->command('events:cancel-past')
            ->hourly()
            ->withoutOverlapping();
        
        // Optionnel: vous pouvez aussi l'exécuter plus fréquemment
        // $schedule->command('events:cancel-past')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
