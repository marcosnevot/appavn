<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Tarea;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CalendarController extends Controller
{
    /**
     * Muestra la vista principal del panel de administrador.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('calendar.index');
    }

    public function getTasks(Request $request)
    {
        try {
            $start = $request->query('start');
            $end = $request->query('end');

            try {
                // Asegurarse de procesar fechas correctamente
                $start = Carbon::createFromFormat(Carbon::ISO8601, $start);
                $end = Carbon::createFromFormat(Carbon::ISO8601, $end);
            } catch (\Exception $e) {
                Log::error('Formato de fecha inválido', ['start' => $start, 'end' => $end, 'error' => $e->getMessage()]);
                return response()->json(['error' => 'Formato de fecha inválido'], 400);
            }

            // Log::info('Fechas procesadas en el backend', ['start' => $start->toDateTimeString(), 'end' => $end->toDateTimeString()]);

            // Validar que las fechas existan
            if (!$start || !$end) {
                throw new \Exception('Fechas de inicio y fin no proporcionadas.');
            }

            // Obtener tareas con relaciones necesarias y filtradas por usuario autenticado
            $tasks = Tarea::with(['cliente', 'asunto', 'tipo', 'users'])
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('fecha_planificacion', [$start, $end])
                        ->orWhereBetween('fecha_vencimiento', [$start, $end])
                        ->orWhere(function ($subquery) use ($start, $end) {
                            $subquery->whereNotNull('periodicidad')
                                ->whereBetween('fecha_inicio_generacion', [$start, $end]);
                        });
                })
                ->where('estado', '!=', 'COMPLETADA') // Excluir tareas completadas
                ->whereHas('users', function ($query) {
                    $query->where('user_id', auth()->id()); // Filtrar asignadas al usuario autenticado
                })
                ->get();

            // Log para depuración
            // Log::info('Tareas obtenidas para el usuario', [
            // 'user_id' => auth()->id(),
            // 'tasks' => $tasks->pluck('id') // IDs de las tareas
            // ]);

            // Formatear los datos para FullCalendar
            $events = $tasks->flatMap(function ($task) use ($start, $end) {
                $events = [];

                // Validar que $task es un objeto y tiene las propiedades necesarias
                if (is_object($task)) {
                    // Log::info('Procesando tarea:', ['task' => $task->toArray()]);

                    // Evento para la fecha de planificación
                    if (!empty($task->fecha_planificacion)) {
                        $events[] = [
                            'id' => $task->id . '-planificacion',
                            'title' => $task->asunto->nombre ?? 'Sin Asunto',
                            'start' => $task->fecha_planificacion,
                            'color' => $this->getTaskColor('planificacion'),
                            'classNames' => ['evento-planificacion'], // Clase específica
                            'extendedProps' => [
                                'cliente' => $task->cliente->nombre_fiscal ?? 'Sin Cliente',
                            ],
                        ];
                    }

                    // Evento para la fecha de vencimiento
                    if (!empty($task->fecha_vencimiento)) {
                        $events[] = [
                            'id' => $task->id . '-vencimiento',
                            'title' => $task->asunto->nombre ?? 'Sin Asunto',
                            'start' => $task->fecha_vencimiento,
                            'color' => $this->getTaskColor('vencimiento'),
                            'classNames' => ['evento-vencimiento'], // Clase específica
                            'extendedProps' => [
                                'cliente' => $task->cliente->nombre_fiscal ?? 'Sin Cliente',
                            ],
                        ];
                    }

                    if (!empty($task->fecha_inicio_generacion)) {
                        $proximaFecha = $this->calcularProximaFecha(
                            $task->fecha_inicio_generacion,
                            $task->periodicidad
                        );

                        Log::info('Cálculo de próxima fecha realizado', [
                            'tarea_id' => $task->id,
                            'proxima_fecha' => $proximaFecha ? $proximaFecha->toDateTimeString() : 'N/A',
                            'start' => $start->toDateTimeString(),
                            'end' => $end->toDateTimeString(),
                            'en_rango' => $proximaFecha && $proximaFecha->between($start, $end, true),
                        ]);

                        if ($proximaFecha && $proximaFecha->between($start, $end, true)) {
                            Log::info('Procesando tarea periódica', [
                                'tarea_id' => $task->id,
                                'proxima_fecha' => $proximaFecha->toDateTimeString(),
                            ]);

                            $events[] = [
                                'id' => $task->id . '-periodicidad-' . $proximaFecha->format('Ymd'),
                                'title' => ($task->asunto->nombre ?? 'Sin Asunto') . ' (' . ucfirst(strtolower($task->periodicidad)) . ')',
                                'start' => $proximaFecha->toDateTimeString(),
                                'color' => $this->getTaskColor('periodicidad'),
                                'classNames' => ['evento-periodicidad'],
                                'extendedProps' => [
                                    'cliente' => $task->cliente->nombre_fiscal ?? 'Sin Cliente',
                                ],
                            ];
                        } else {
                            Log::warning('Tarea fuera del rango visible', [
                                'tarea_id' => $task->id,
                                'proxima_fecha' => $proximaFecha ? $proximaFecha->toDateTimeString() : 'N/A',
                                'start' => $start->toDateTimeString(),
                                'end' => $end->toDateTimeString(),
                            ]);
                        }
                    }
                } else {
                    Log::error('Elemento inesperado en tareas:', ['task' => $task]);
                }
                Log::info('Eventos enviados al frontend', ['events' => $events]);

                return $events;
            });

            return response()->json($events);
        } catch (\Exception $e) {
            // Loguear cualquier error encontrado
            Log::error('Error obteniendo tareas para el calendario', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function calcularProximaFecha($fechaInicioGeneracion, $periodicidad)
    {
        $fecha = Carbon::parse($fechaInicioGeneracion);

        //Log::info('Iniciando cálculo de próxima fecha', [
        //  'fecha_inicio_generacion' => $fechaInicioGeneracion,
        //'periodicidad' => $periodicidad,
        // ]);

        // Calcular la próxima fecha directamente según la periodicidad
        switch (strtoupper($periodicidad)) {
            case 'SEMANAL':
                $fecha->addWeek();
                break;
            case 'MENSUAL':
                $fecha->addMonth();
                break;
            case 'TRIMESTRAL':
                $fecha->addMonths(3);
                break;
            case 'ANUAL':
                $fecha->addYear();
                break;
            default:
                Log::error('Periodicidad no válida', ['periodicidad' => $periodicidad]);
                return null;
        }

        // Log::info('Próxima fecha generada', ['proxima_fecha' => $fecha->toDateString()]);
        return $fecha;
    }








    private function getTaskColor($type)
    {
        switch ($type) {
            case 'planificacion':
                return '#09d9fd'; // Azul claro
            case 'vencimiento':
                return '#FFCDD2'; // Rojo claro
            case 'periodicidad':
                return '#FFF59D'; // Amarillo claro
            default:
                Log::error('Tipo desconocido en getTaskColor:', ['type' => $type]);
                return '#E0E0E0'; // Gris claro por defecto
        }
    }
}
