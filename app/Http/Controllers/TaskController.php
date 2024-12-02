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
            ->orderBy('fecha_planificacion', 'asc') // Ordenar por la fecha de creación, de más reciente a más antigua
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
        try {
            Log::debug('Parámetros recibidos:', $request->all());

            $userId = $request->query('user_id'); // Usuario actual
            $filters = $request->all(); // Capturar todos los filtros enviados

            // Determinar los criterios de ordenación
            $sortKey = $request->query('sortKey', 'fecha_planificacion'); // Usar 'fecha_planificacion' por defecto
            $sortDirection = $request->query('sortDirection', 'asc'); // Dirección predeterminada

            $query = Tarea::query()->select('tareas.*');

            // Ordenación en relaciones
            if ($sortKey === 'asunto.nombre') {
                $query->leftJoin('asuntos', 'tareas.asunto_id', '=', 'asuntos.id');
                $query->addSelect('asuntos.nombre as asunto_nombre'); // Alias para asunto
                $sortKey = 'asuntos.nombre';
            }
            if ($sortKey === 'cliente.nombre_fiscal') {
                $query->leftJoin('clientes', 'tareas.cliente_id', '=', 'clientes.id');
                $query->addSelect('clientes.nombre_fiscal as cliente_nombre_fiscal'); // Alias para cliente
                $sortKey = 'clientes.nombre_fiscal';
            }
            if ($sortKey === 'tipo.nombre') {
                $query->leftJoin('tipos', 'tareas.tipo_id', '=', 'tipos.id');
                $query->addSelect('tipos.nombre as tipo_nombre'); // Alias para tipo
                $sortKey = 'tipos.nombre';
            }




            // Filtros dinámicos para múltiples valores
            foreach (['subtipo', 'facturado', 'estado'] as $filter) {
                if (!empty($filters[$filter])) {
                    $values = explode(',', $filters[$filter]); // Dividir la cadena en un array
                    $query->whereIn($filter, $values); // Usar whereIn para múltiples valores
                }
            }

            // Filtros dinámicos para booleanos
            foreach (['facturable'] as $booleanField) {
                if (!empty($filters[$booleanField])) {
                    // Convertir el valor recibido en booleano estricto
                    $values = array_map(function ($value) {
                        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
                    }, explode(',', $filters[$booleanField]));

                    $query->whereIn($booleanField, $values);
                }
            }

            // Filtros dinámicos para valores únicos
            foreach (['precio', 'tiempo_previsto', 'tiempo_real'] as $filter) {
                if (!empty($filters[$filter])) {
                    $query->where($filter, $filters[$filter]);
                }
            }

            // Filtros con búsqueda en relaciones
            if (!empty($filters['asunto'])) {
                $query->whereHas('asunto', function ($q) use ($filters) {
                    $q->where('nombre', 'like', '%' . $filters['asunto'] . '%');
                });
            }

            if (!empty($filters['tipo'])) {
                $query->whereHas('tipo', function ($q) use ($filters) {
                    $q->where('nombre', 'like', '%' . $filters['tipo'] . '%');
                });
            }

            // Filtro por usuario asignado
            if (!empty($filters['usuario'])) {
                // Si se filtra explícitamente por usuario desde el frontend
                $userIds = explode(',', $filters['usuario']);
                $query->whereHas('users', function ($q) use ($userIds) {
                    $q->whereIn('users.id', $userIds);
                });
            } elseif ($userId) {
                // Si no hay un filtro explícito de usuario, aplicar el usuario logueado
                $query->whereHas('users', function ($q) use ($userId) {
                    $q->where('users.id', $userId);
                });
            }

            // Filtros de rangos de fechas
            foreach (['fecha_inicio' => '>=', 'fecha_vencimiento' => '<=', 'fecha_imputacion' => '='] as $field => $operator) {
                if (!empty($filters[$field])) {
                    $query->whereDate($field, $operator, $filters[$field]);
                }
            }


            // Filtros específicos de planificación
            if (!empty($filters['fecha_planificacion_inicio']) && !empty($filters['fecha_planificacion_fin'])) {
                // Filtrar por rango si ambas fechas están definidas
                $query->whereBetween('fecha_planificacion', [
                    $filters['fecha_planificacion_inicio'],
                    $filters['fecha_planificacion_fin']
                ]);
            } elseif (!empty($filters['fecha_planificacion_inicio'])) {
                // Filtrar desde una fecha de inicio
                $query->whereDate('fecha_planificacion', '>=', $filters['fecha_planificacion_inicio']);
            } elseif (!empty($filters['fecha_planificacion_fin'])) {
                // Filtrar hasta una fecha de fin
                $query->whereDate('fecha_planificacion', '<=', $filters['fecha_planificacion_fin']);
            } elseif (!empty($filters['fecha_planificacion'])) {
                // Filtrar por un día en específico
                if ($filters['fecha_planificacion'] === 'past') {
                    $query->whereDate('fecha_planificacion', '<', now()->toDateString())
                        ->whereIn('estado', ['PENDIENTE', 'ENESPERA']);
                } else {
                    $query->whereDate('fecha_planificacion', $filters['fecha_planificacion']);
                }
            }

            // Filtro por cliente
            if (!empty($filters['cliente'])) {
                $query->where('cliente_id', $filters['cliente']);
            }

            DB::enableQueryLog();




            // Incluir relaciones necesarias
            $query->with(['cliente', 'asunto', 'tipo', 'users']);

            // Evitar duplicados y ordenar
            $query->distinct()->orderBy('fecha_planificacion', 'asc');




            // Orden terciario: desempatador por id

            // Paginación
            $tasks = $query->paginate(50);

            Log::debug('Parámetros de ordenación:', ['sortKey' => $sortKey, 'sortDirection' => $sortDirection]);
            Log::debug('SQL generado:', DB::getQueryLog());
            return response()->json([
                'success' => true,
                'tasks' => $tasks->items(),
                'pagination' => [
                    'current_page' => $tasks->currentPage(),
                    'last_page' => $tasks->lastPage(),
                    'per_page' => $tasks->perPage(),
                    'total' => $tasks->total(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener tareas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor.',
            ], 500);
        }
    }







    public function billingIndex()
    {

        // Obtener todas las tareas de la base de datos, ordenadas por las más recientes
        $tasks = Tarea::with(['cliente', 'asunto', 'tipo', 'users'])
            ->orderBy('fecha_planificacion', 'asc') // Ordenar por la fecha de creación, de más reciente a más antigua
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
        try {
            $userId = $request->query('user_id'); // Usuario actual
            $sortKey = $request->query('sortKey', 'tareas.fecha_planificacion'); // Campo por defecto
            $sortDirection = $request->query('sortDirection', 'asc'); // Dirección por defecto
            $filters = $request->all(); // Capturar todos los filtros enviados

            // Crear la consulta base
            $query = Tarea::query()->select('tareas.*');

            // Ordenación en relaciones
            if ($sortKey === 'asunto.nombre') {
                $query->leftJoin('asuntos', 'tareas.asunto_id', '=', 'asuntos.id');
                $query->addSelect('asuntos.nombre as asunto_nombre');
                $sortKey = 'asuntos.nombre';
            } elseif ($sortKey === 'cliente.nombre_fiscal') {
                $query->leftJoin('clientes', 'tareas.cliente_id', '=', 'clientes.id');
                $query->addSelect('clientes.nombre_fiscal as cliente_nombre_fiscal');
                $sortKey = 'clientes.nombre_fiscal';
            } elseif ($sortKey === 'tipo.nombre') {
                $query->leftJoin('tipos', 'tareas.tipo_id', '=', 'tipos.id');
                $query->addSelect('tipos.nombre as tipo_nombre');
                $sortKey = 'tipos.nombre';
            }

            // Filtros dinámicos para múltiples valores
            foreach (['subtipo', 'estado'] as $filter) {
                if (!empty($filters[$filter])) {
                    $values = explode(',', $filters[$filter]); // Dividir la cadena en un array
                    $query->whereIn($filter, $values); // Usar whereIn para múltiples valores
                }
            }

            // Filtros dinámicos para valores únicos
            foreach (['precio', 'tiempo_previsto', 'tiempo_real'] as $filter) {
                if (!empty($filters[$filter])) {
                    $query->where($filter, $filters[$filter]);
                }
            }


            // Filtros con búsqueda en relaciones
            if (!empty($filters['asunto'])) {
                $query->whereHas('asunto', function ($q) use ($filters) {
                    $q->where('nombre', 'like', '%' . $filters['asunto'] . '%');
                });
            }

            if (!empty($filters['tipo'])) {
                $query->whereHas('tipo', function ($q) use ($filters) {
                    $q->where('nombre', 'like', '%' . $filters['tipo'] . '%');
                });
            }

            // Filtro por usuario asignado
            if (!empty($filters['usuario'])) {
                // Si se filtra explícitamente por usuario desde el frontend
                $userIds = explode(',', $filters['usuario']);
                $query->whereHas('users', function ($q) use ($userIds) {
                    $q->whereIn('users.id', $userIds);
                });
            } elseif ($userId) {
                // Si no hay un filtro explícito de usuario, aplicar el usuario logueado
                $query->whereHas('users', function ($q) use ($userId) {
                    $q->where('users.id', $userId);
                });
            }

            // Filtros de rangos de fechas
            foreach (['fecha_inicio' => '>=', 'fecha_vencimiento' => '<='] as $field => $operator) {
                if (!empty($filters[$field])) {
                    $query->whereDate($field, $operator, $filters[$field]);
                }
            }

            // Filtros específicos de planificación
            if (!empty($filters['fecha_planificacion_inicio']) && !empty($filters['fecha_planificacion_fin'])) {
                // Filtrar por rango si ambas fechas están definidas
                $query->whereBetween('fecha_planificacion', [
                    $filters['fecha_planificacion_inicio'],
                    $filters['fecha_planificacion_fin']
                ]);
            } elseif (!empty($filters['fecha_planificacion_inicio'])) {
                // Filtrar desde una fecha de inicio
                $query->whereDate('fecha_planificacion', '>=', $filters['fecha_planificacion_inicio']);
            } elseif (!empty($filters['fecha_planificacion_fin'])) {
                // Filtrar hasta una fecha de fin
                $query->whereDate('fecha_planificacion', '<=', $filters['fecha_planificacion_fin']);
            } elseif (!empty($filters['fecha_planificacion'])) {
                // Filtrar por un día en específico
                if ($filters['fecha_planificacion'] === 'past') {
                    $query->whereDate('fecha_planificacion', '<', now()->toDateString())
                        ->whereIn('estado', ['PENDIENTE', 'ENESPERA']);
                } else {
                    $query->whereDate('fecha_planificacion', $filters['fecha_planificacion']);
                }
            }


            // Filtro por cliente
            if (!empty($filters['cliente'])) {
                $query->where('cliente_id', $filters['cliente']);
            }


            // Filtro por usuario asignado
            if (!empty($filters['usuario'])) {
                // Si se filtra explícitamente por usuario desde el frontend
                $userIds = explode(',', $filters['usuario']);
                $query->whereHas('users', function ($q) use ($userIds) {
                    $q->whereIn('users.id', $userIds);
                });
            } elseif ($userId) {
                // Si no hay un filtro explícito de usuario, aplicar el usuario logueado
                $query->whereHas('users', function ($q) use ($userId) {
                    $q->where('users.id', $userId);
                });
            }

            // Filtro por facturable
            if (!empty($filters['facturable'])) {
                // Si se filtra explícitamente por facturable desde el frontend
                $facturableValues = explode(',', $filters['facturable']);
                $query->whereIn('facturable', $facturableValues);
            } else {
                // Si no hay un filtro explícito, aplicar el valor predeterminado (facturable = true)
                $query->where('facturable', true);
            }

            // Filtro por facturado
            if (!empty($filters['facturado'])) {
                // Si se filtra explícitamente por facturado desde el frontend
                $facturadoValues = explode(',', $filters['facturado']);
                $query->whereIn('facturado', $facturadoValues);
            } else {
                // Si no hay un filtro explícito, aplicar el valor predeterminado (facturado = 'NO')
                $query->where('facturado', 'NO');
            }





            // Incluir relaciones necesarias
            $query->with(['cliente', 'asunto', 'tipo', 'users']);

            $query->distinct()->orderBy('fecha_planificacion', 'asc');

            // Paginación
            $tasks = $query->paginate(50);
            Log::info('Tareas obtenidas:', $query->get()->toArray());

            // Respuesta JSON
            return response()->json([
                'success' => true,
                'tasks' => $tasks->items(),
                'pagination' => [
                    'current_page' => $tasks->currentPage(),
                    'last_page' => $tasks->lastPage(),
                    'per_page' => $tasks->perPage(),
                    'total' => $tasks->total(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener tareas para facturación: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor.',
            ], 500);
        }
    }




    public function timesIndex()
    {

        // Obtener todas las tareas de la base de datos, ordenadas por las más recientes
        $tasks = Tarea::with(['cliente', 'asunto', 'tipo', 'users'])
            ->orderBy('fecha_planificacion', 'asc') // Ordenar por la fecha de creación, de más reciente a más antigua
            ->get();

        // Obtener datos adicionales necesarios para el formulario
        $clientes = Cliente::all();
        $asuntos = Asunto::all();
        $tipos = Tipo::all();
        $usuarios = User::all();

        // Pasar las tareas y los datos adicionales a la vista
        return view('times.index', compact('tasks', 'clientes', 'asuntos', 'tipos', 'usuarios'));
    }

    public function getTimes(Request $request)
    {
        try {
            $userId = $request->query('user_id'); // Usuario actual
            $filters = $request->all(); // Capturar todos los filtros enviados

            // Determinar los criterios de ordenación
            $sortKey = $request->query('sortKey', 'fecha_planificacion'); // Usar 'fecha_planificacion' por defecto
            $sortDirection = $request->query('sortDirection', 'asc'); // Dirección predeterminada

            $query = Tarea::query()->select('tareas.*');

            // Ordenación en relaciones
            if ($sortKey === 'asunto.nombre') {
                $query->leftJoin('asuntos', 'tareas.asunto_id', '=', 'asuntos.id');
                $query->addSelect('asuntos.nombre as asunto_nombre'); // Alias para asunto
                $sortKey = 'asuntos.nombre';
            }
            if ($sortKey === 'cliente.nombre_fiscal') {
                $query->leftJoin('clientes', 'tareas.cliente_id', '=', 'clientes.id');
                $query->addSelect('clientes.nombre_fiscal as cliente_nombre_fiscal'); // Alias para cliente
                $sortKey = 'clientes.nombre_fiscal';
            }
            if ($sortKey === 'tipo.nombre') {
                $query->leftJoin('tipos', 'tareas.tipo_id', '=', 'tipos.id');
                $query->addSelect('tipos.nombre as tipo_nombre'); // Alias para tipo
                $sortKey = 'tipos.nombre';
            }




            // Filtros dinámicos para múltiples valores
            foreach (['subtipo', 'facturado', 'estado'] as $filter) {
                if (!empty($filters[$filter])) {
                    $values = explode(',', $filters[$filter]); // Dividir la cadena en un array
                    $query->whereIn($filter, $values); // Usar whereIn para múltiples valores
                }
            }

            // Filtros dinámicos para booleanos
            foreach (['facturable'] as $booleanField) {
                if (!empty($filters[$booleanField])) {
                    // Convertir el valor recibido en booleano estricto
                    $values = array_map(function ($value) {
                        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
                    }, explode(',', $filters[$booleanField]));

                    $query->whereIn($booleanField, $values);
                }
            }

            // Filtros dinámicos para valores únicos
            foreach (['precio', 'tiempo_previsto', 'tiempo_real'] as $filter) {
                if (!empty($filters[$filter])) {
                    $query->where($filter, $filters[$filter]);
                }
            }

            // Filtros con búsqueda en relaciones
            if (!empty($filters['asunto'])) {
                $query->whereHas('asunto', function ($q) use ($filters) {
                    $q->where('nombre', 'like', '%' . $filters['asunto'] . '%');
                });
            }

            if (!empty($filters['tipo'])) {
                $query->whereHas('tipo', function ($q) use ($filters) {
                    $q->where('nombre', 'like', '%' . $filters['tipo'] . '%');
                });
            }

            // Filtro por usuario asignado
            if (!empty($filters['usuario'])) {
                // Si se filtra explícitamente por usuario desde el frontend
                $userIds = explode(',', $filters['usuario']);
                $query->whereHas('users', function ($q) use ($userIds) {
                    $q->whereIn('users.id', $userIds);
                });
            } elseif ($userId) {
                // Si no hay un filtro explícito de usuario, aplicar el usuario logueado
                $query->whereHas('users', function ($q) use ($userId) {
                    $q->where('users.id', $userId);
                });
            }

            // Filtros de rangos de fechas
            foreach (['fecha_inicio' => '>=', 'fecha_vencimiento' => '<=', 'fecha_imputacion' => '='] as $field => $operator) {
                if (!empty($filters[$field])) {
                    $query->whereDate($field, $operator, $filters[$field]);
                }
            }


            // Filtros específicos de planificación
            if (!empty($filters['fecha_planificacion_inicio']) && !empty($filters['fecha_planificacion_fin'])) {
                // Filtrar por rango si ambas fechas están definidas
                $query->whereBetween('fecha_planificacion', [
                    $filters['fecha_planificacion_inicio'],
                    $filters['fecha_planificacion_fin']
                ]);
            } elseif (!empty($filters['fecha_planificacion_inicio'])) {
                // Filtrar desde una fecha de inicio
                $query->whereDate('fecha_planificacion', '>=', $filters['fecha_planificacion_inicio']);
            } elseif (!empty($filters['fecha_planificacion_fin'])) {
                // Filtrar hasta una fecha de fin
                $query->whereDate('fecha_planificacion', '<=', $filters['fecha_planificacion_fin']);
            } elseif (!empty($filters['fecha_planificacion'])) {
                // Filtrar por un día en específico
                if ($filters['fecha_planificacion'] === 'past') {
                    $query->whereDate('fecha_planificacion', '<', now()->toDateString())
                        ->whereIn('estado', ['PENDIENTE', 'ENESPERA']);
                } else {
                    $query->whereDate('fecha_planificacion', $filters['fecha_planificacion']);
                }
            }

            // Filtro por cliente
            if (!empty($filters['cliente'])) {
                $query->where('cliente_id', $filters['cliente']);
            }

            DB::enableQueryLog();

            // Evitar duplicados y ordenar
            $query->distinct()->orderBy('fecha_planificacion', 'asc');
            // Orden terciario: desempatador por id


            // Incluir relaciones necesarias
            $query->with(['cliente', 'asunto', 'tipo', 'users']);

            // Paginación
            $tasks = $query->paginate(50);
            Log::debug('Parámetros de ordenación:', ['sortKey' => $sortKey, 'sortDirection' => $sortDirection]);
            Log::debug('SQL generado:', DB::getQueryLog());
            return response()->json([
                'success' => true,
                'tasks' => $tasks->items(),
                'pagination' => [
                    'current_page' => $tasks->currentPage(),
                    'last_page' => $tasks->lastPage(),
                    'per_page' => $tasks->perPage(),
                    'total' => $tasks->total(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener tareas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor.',
            ], 500);
        }
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
                'cliente_nombre' => 'nullable|string|max:255',
                'cliente_nif' => 'nullable|string|max:20',
                'cliente_email' => 'nullable|email|max:255',
                'cliente_telefono' => 'nullable|string|max:15',
                'asunto_id' => 'nullable',
                'asunto_nombre' => 'nullable|string|max:255',
                'tipo_id' => 'nullable',
                'tipo_nombre' => 'nullable|string|max:255',
                'subtipo' => 'nullable|string',
                'estado' => 'nullable|string',
                'archivo' => 'nullable|string',
                'descripcion' => 'nullable|string',
                'observaciones' => 'nullable|string',
                'facturable' => 'nullable|boolean',
                'facturado' => 'nullable|string',
                'precio' => 'nullable|numeric',
                'suplido' => 'nullable|numeric',
                'coste' => 'nullable|numeric',
                'fecha_inicio' => 'nullable|date',
                'fecha_vencimiento' => 'nullable|date',
                'fecha_imputacion' => 'nullable|date',
                'tiempo_previsto' => 'nullable|numeric',
                'tiempo_real' => 'nullable|numeric',
                'planificacion' => 'nullable|date',
                'users' => 'nullable|array',
                'users.*' => 'exists:users,id'
            ]);

            Log::debug('Datos validados:', $validated);

            // Verificar si se debe crear un nuevo cliente
            if (!$validated['cliente_id'] && !empty($validated['cliente_nombre'])) {
                // Buscar bajo un bloqueo para evitar condiciones de carrera
                $clienteExistente = Cliente::where('nombre_fiscal', strtoupper($validated['cliente_nombre']))
                    ->lockForUpdate()
                    ->first();

                if ($clienteExistente) {
                    // Si ya existe, asignar el ID del cliente existente
                    $validated['cliente_id'] = $clienteExistente->id;
                } else {
                    // Crear un nuevo cliente
                    $cliente = Cliente::create([
                        'nombre_fiscal' => strtoupper($validated['cliente_nombre']),
                        'nif' => $validated['cliente_nif'] ?? null,
                        'email' => $validated['cliente_email'] ?? null,
                        'telefono' => $validated['cliente_telefono'] ?? null
                    ]);
                    $validated['cliente_id'] = $cliente->id;
                }
            }

            // Verificar si se debe crear un nuevo asunto
            if (!$validated['asunto_id'] && !empty($validated['asunto_nombre'])) {
                $asuntoExistente = Asunto::where('nombre', strtoupper($validated['asunto_nombre']))
                    ->lockForUpdate()
                    ->first();

                if ($asuntoExistente) {
                    $validated['asunto_id'] = $asuntoExistente->id;
                } else {
                    $asunto = Asunto::create(['nombre' => strtoupper($validated['asunto_nombre'])]);
                    $validated['asunto_id'] = $asunto->id;
                }
            }

            // Verificar si se debe crear un nuevo tipo
            if (!$validated['tipo_id'] && !empty($validated['tipo_nombre'])) {
                $tipoExistente = Tipo::where('nombre', strtoupper($validated['tipo_nombre']))
                    ->lockForUpdate()
                    ->first();

                if ($tipoExistente) {
                    $validated['tipo_id'] = $tipoExistente->id;
                } else {
                    $tipo = Tipo::create(['nombre' => strtoupper($validated['tipo_nombre'])]);
                    $validated['tipo_id'] = $tipo->id;
                }
            }

            // Crear la tarea
            $task = Tarea::create([
                'cliente_id' => $validated['cliente_id'],
                'asunto_id' => $validated['asunto_id'],
                'tipo_id' => $validated['tipo_id'],
                'subtipo' => $validated['subtipo'] ?? null,
                'estado' => $validated['estado'] ?? 'PENDIENTE',
                'archivo' => $validated['archivo'] ?? null,
                'descripcion' => $validated['descripcion'] ?? null,
                'observaciones' => $validated['observaciones'] ?? null,
                'facturable' => $validated['facturable'] ?? false,
                'facturado' => $validated['facturado'] ?? 'No',
                'precio' => $validated['precio'] ?? null,
                'suplido' => $validated['suplido'] ?? null,
                'coste' => $validated['coste'] ?? null,
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

            // Asignar usuarios a la tarea
            if (!empty($validated['users'])) {
                $task->users()->sync($validated['users']);
            }

            // Emitir el evento para otros usuarios
            broadcast(new TaskCreated($task));

            // Confirma la transacción
            DB::commit();

            return response()->json([
                'success' => true,
                'task' => $task->load(['cliente', 'asunto', 'tipo', 'users'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
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
                $subtipoValues = explode(',', $filters['subtipo']);
                $query->whereIn('subtipo', $subtipoValues);
            }


            // Filtrar por facturado
            if (!empty($filters['facturado'])) {
                $facturadoValues = explode(',', $filters['facturado']);
                $query->whereIn('facturado', $facturadoValues);
            }

            // Filtrar por estado
            if (!empty($filters['estado'])) {
                $estados = explode(',', $filters['estado']);
                $query->whereIn('estado', $estados);
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

            // Filtros dinámicos para booleanos
            foreach (['facturable'] as $booleanField) {
                if (!empty($filters[$booleanField])) {
                    // Convertir el valor recibido en booleano estricto
                    $values = array_map(function ($value) {
                        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
                    }, explode(',', $filters[$booleanField]));

                    $query->whereIn($booleanField, $values);
                }
            }



            // Filtrar por fechas
            if (!empty($filters['fecha_inicio'])) {
                $query->whereDate('fecha_inicio', '>=', $filters['fecha_inicio']);
            }

            if (!empty($filters['fecha_vencimiento'])) {
                $query->whereDate('fecha_vencimiento', '<=', $filters['fecha_vencimiento']);
            }

            // Filtrar por fecha_imputacion
            if (!empty($filters['fecha_imputacion'])) {
                $query->whereDate('fecha_imputacion', '=', $filters['fecha_imputacion']);
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

            // Filtros específicos de planificación
            if (!empty($filters['fecha_planificacion_inicio']) && !empty($filters['fecha_planificacion_fin'])) {
                // Filtrar por rango si ambas fechas están definidas
                $query->whereBetween('fecha_planificacion', [
                    $filters['fecha_planificacion_inicio'],
                    $filters['fecha_planificacion_fin']
                ]);
            } elseif (!empty($filters['fecha_planificacion_inicio'])) {
                // Filtrar desde una fecha de inicio
                $query->whereDate('fecha_planificacion', '>=', $filters['fecha_planificacion_inicio']);
            } elseif (!empty($filters['fecha_planificacion_fin'])) {
                // Filtrar hasta una fecha de fin
                $query->whereDate('fecha_planificacion', '<=', $filters['fecha_planificacion_fin']);
            } elseif (!empty($filters['fecha_planificacion'])) {
                // Filtrar por un día en específico
                if ($filters['fecha_planificacion'] === 'past') {
                    $query->whereDate('fecha_planificacion', '<', now()->toDateString())
                        ->whereIn('estado', ['PENDIENTE', 'ENESPERA']);
                } else {
                    $query->whereDate('fecha_planificacion', $filters['fecha_planificacion']);
                }
            }


            // Añadir el orden por fecha de planificacion, de más antigua a más reciente
            $query->distinct()->orderBy('fecha_planificacion', 'asc');

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
            'fecha_vencimiento',
            'fecha_planificacion',
            'cliente_id',
            'asunto_id',
            'descripcion',
            'observaciones',
            'facturable',
            'facturado',
            'estado',
            'tiempo_previsto',
            'tiempo_real',
            'tipo_id',
            'subtipo',
            'fecha_inicio',
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
            $subtipoValues = explode(',', $filters['subtipo']);
            $query->whereIn('subtipo', $subtipoValues);
        }


        // Filtrar por facturado
        if (!empty($filters['facturado'])) {
            $facturadoValues = explode(',', $filters['facturado']);
            $query->whereIn('facturado', $facturadoValues);
        }

        // Filtrar por estado
        if (!empty($filters['estado'])) {
            $estados = explode(',', $filters['estado']);
            $query->whereIn('estado', $estados);
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

        // Filtros dinámicos para booleanos
        foreach (['facturable'] as $booleanField) {
            if (!empty($filters[$booleanField])) {
                // Convertir el valor recibido en booleano estricto
                $values = array_map(function ($value) {
                    return filter_var($value, FILTER_VALIDATE_BOOLEAN);
                }, explode(',', $filters[$booleanField]));

                $query->whereIn($booleanField, $values);
            }
        }



        // Filtrar por fechas
        if (!empty($filters['fecha_inicio'])) {
            $query->whereDate('fecha_inicio', '>=', $filters['fecha_inicio']);
        }

        if (!empty($filters['fecha_vencimiento'])) {
            $query->whereDate('fecha_vencimiento', '<=', $filters['fecha_vencimiento']);
        }

        // Filtrar por fecha_imputacion
        if (!empty($filters['fecha_imputacion'])) {
            $query->whereDate('fecha_imputacion', '=', $filters['fecha_imputacion']);
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

        // Filtros específicos de planificación
        if (!empty($filters['fecha_planificacion_inicio']) && !empty($filters['fecha_planificacion_fin'])) {
            // Filtrar por rango si ambas fechas están definidas
            $query->whereBetween('fecha_planificacion', [
                $filters['fecha_planificacion_inicio'],
                $filters['fecha_planificacion_fin']
            ]);
        } elseif (!empty($filters['fecha_planificacion_inicio'])) {
            // Filtrar desde una fecha de inicio
            $query->whereDate('fecha_planificacion', '>=', $filters['fecha_planificacion_inicio']);
        } elseif (!empty($filters['fecha_planificacion_fin'])) {
            // Filtrar hasta una fecha de fin
            $query->whereDate('fecha_planificacion', '<=', $filters['fecha_planificacion_fin']);
        } elseif (!empty($filters['fecha_planificacion'])) {
            // Filtrar por un día en específico
            if ($filters['fecha_planificacion'] === 'past') {
                $query->whereDate('fecha_planificacion', '<', now()->toDateString())
                    ->whereIn('estado', ['PENDIENTE', 'ENESPERA']);
            } else {
                $query->whereDate('fecha_planificacion', $filters['fecha_planificacion']);
            }
        }

        // Añadir el orden por fecha de creación, de más reciente a más antigua
        $query->distinct()->orderBy('fecha_planificacion', 'asc');



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

            // Reglas de validación dinámica
            $rules = [];
            foreach (
                [
                    'subtipoEdit' => 'nullable|string',
                    'estadoEdit' => 'nullable|string',
                    'archivoEdit' => 'nullable|string',
                    'descripcionEdit' => 'nullable|string',
                    'observacionesEdit' => 'nullable|string',
                    'facturableEdit' => 'nullable|boolean',
                    'facturadoEdit' => 'nullable|string',
                    'facturado' => 'nullable|string',  // Facturado desde la celda
                    'precioEdit' => 'nullable|numeric',
                    'suplidoEdit' => 'nullable|numeric',
                    'costeEdit' => 'nullable|numeric',
                    'fecha_planificacionEdit' => 'nullable|date',
                    'fecha_inicioEdit' => 'nullable|date',
                    'fecha_vencimientoEdit' => 'nullable|date',
                    'fecha_imputacionEdit' => 'nullable|date',
                    'tiempo_previstoEdit' => 'nullable|numeric',
                    'tiempo_realEdit' => 'nullable|numeric',
                    'usersEdit' => 'nullable|array',
                    'usersEdit.*' => 'exists:users,id',
                    'duplicar' => 'nullable|boolean',
                ] as $field => $rule
            ) {
                if ($request->has($field)) { // Validar solo campos presentes
                    $rules[$field] = $rule;
                }
            }

            // Validar la solicitud
            $validated = $request->validate($rules);
            // Buscar la tarea por ID
            $task = Tarea::findOrFail($id);

            // Construir datos de actualización dinámicamente
            $updateData = [];
            foreach (
                [
                    'subtipo' => 'subtipoEdit',
                    'estado' => 'estadoEdit',
                    'archivo' => 'archivoEdit',
                    'descripcion' => 'descripcionEdit',
                    'observaciones' => 'observacionesEdit',
                    'facturable' => 'facturableEdit',
                    'precio' => 'precioEdit',
                    'suplido' => 'suplidoEdit',
                    'coste' => 'costeEdit',
                    'fecha_planificacion' => 'fecha_planificacionEdit',
                    'fecha_inicio' => 'fecha_inicioEdit',
                    'fecha_vencimiento' => 'fecha_vencimientoEdit',
                    'fecha_imputacion' => 'fecha_imputacionEdit',
                    'tiempo_previsto' => 'tiempo_previstoEdit',
                    'tiempo_real' => 'tiempo_realEdit',
                ] as $field => $requestKey
            ) {
                if ($request->has($requestKey)) { // Incluir solo campos presentes
                    $updateData[$field] = $request->input($requestKey);
                }
            }


            // Manejar el campo "facturado" (priorizar celda sobre formulario)
            if ($request->has('facturado')) {
                $updateData['facturado'] = $request->input('facturado');
            } elseif ($request->has('facturadoEdit')) {
                $updateData['facturado'] = $request->input('facturadoEdit');
            }


            // Actualizar la tarea con los datos procesados
            $task->update($updateData);


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
