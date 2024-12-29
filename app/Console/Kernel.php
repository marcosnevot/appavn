<?php

namespace App\Console;

use App\Jobs\GenerarTareaJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // Ejecuta el comando diariamente a la medianoche
        // $schedule->command('notifications:delete-old')
        //    ->monthly();
        $schedule->job(new GenerarTareaJob)->dailyAt('9:00');
        // $schedule->job(new GenerarTareaJob)->everyMinute();

        // Realizar una copia de seguridad semanal los martes a las 9:00
        $schedule->command('backup:run')->weeklyOn(2, '9:00')->onFailure(function () {
            Log::error('Copia de seguridad fallida.');
        })->onSuccess(function () {
            Log::info('Copia de seguridad completada con éxito.');
        });

        // Limpieza de copias de seguridad semanal los miércoles a las 9:00
        $schedule->command('backup:clean')->weeklyOn(3, '9:00')->onFailure(function () {
            Log::error('Limpieza de copias de seguridad fallida.');
        })->onSuccess(function () {
            Log::info('Limpieza de copias de seguridad completada con éxito.');
        });


        Log::info('Comando tareas:    generar ejecutado.');
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
