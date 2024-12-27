<?php

namespace App\Jobs;

use App\Models\Tarea;
use App\Notifications\TaskAssignedNotification;
use App\Notifications\TaskPeriodicReminderNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerarTareaJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    // Tiempo de espera para ejecutar el Job
    public $timeout = 120;

    /**
     * Ejecutar el trabajo.
     *
     * @return void
     */
    public function handle()
    {
        // Log::info('Generando tareas periódicas.');

        // Obtener todas las tareas periódicas que deben generarse
        $tareas = Tarea::where('periodicidad', '!=', 'NO')
            ->whereDate('fecha_inicio_generacion', '<=', now()) // Obtener tareas cuya fecha de generación ha pasado
            ->with('users')
            ->get();

        foreach ($tareas as $tarea) {
            // Calcular la próxima fecha de generación según la periodicidad
            $nuevaFechaGeneracion = $this->calcularProximaFecha($tarea->fecha_inicio_generacion, $tarea->periodicidad);

            // Solo generar tarea si la nueva fecha de generación ya ha pasado
            if ($nuevaFechaGeneracion <= now()) {
                // Insertar una nueva tarea duplicada con la nueva fecha de generación
                $nuevaTarea = Tarea::create([
                    'asunto_id' => $tarea->asunto_id,
                    'tipo_id' => $tarea->tipo_id,
                    'subtipo' => $tarea->subtipo,
                    'estado' => 'PENDIENTE',
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
                    'fecha_inicio' => now(),
                    'fecha_vencimiento' => null,
                    'fecha_imputacion' => null,
                    'fecha_planificacion' => now(),
                    'tiempo_previsto' => $tarea->tiempo_previsto,
                    'tiempo_real' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'periodicidad' => 'NO', // Las tareas duplicadas no son periódicas
                    'fecha_inicio_generacion' => null, // La fecha de inicio de generación de la nueva tarea es nula
                ]);

                // Verifica y sincroniza usuarios
                // Log::info('Usuarios asociados a la tarea original (ID ' . $tarea->id . '): ' . json_encode($tarea->users));

                if ($tarea->users->isNotEmpty()) { // Verifica que haya usuarios
                    $nuevaTarea->users()->sync($tarea->users->pluck('id')->toArray());

                    foreach ($tarea->users as $user) {
                        try {
                            $user->notify(new TaskPeriodicReminderNotification($nuevaTarea, $tarea->periodicidad));
                            // Log::info('Notificación enviada al usuario ID: ' . $user->id);
                        } catch (\Exception $e) {
                            Log::error('Error al enviar notificación al usuario ID: ' . $user->id . '. Error: ' . $e->getMessage());
                        }
                    }
                } else {
                    Log::warning('La tarea (ID ' . $tarea->id . ') no tiene usuarios asignados.');
                }

                $tarea->update(['fecha_inicio_generacion' => $nuevaFechaGeneracion]);
                // Log::info('Tarea generada con ID: ' . $tarea->id);

            } else {
                // Si la tarea no se genera porque la fecha aún no ha llegado
                Log::info('La tarea con ID ' . $tarea->id . ' aún no está lista para ser generada. Próxima fecha de generación: ' . $nuevaFechaGeneracion);
            }
        }

        // Log::info('Tareas periódicas generadas correctamente.');
    }


    /**
     * Calcular la próxima fecha de generación según la periodicidad
     *
     * @param string $fechaActual
     * @param string $periodicidad
     * @return \Carbon\Carbon
     */
    private function calcularProximaFecha($fechaActual, $periodicidad)
    {
        $fecha = Carbon::parse($fechaActual);

        switch ($periodicidad) {
            case 'SEMANAL':
                return $fecha->addWeek(); // Añadir una semana
            case 'MENSUAL':
                return $fecha->addMonth(); // Añadir un mes
            case 'TRIMESTRAL':
                return $fecha->addMonths(3); // Añadir tres meses
            case 'ANUAL':
                return $fecha->addYear(); // Añadir un año
            default:
                return null; // No se genera tarea si la periodicidad no es válida
        }
    }
}
