<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GenerarTareasPeriodicas extends Command
{
    // Nombre y descripción del comando
    protected $signature = 'tareas:generar';
    protected $description = 'Genera tareas periódicas según su configuración';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        // Obtener las tareas que son periódicas y cuya fecha de generación corresponde o ya pasó
        $tareas = DB::table('tareas')
            ->where('periodicidad', '!=', 'NO')
            ->whereDate('fecha_inicio_generacion', '<=', now())
            ->get();

        foreach ($tareas as $tarea) {
            // Calcular la nueva fecha de generación
            $nuevaFechaGeneracion = $this->calcularProximaFecha($tarea->fecha_inicio_generacion, $tarea->periodicidad);

            // Crear una copia de la tarea con las modificaciones necesarias
            DB::table('tareas')->insert([
                'asunto_id' => $tarea->asunto_id,
                'tipo_id' => $tarea->tipo_id,
                'subtipo' => $tarea->subtipo,
                'estado' => 'PENDIENTE', // El estado siempre será PENDIENTE
                'cliente_id' => $tarea->cliente_id,
                'asignacion_id' => $tarea->asignacion_id,
                'descripcion' => $tarea->descripcion,
                'observaciones' => $tarea->observaciones,
                'archivo' => $tarea->archivo,
                'facturable' => $tarea->facturable,
                'facturado' => $tarea->facturado,
                'precio' => $tarea->precio,
                'suplido' => $tarea->suplido,
                'coste' => $tarea->coste,
                'fecha_inicio' => now(), // La fecha de inicio es la actual
                'fecha_vencimiento' => null, // Fecha de vencimiento vacía
                'fecha_imputacion' => null,
                'fecha_planificacion' => now(), // La fecha de planificación es la actual
                'tiempo_previsto' => $tarea->tiempo_previsto,
                'tiempo_real' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'periodicidad' => 'NO', // Las tareas duplicadas no son periódicas
                'fecha_inicio_generacion' => null,
            ]);

            // Actualizar la fecha de próxima generación de la tarea original
            DB::table('tareas')
                ->where('id', $tarea->id)
                ->update(['fecha_inicio_generacion' => $nuevaFechaGeneracion]);
        }

        $this->info('Tareas periódicas generadas exitosamente.');
    }

    private function calcularProximaFecha($fechaActual, $periodicidad)
    {
        $fecha = Carbon::parse($fechaActual);

        switch ($periodicidad) {
            case 'SEMANAL':
                return $fecha->addWeek();
            case 'MENSUAL':
                return $fecha->addMonth();
            case 'TRIMESTRAL':
                return $fecha->addMonths(3);
            case 'ANUAL':
                return $fecha->addYear();
            default:
                return null;
        }
    }
}
