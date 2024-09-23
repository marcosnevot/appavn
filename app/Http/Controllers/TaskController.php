<?php

namespace App\Http\Controllers;

use App\Events\TareaActualizada;
use App\Events\TaskCreated;
use App\Events\TaskUpdated;
use App\Models\Asunto;
use App\Models\Cliente;
use App\Models\Tarea;
use App\Models\Tipo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\View\Components\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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



    public function store(Request $request)
    {

        try {
            // Inicia una transacción de base de datos
            DB::beginTransaction();

            // Validar la solicitud
            $validated = $request->validate([
                'cliente_id' => 'required|exists:clientes,id',
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
                'users' => 'nullable|array', // Validar que sea un array de usuarios
                'users.*' => 'exists:users,id' // Validar que cada usuario exista en la tabla 'users'
            ]);
            Log::debug('Datos validados:', $validated);


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
            Log::debug('Tipo Nombre: ' . $validated['tipo_nombre']);

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
                'facturado' => $validated['facturado'] ?? null, // Crear el campo facturado
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
                'tiempo_previsto' => $validated['tiempo_previsto'] ?? null,
                'tiempo_real' => $validated['tiempo_real'] ?? null,
            ]);

            // Asociar los usuarios a la tarea (si se han seleccionado)
            if (!empty($validated['users'])) {
                $task->users()->sync($validated['users']); // Asocia los usuarios a la tarea
            }

            // Emitir el evento para otros usuarios
            broadcast(new TaskCreated($task))->toOthers();

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




    public function update(Request $request, $id)
    {
        $task = Tarea::findOrFail($id);
        $task->update($request->all());

        // Emitir el evento para los clientes conectados
        broadcast(new TaskUpdated($task))->toOthers();

        return response()->json(['message' => 'Tarea actualizada.']);
    }
}
