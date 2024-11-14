<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteOldNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:delete-old {days=30}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina notificaciones leídas más antiguas que el número de días especificado';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Obtén el número de días desde el argumento o usa 30 por defecto
        $days = $this->argument('days');

        // Calcula la fecha límite
        $cutoffDate = now()->subDays($days);

        // Elimina las notificaciones leídas antes de la fecha límite
        $deleted = DB::table('notifications')
            ->whereNotNull('read_at') // Solo leídas
            ->where('created_at', '<', $cutoffDate) // Más antiguas que la fecha límite
            ->delete();

        $this->info("$deleted notificaciones eliminadas con más de $days días de antigüedad.");

        return 0;
    }
}
