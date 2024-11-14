<?php

namespace App\Http\Controllers;

use App\Events\TareaActualizada;
use App\Events\TaskCreated;
use App\Events\TaskDeleted;
use App\Events\TaskUpdated;
use App\Exports\TareasExport;
use App\Exports\TasksExport;
use App\Models\Asunto;
use App\Models\Cliente;
use App\Models\Tarea;
use App\Models\Tipo;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use Carbon\Carbon;
use Illuminate\Console\View\Components\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class TaskController extends Controller
{
    /**
     * Muestra la vista principal de Tareas.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

        // Obtener todas las tareas de la base de datos, ordenadas por las más recientes
        $tasks = Tarea::with(['cliente', 'asunto', 'tipo', 'users'])
            ->orderBy('created_at', 'desc') // Ordenar por la fecha de creación, de más reciente a más antigua
            ->get();

        // Obtener datos adicionales necesarios para el formulario
        $clientes = Cliente::all();
        $asuntos = Asunto::all();
        $tipos = Tipo::all();
        $usuarios = User::all();

        // Pasar las tareas y los datos adicionales a la vista
        return view('tasks.index', compact('tasks', 'clientes', 'asuntos', 'tipos', 'usuarios'));
    }

    public function getTasks(Request $request)
    {
        // Obtener el ID de usuario de la solicitud (si existe)
        $userId = $request->query('user_id');

        // Obtener la fecha actual en formato YYYY-MM-DD
        $fechaHoy = date('Y-m-d');

        // Crear la consulta base para las tareas con relaciones necesarias
        $query = Tarea::with(['cliente', 'asunto', 'tipo', 'users'])
            ->orderBy('created_at', 'desc');

        // Si se pasa un user_id, filtrar las tareas asignadas a ese usuario
        if ($userId) {
            $query->whereHas('users', function ($q) use ($userId) {
                $q->where('users.id', $userId);
            });
        }

        /* Si no hay un filtro específico para fecha de planificación, filtrar por tareas de hoy
        if (!$request->has('fecha_planificacion') || $request->input('fecha_planificacion') === 'Hoy') {
            $query->whereDate('fecha_planificacion', $fechaHoy);
        } */

        // Ejecutar la consulta con paginación
        $tasks = $query->paginate(50);

        // Devolver las tareas en formato JSON, junto con enlaces de paginación
        return response()->json([
            'success' => true,
            'tasks' => $tasks->items(), // Las tareas actuales
            'pagination' => [
                'total' => $tasks->total(),
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'per_page' => $tasks->perPage(),
                'next_page_url' => $tasks->nextPageUrl(),
                'prev_page_url' => $tasks->previousPageUrl()
            ]
        ]);
    }


    public function billingIndex()
    {

        // Obtener todas las tareas de la base de datos, ordenadas por las más recientes
        $tasks = Tarea::with(['cliente', 'asunto', 'tipo', 'users'])
            ->orderBy('created_at', 'desc') // Ordenar por la fecha de creación, de más reciente a más antigua
            ->get();

        // Obtener datos adicionales necesarios para el formulario
        $clientes = Cliente::all();
        $asuntos = Asunto::all();
        $tipos = Tipo::all();
        $usuarios = User::all();

        // Pasar las tareas y los datos adicionales a la vista
        return view('billing.index', compact('tasks', 'clientes', 'asuntos', 'tipos', 'usuarios'));
    }

    public function getBilling(Request $request)
    {
        // Obtener el ID de usuario de la solicitud (si existe)
        $userId = $request->query('user_id');


        // Crear la consulta base para las tareas con relaciones necesarias
        $query = Tarea::with(['cliente', 'asunto', 'tipo', 'users'])
            ->where('facturable', true) // Filtrar para facturable = true
            ->where('facturado', 'No')  // Filtrar para facturado = No
            ->orderBy('created_at', 'desc');

        // Si se pasa un user_id, filtrar las tareas asignadas a ese usuario
        if ($userId) {
            $query->whereHas('users', function ($q) use ($userId) {
                $q->where('users.id', $userId);
            });
        }


        // Ejecutar la consulta con paginación
        $tasks = $query->paginate(50);

        // Devolver las tareas en formato JSON, junto con enlaces de paginación
        return response()->json([
            'success' => true,
            'tasks' => $tasks->items(), // Las tareas actuales
            'pagination' => [
                'total' => $tasks->total(),
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'per_page' => $tasks->perPage(),
                'next_page_url' => $tasks->nextPageUrl(),
                'prev_page_url' => $tasks->previousPageUrl()
            ]
        ]);
    }

    public function timesIndex()
    {

        // Obtener todas las tareas de la base de datos, ordenadas por las más recientes
        $tasks = Tarea::with(['cliente', 'asunto', 'tipo', 'users'])
            ->orderBy('created_at', 'desc') // Ordenar por la fecha de creación, de más reciente a más antigua
            ->get();

        // Obtener datos adicionales necesarios para el formulario
        $clientes = Cliente::all();
        $asuntos = Asunto::all();
        $tipos = Tipo::all();
        $usuarios = User::all();

        // Pasar las tareas y los datos adicionales a la vista
        return view('times.index', compact('tasks', 'clientes', 'asuntos', 'tipos', 'usuarios'));
    }


    public function show($id)
    {
        try {
            // Encuentra la tarea por su ID o lanza un error si no se encuentra
            $task = Tarea::with(['cliente', 'asunto', 'tipo', 'users'])->findOrFail($id);

            // Obtén la lista de todos los usuarios
            $usuarios = User::all();

            // Renderizar la vista modal con los detalles de la tarea y la lista de usuarios
            $html = view('tasks.partials.task-detail-modal', compact('task', 'usuarios'))->render();

            // Devolver el HTML dentro de una respuesta JSON
            return response()->json(['html' => $html]);
        } catch (\Exception $e) {
            // Devuelve una respuesta JSON de error con el mensaje específico
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function store(Request $request)
    {

        try {
            // Inicia una transacción de base de datos
            DB::beginTransaction();

            // Validar la solicitud
            $validated = $request->validate([
                'cliente_id' => 'nullable',
                'cliente_nombre' => 'nullable|string|max:255', // Permitir nulo o string
                'cliente_nif' => 'nullable|string|max:20', // Validación para NIF
                'cliente_email' => 'nullable|email|max:255', // Validación para email
                'cliente_telefono' => 'nullable|string|max:15',
                'asunto_id' => 'nullable',
                'asunto_nombre' => 'nullable|string|max:255', // Permitir nulo o string
                'tipo_id' => 'nullable',
                'tipo_nombre' => 'nullable|string|max:255',   // Permitir nulo o string
                'subtipo' => 'nullable|string',
                'estado' => 'nullable|string',
                'archivo' => 'nullable|string',
                'descripcion' => 'nullable|string',
                'observaciones' => 'nullable|string',
                'facturable' => 'nullable|boolean',
                'facturado' => 'nullable|string', // Añadir validación para facturado
                'precio' => 'nullable|numeric', // Añadir validación para precio
                'suplido' => 'nullable|numeric', // Añadir validación para suplido
                'coste' => 'nullable|numeric', // Añadir validación para coste
                'fecha_inicio' => 'nullable|date',
                'fecha_vencimiento' => 'nullable|date',
                'fecha_imputacion' => 'nullable|date',
                'tiempo_previsto' => 'nullable|numeric',
                'tiempo_real' => 'nullable|numeric',
                'planificacion' => 'nullable|date',
                'users' => 'nullable|array', // Validar que sea un array de usuarios
                'users.*' => 'exists:users,id' // Validar que cada usuario exista en la tabla 'users'
            ]);
            Log::debug('Datos validados:', $validated);


            // Verificar si se debe crear un nuevo cliente
            if (!$validated['cliente_id'] && !empty($validated['cliente_nombre'])) {
                // Buscar si el cliente ya existe antes de crear uno nuevo
                $clienteExistente = Cliente::where('nombre_fiscal', strtoupper($validated['cliente_nombre']))->first();

                if ($clienteExistente) {
                    // Si ya existe, asignar el ID del cliente existente
                    $validated['cliente_id'] = $clienteExistente->id;
                } else {
                    // Si no existe, crear un nuevo cliente con los datos adicionales
                    $cliente = Cliente::create([
                        'nombre_fiscal' => strtoupper($validated['cliente_nombre']),
                        'nif' => $validated['cliente_nif'] ?? null, // Añadir NIF si está presente
                        'email' => $validated['cliente_email'] ?? null, // Añadir email si está presente
                        'telefono' => $validated['cliente_telefono'] ?? null // Añadir teléfono si está presente
                    ]);
                    $validated['cliente_id'] = $cliente->id;
                }
            }


            // Verificar si se debe crear un nuevo asunto
            if (!$validated['asunto_id'] && !empty($validated['asunto_nombre'])) {
                // Buscar si el asunto ya existe antes de crear uno nuevo
                $asuntoExistente = Asunto::where('nombre', strtoupper($validated['asunto_nombre']))->first();

                if ($asuntoExistente) {
                    // Si ya existe, asignar el ID del asunto existente
                    $validated['asunto_id'] = $asuntoExistente->id;
                } else {
                    // Si no existe, crear un nuevo asunto
                    $asunto = Asunto::create(['nombre' => strtoupper($validated['asunto_nombre'])]);
                    $validated['asunto_id'] = $asunto->id;
                }
            }

            /// Verificar si se debe crear un nuevo tipo
            if (!$validated['tipo_id'] && !empty($validated['tipo_nombre'])) {
                // Buscar si el tipo ya existe antes de crear uno nuevo
                $tipoExistente = Tipo::where('nombre', strtoupper($validated['tipo_nombre']))->first();

                if ($tipoExistente) {
                    // Si ya existe, asignar el ID del tipo existente
                    $validated['tipo_id'] = $tipoExistente->id;
                } else {
                    // Si no existe, crear un nuevo tipo
                    $tipo = Tipo::create(['nombre' => strtoupper($validated['tipo_nombre'])]);
                    $validated['tipo_id'] = $tipo->id;
                }
            }


            Log::debug('Cliente Nombre: ' . $validated['cliente_nombre']);

            // Crear la tarea
            $task = Tarea::create([
                'cliente_id' => $validated['cliente_id'],
                'asunto_id' => $validated['asunto_id'], // Asunto existente o recién creado
                'tipo_id' => $validated['tipo_id'], // Tipo existente o recién creado
                'subtipo' => $validated['subtipo'] ?? null,
                'estado' => $validated['estado'] ?? 'PENDIENTE',
                'archivo' => $validated['archivo'] ?? null,
                'descripcion' => $validated['descripcion'] ?? null,
                'observaciones' => $validated['observaciones'] ?? null,
                'facturable' => $validated['facturable'] ?? false,
                'facturado' => $validated['facturado'] ?? 'No', // Crear el campo facturado
                'precio' => $validated['precio'] ?? null, // Crear el campo precio
                'suplido' => $validated['suplido'] ?? null, // Crear el campo suplido
                'coste' => $validated['coste'] ?? null, // Crear el campo coste
                'fecha_inicio' => isset($validated['fecha_inicio'])
                    ? Carbon::parse($validated['fecha_inicio'])->format('Y-m-d')
                    : null,
                'fecha_vencimiento' => isset($validated['fecha_vencimiento'])
                    ? Carbon::parse($validated['fecha_vencimiento'])->format('Y-m-d')
                    : null,
                'fecha_imputacion' => isset($validated['fecha_imputacion'])
                    ? Carbon::parse($validated['fecha_imputacion'])->format('Y-m-d')
                    : null,
                'fecha_planificacion' => isset($validated['planificacion'])
                    ? Carbon::parse($validated['planificacion'])->format('Y-m-d')
                    : null,
                'tiempo_previsto' => $validated['tiempo_previsto'] ?? null,
                'tiempo_real' => $validated['tiempo_real'] ?? null,
            ]);

            // 1. Asignar usuarios a la tarea en la base de datos
            $assignedUserIds = [];
            if (!empty($validated['users'])) {
                $task->users()->sync($validated['users']); // Asociar los usuarios a la tarea
                $assignedUserIds = $validated['users'];    // Guardar los IDs para notificaciones
            }

            // 2. Enviar notificaciones a los usuarios asignados
            foreach ($assignedUserIds as $userId) {
                if ($userId != auth()->id()) { // Evitar notificar al usuario que asigna la tarea
                    $user = User::find($userId);
                    if ($user) {
                        $user->notify(new TaskAssignedNotification($task)); // Enviar notificación
                    }
                }
            }

            // Emitir el evento para otros usuarios
            broadcast(new TaskCreated($task));

            // Confirma la transacción
            DB::commit();

            // Si la tarea se crea correctamente, devolver success: true
            return response()->json([
                'success' => true,
                'task' => $task->load(['cliente', 'asunto', 'tipo', 'users']) // Cargar relaciones
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack(); // Deshacer la transacción en caso de error de validación
            Log::error('Errores de validación:', $e->errors());
            return response()->json([
                'success' => false,
                'errors' => $e->errors()  // Devuelve todos los errores de validación
            ], 400);
        } catch (\Exception $e) {
            DB::rollBack(); // Deshacer la transacción en caso de otro error
            Log::error($e); // Agregar esto para capturar el error detallado
            return response()->json(['success' => false, 'message' => 'Error al crear la tarea'], 500);
        }
    }

    public function filter(Request $request)
    {
        try {
            // Obtener los filtros enviados desde el frontend
            $filters = $request->all();

            // Crear una consulta base para filtrar las tareas
            $query = Tarea::with(['cliente', 'asunto', 'tipo', 'users']); // Asegurarse de cargar las relaciones

            // Filtrar por cliente
            if (!empty($filters['cliente'])) {
                $query->where('cliente_id', $filters['cliente']);
            }

            // Filtrar por asunto
            if (!empty($filters['asunto'])) {
                // Buscar el asunto por nombre
                $asunto = Asunto::where('nombre', 'like', '%' . $filters['asunto'] . '%')->first();
                if ($asunto) {
                    $query->where('asunto_id', $asunto->id);
                }
            }

            // Filtrar por tipo
            if (!empty($filters['tipo'])) {
                // Buscar el tipo por nombre
                $tipo = Tipo::where('nombre', 'like', '%' . $filters['tipo'] . '%')->first();
                if ($tipo) {
                    $query->where('tipo_id', $tipo->id);
                }
            }

            // Filtrar por subtipo
            if (!empty($filters['subtipo'])) {
                $query->where('subtipo', $filters['subtipo']);
            }


            // Filtrar por subtipo
            if (!empty($filters['facturado'])) {
                $query->where('facturado', $filters['facturado']);
            }

            // Filtrar por estado
            if (!empty($filters['estado'])) {
                $query->where('estado', $filters['estado']);
            }

            // Filtrar por usuario asignado
            if (!empty($filters['usuario'])) {
                // Convertir los IDs separados por comas en un array
                $userIds = explode(',', $filters['usuario']);

                // Filtrar las tareas que tienen al menos uno de estos usuarios asignados
                $query->whereHas('users', function ($q) use ($userIds) {
                    // Asegurarse de especificar que 'id' es de la tabla 'users'
                    $q->whereIn('users.id', $userIds);
                });
            }


            // Filtrar por archivo
            if (!empty($filters['archivo'])) {
                $query->where('archivo', 'like', '%' . $filters['archivo'] . '%');
            }

            // Filtrar por facturable
            if (isset($filters['facturable'])) {
                $query->where('facturable', $filters['facturable']);
            }

            // Filtrar por fechas
            if (!empty($filters['fecha_inicio'])) {
                $query->whereDate('fecha_inicio', '>=', $filters['fecha_inicio']);
            }

            if (!empty($filters['fecha_vencimiento'])) {
                $query->whereDate('fecha_vencimiento', '<=', $filters['fecha_vencimiento']);
            }

            // Filtrar por precio
            if (!empty($filters['precio'])) {
                $query->where('precio', '=', $filters['precio']);
            }

            // Filtrar por tiempo previsto y tiempo real
            if (!empty($filters['tiempo_previsto'])) {
                $query->where('tiempo_previsto', '=', $filters['tiempo_previsto']);
            }

            if (!empty($filters['tiempo_real'])) {
                $query->where('tiempo_real', '=', $filters['tiempo_real']);
            }

            // Filtrar por fecha de planificación
            if (!empty($filters['fecha_planificacion'])) {
                if ($filters['fecha_planificacion'] === 'past') {
                    // Filtrar por fechas anteriores a hoy y que no estén completadas
                    $query->whereDate('fecha_planificacion', '<', now()->toDateString())
                        ->whereIn('estado', ['PENDIENTE', 'ENESPERA']); // Asegúrate de que estos valores coincidan con tu ENUM de estado
                } else {
                    // Filtrar por una fecha específica
                    $query->whereDate('fecha_planificacion', $filters['fecha_planificacion']);
                }
            }

            // Añadir el orden por fecha de creación, de más reciente a más antigua
            $query->orderBy('created_at', 'desc');

            // Ejecutar la consulta y obtener las tareas filtradas
            $filteredTasks = $query->paginate(50);

            // Devolver las tareas filtradas como respuesta JSON
            return response()->json([
                'success' => true,
                'filteredTasks' => $filteredTasks->items(), // Tareas filtradas
                'pagination' => [
                    'current_page' => $filteredTasks->currentPage(),
                    'last_page' => $filteredTasks->lastPage(),
                    'next_page_url' => $filteredTasks->nextPageUrl(),
                    'prev_page_url' => $filteredTasks->previousPageUrl(),
                    'total' => $filteredTasks->total(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Método de exportación de tareas filtradas
    public function exportFilteredTasks(Request $request)
    {
        // Obtén los filtros aplicados desde la solicitud
        $filters = $request->all();

        // Aplica los filtros a la consulta de tareas
        $query = Tarea::select([
            'id',
            'asunto_id',
            'cliente_id',
            'tipo_id',
            'descripcion',
            'observaciones',
            'facturable',
            'facturado',
            'subtipo',
            'estado',
            'fecha_inicio',
            'fecha_vencimiento',
            'fecha_imputacion',
            'tiempo_previsto',
            'tiempo_real',
            'fecha_planificacion',
            'created_at'
        ])->with(['cliente', 'asunto', 'tipo', 'users']);
        // Filtrar por cliente
        if (!empty($filters['cliente'])) {
            $query->where('cliente_id', $filters['cliente']);
        }

        // Filtrar por asunto
        if (!empty($filters['asunto'])) {
            // Buscar el asunto por nombre
            $asunto = Asunto::where('nombre', 'like', '%' . $filters['asunto'] . '%')->first();
            if ($asunto) {
                $query->where('asunto_id', $asunto->id);
            }
        }

        // Filtrar por tipo
        if (!empty($filters['tipo'])) {
            // Buscar el tipo por nombre
            $tipo = Tipo::where('nombre', 'like', '%' . $filters['tipo'] . '%')->first();
            if ($tipo) {
                $query->where('tipo_id', $tipo->id);
            }
        }

        // Filtrar por subtipo
        if (!empty($filters['subtipo'])) {
            $query->where('subtipo', $filters['subtipo']);
        }


        // Filtrar por subtipo
        if (!empty($filters['facturado'])) {
            $query->where('facturado', $filters['facturado']);
        }

        // Filtrar por estado
        if (!empty($filters['estado'])) {
            $query->where('estado', $filters['estado']);
        }

        // Filtrar por usuario asignado
        if (!empty($filters['usuario'])) {
            // Convertir los IDs separados por comas en un array
            $userIds = explode(',', $filters['usuario']);

            // Filtrar las tareas que tienen al menos uno de estos usuarios asignados
            $query->whereHas('users', function ($q) use ($userIds) {
                // Asegurarse de especificar que 'id' es de la tabla 'users'
                $q->whereIn('users.id', $userIds);
            });
        }


        // Filtrar por archivo
        if (!empty($filters['archivo'])) {
            $query->where('archivo', 'like', '%' . $filters['archivo'] . '%');
        }

        // Filtrar por facturable
        if (isset($filters['facturable'])) {
            $query->where('facturable', $filters['facturable']);
        }

        // Filtrar por fechas
        if (!empty($filters['fecha_inicio'])) {
            $query->whereDate('fecha_inicio', '>=', $filters['fecha_inicio']);
        }

        if (!empty($filters['fecha_vencimiento'])) {
            $query->whereDate('fecha_vencimiento', '<=', $filters['fecha_vencimiento']);
        }

        // Filtrar por precio
        if (!empty($filters['precio'])) {
            $query->where('precio', '=', $filters['precio']);
        }

        // Filtrar por tiempo previsto y tiempo real
        if (!empty($filters['tiempo_previsto'])) {
            $query->where('tiempo_previsto', '=', $filters['tiempo_previsto']);
        }

        if (!empty($filters['tiempo_real'])) {
            $query->where('tiempo_real', '=', $filters['tiempo_real']);
        }

        // Filtrar por fecha de planificación
        if (!empty($filters['fecha_planificacion'])) {
            if ($filters['fecha_planificacion'] === 'past') {
                // Filtrar por fechas anteriores a hoy
                $query->whereDate('fecha_planificacion', '<', now()->toDateString());
            } else {
                // Filtrar por una fecha específica
                $query->whereDate('fecha_planificacion', $filters['fecha_planificacion']);
            }
        }

        // Añadir el orden por fecha de creación, de más reciente a más antigua
        $query->orderBy('created_at', 'desc');



        $filteredTasks = $query->get();
        $fileName = $filters['fileName'] ?? 'tareas_filtradas.xlsx';

        // Exporta las tareas filtradas

        return Excel::download(new TasksExport($filteredTasks), $fileName);
    }


    public function destroy($id)
    {
        try {
            // Buscar la tarea por su ID
            $task = Tarea::findOrFail($id);

            // Emitir el evento para que otros usuarios sepan que esta tarea ha sido eliminada
            broadcast(new TaskDeleted($task->id));  // Solo enviamos la ID de la tarea


            // Eliminar relaciones en la tabla pivot 'tarea_user'
            $task->users()->detach();  // Eliminar todas las relaciones con usuarios

            // Eliminar la tarea
            $task->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tarea eliminada correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la tarea: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            // Buscar la tarea por su ID con relaciones (users, cliente, etc.)
            $task = Tarea::with(['users', 'cliente', 'asunto', 'tipo'])->findOrFail($id);

            // Devolver la tarea en formato JSON para ser usada en el formulario
            return response()->json($task);
        } catch (\Exception $e) {
            // Manejar cualquier error que ocurra durante la búsqueda de la tarea
            return response()->json(['error' => 'Error al cargar la tarea: ' . $e->getMessage()], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {


            // Iniciar una transacción para asegurar la integridad de los datos
            DB::beginTransaction();

            // Validar los datos de la solicitud
            $validated = $request->validate([
                'subtipoEdit' => 'nullable|string', // Validar subtipo
                'estadoEdit' => 'nullable|string',  // Validar estado
                'archivoEdit' => 'nullable|string',  // Validar archivo
                'descripcionEdit' => 'nullable|string',  // Validar descripción
                'observacionesEdit' => 'nullable|string',  // Validar observaciones
                'facturableEdit' => 'nullable|boolean',  // Validar facturable (checkbox)
                'facturadoEdit' => 'nullable|string',  // Validar facturado
                'precioEdit' => 'nullable|numeric',  // Validar precio
                'suplidoEdit' => 'nullable|numeric',  // Validar suplido
                'costeEdit' => 'nullable|numeric',  // Validar coste
                'fecha_planificacionEdit' => 'nullable|date',  // Validar fecha de planificación
                'fecha_inicioEdit' => 'nullable|date',  // Validar fecha de inicio
                'fecha_vencimientoEdit' => 'nullable|date',  // Validar fecha de vencimiento
                'fecha_imputacionEdit' => 'nullable|date',  // Validar fecha de imputación
                'tiempo_previstoEdit' => 'nullable|numeric',  // Validar tiempo previsto
                'tiempo_realEdit' => 'nullable|numeric',  // Validar tiempo real
                'usersEdit' => 'nullable|array',  // Validar usuarios asignados
                'usersEdit.*' => 'exists:users,id',  // Cada usuario debe existir en la tabla de usuarios
                'duplicar' => 'nullable|boolean', // Validar el checkbox de duplicación
            ]);

            // Buscar la tarea por ID
            $task = Tarea::findOrFail($id);

            // Actualizar la tarea con los datos validados
            $task->update([
                'subtipo' => $validated['subtipoEdit'],  // No usar coalescencia nula
                'estado' => $validated['estadoEdit'],
                'archivo' => $validated['archivoEdit'],
                'descripcion' => $validated['descripcionEdit'],  // Permitir que se guarde como vacío
                'observaciones' => $validated['observacionesEdit'],  // Permitir vacío
                'facturable' => $validated['facturableEdit'] ?? false,  // Checkbox
                'facturado' => $validated['facturadoEdit'],
                'precio' => $validated['precioEdit'],
                'suplido' => $validated['suplidoEdit'],
                'coste' => $validated['costeEdit'],
                'fecha_planificacion' => $validated['fecha_planificacionEdit'],
                'fecha_inicio' => $validated['fecha_inicioEdit'],
                'fecha_vencimiento' => $validated['fecha_vencimientoEdit'],
                'fecha_imputacion' => $validated['fecha_imputacionEdit'],
                'tiempo_previsto' => $validated['tiempo_previstoEdit'],
                'tiempo_real' => $validated['tiempo_realEdit'],
            ]);


            // Asociar los usuarios a la tarea (si se han seleccionado)
            if (!empty($validated['usersEdit'])) {
                $task->users()->sync($validated['usersEdit']);

                foreach ($validated['usersEdit'] as $userId) {
                    if ($userId != auth()->id()) { // Evitar notificar al usuario que está actualizando
                        $user = User::find($userId);
                        if ($user) {
                            $user->notify(new TaskAssignedNotification($task)); // Enviar notificación
                        }
                    }
                }
            }



            Log::debug('Emitiendo evento TaskUpdated para la tarea con ID: ' . $task->id);


            // Emitir el evento para que otros usuarios sean notificados de la actualización
            broadcast(new TaskUpdated($task));

            // Si el checkbox de duplicación está marcado, crear una nueva tarea con los mismos datos
            if ($validated['duplicar'] ?? false) {
                $duplicatedTask = $task->replicate(); // Clonar la tarea sin el ID ni timestamps

                // Cambiar el estado a "PENDIENTE" para la tarea duplicada
                $duplicatedTask->estado = 'PENDIENTE';

                // Guardar la nueva tarea duplicada
                $duplicatedTask->save();

                // Duplicar la asociación de usuarios
                if (!empty($validated['usersEdit'])) {
                    $duplicatedTask->users()->sync($validated['usersEdit']);
                }

                broadcast(new TaskCreated($duplicatedTask));
            }

            // Confirmar la transacción
            DB::commit();

            // Devolver la tarea actualizada
            return response()->json([
                'success' => true,
                'task' => $task->load(['cliente', 'asunto', 'tipo', 'users']),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack(); // Deshacer la transacción en caso de error de validación
            return response()->json(['success' => false, 'errors' => $e->errors()], 400);
        } catch (\Exception $e) {
            DB::rollBack(); // Deshacer la transacción en caso de error general
            return response()->json(['success' => false, 'message' => 'Error al actualizar la tarea: ' . $e->getMessage()], 500);
        }
    }
}
