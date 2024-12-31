<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Tarea;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
                        ->orWhereNotNull('fecha_inicio_generacion'); // Incluir tareas periódicas
                })
                ->where('estado', '!=', 'COMPLETADA') // Excluir tareas completadas
                ->whereHas('users', function ($query) {
                    $query->where('user_id', auth()->id()); // Filtrar asignadas al usuario autenticado
                })
                ->orderByRaw('fecha_vencimiento IS NULL, fecha_vencimiento ASC')
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
                    if (!empty($task->fecha_vencimiento)) {
                        $events[] = [
                            'id' => $task->id . '-vencimiento',
                            'title' => $task->asunto->nombre ?? 'Sin Asunto',
                            'start' => $task->fecha_vencimiento,
                            'color' => $this->getTaskColor('vencimiento'),
                            'classNames' => ['evento-vencimiento'],
                            'extendedProps' => [
                                'cliente' => $task->cliente->nombre_fiscal ?? 'Sin Cliente',
                            ],
                        ];
                    }

                    if (!empty($task->fecha_planificacion)) {
                        $events[] = [
                            'id' => $task->id . '-planificacion',
                            'title' => $task->asunto->nombre ?? 'Sin Asunto',
                            'start' => $task->fecha_planificacion,
                            'color' => $this->getTaskColor('planificacion'),
                            'classNames' => ['evento-planificacion'],
                            'extendedProps' => [
                                'cliente' => $task->cliente->nombre_fiscal ?? 'Sin Cliente',
                            ],
                        ];
                    }

                    // Manejar tareas periódicas
                    if (!empty($task->fecha_inicio_generacion) && $task->periodicidad !== 'NO') {
                        $nextDate = $this->calculateNextPeriodicDate($task->fecha_inicio_generacion, $task->periodicidad);

                        if ($nextDate && $nextDate->between($start, $end)) {
                            $event = [
                                'id' => $task->id . '-periodicidad',
                                'title' => ($task->asunto->nombre ?? 'Sin Asunto') . ' - ' . $task->periodicidad,
                                'start' => $nextDate,
                                'color' => $this->getTaskColor('periodicidad'),
                                'classNames' => ['evento-periodicidad'],
                                'extendedProps' => [
                                    'cliente' => $task->cliente->nombre_fiscal ?? 'Sin Cliente',
                                ],
                            ];
                            // Log::info('Evento creado para tarea periódica', ['event' => $event]);
                            $events[] = $event;
                        }
                    }
                }
               // Log::info('Eventos ordenados en el backend', ['events' => $events]);
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

    private function calculateNextPeriodicDate($startDate, $periodicity)
    {
        $startDate = Carbon::parse($startDate);

        switch ($periodicity) {
            case 'SEMANAL':
                return $startDate->addWeek();
            case 'MENSUAL':
                return $startDate->addMonth();
            case 'TRIMESTRAL':
                return $startDate->addMonths(3);
            case 'ANUAL':
                return $startDate->addYear();
            default:
                return $startDate; // Si la periodicidad no es válida, devolver la fecha inicial
        }
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

    public function getEventsByDate(Request $request)
    {
        try {
            $date = $request->query('date');

            if (!$date) {
                return response()->json(['error' => 'Fecha no proporcionada'], 400);
            }

            $tasks = Tarea::with(['cliente', 'asunto', 'tipo', 'users'])
                ->where(function ($query) use ($date) {
                    $query->whereDate('fecha_planificacion', $date)
                        ->orWhereDate('fecha_vencimiento', $date)
                        ->orWhereNotNull('fecha_inicio_generacion'); // Incluir tareas periódicas
                })
                ->where('estado', '!=', 'COMPLETADA') // Excluir tareas completadas
                ->whereHas('users', function ($query) {
                    $query->where('user_id', auth()->id()); // Filtrar asignadas al usuario autenticado
                })
                ->orderByRaw('fecha_vencimiento IS NULL, fecha_vencimiento ASC')
                ->get();


            $events = $tasks->flatMap(function ($task) use ($date) {
                $events = [];

                if (is_object($task)) {
                    if (!empty($task->fecha_vencimiento) && Carbon::parse($task->fecha_vencimiento)->isSameDay(Carbon::parse($date))) {
                        $events[] = [
                            'id' => $task->id . '-vencimiento',
                            'title' => $task->asunto->nombre ?? 'Sin Asunto',
                            'color' => $this->getTaskColor('vencimiento'),
                            'classNames' => ['evento-vencimiento'],
                            'extendedProps' => [
                                'cliente' => $task->cliente->nombre_fiscal ?? 'Sin Cliente',
                            ],
                            'description' => Str::limit($task->descripcion ?? '', 100),

                        ];
                    }

                    if (!empty($task->fecha_planificacion) && $task->fecha_planificacion == $date) {
                        $events[] = [
                            'id' => $task->id . '-planificacion',
                            'title' => $task->asunto->nombre ?? 'Sin Asunto',
                            'color' => $this->getTaskColor('planificacion'),
                            'classNames' => ['evento-planificacion'],
                            'extendedProps' => [
                                'cliente' => $task->cliente->nombre_fiscal ?? 'Sin Cliente',
                            ],
                            'description' => Str::limit($task->descripcion ?? '', 100),
                        ];
                    }
                }

                return $events;
            });


            return response()->json($events->toArray()); // Asegurar que sea un array
        } catch (\Exception $e) {
            Log::error('Error obteniendo eventos por fecha', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
