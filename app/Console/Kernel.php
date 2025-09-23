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
        // $schedule->command('inspire')->hourly();

        // Ejecutar el comando todos los días a las 8:30 AM
        // $schedule->command('documents:send-licitacion-reminder')->dailyAt('08:30');
        // $schedule->command('documents:send-licitacion-revalidation-reminder')->dailyAt('08:30');
        $schedule->command('documents:send-licitacion-notifications')->dailyAt('08:30');


    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
