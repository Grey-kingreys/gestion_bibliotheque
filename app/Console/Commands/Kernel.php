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
        // Mettre à jour les statuts des emprunts en retard chaque jour à minuit
        $schedule->command('emprunts:update-statuts')
                 ->daily()
                 ->at('00:00');

        // Optionnel : le faire aussi toutes les heures pour plus de réactivité
        // $schedule->command('emprunts:update-statuts')->hourly();
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