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

            // Validar la solicitud
            $validated = $request->validate([
                'cliente_id' => 'required|exists:clientes,id',
                'asunto_id' => 'required|exists:asuntos,id',
                'tipo_id' => 'nullable|exists:tipos,id',
                'subtipo' => 'nullable|string',
                'estado' => 'nullable|string',
                'descripcion' => 'nullable|string',
                'observaciones' => 'nullable|string',
                'facturable' => 'nullable|boolean',
                'fecha_inicio' => 'nullable|date',
                'fecha_vencimiento' => 'nullable|date',
                'fecha_imputacion' => 'nullable|date', 
                'tiempo_previsto' => 'nullable|numeric', 
                'tiempo_real' => 'nullable|numeric', 
            ]);

            // Crear la tarea
            $task = Tarea::create([
                'cliente_id' => $validated['cliente_id'],
                'asunto_id' => $validated['asunto_id'],
                'tipo_id' => $validated['tipo_id'] ?? null,
                'subtipo' => $validated['subtipo'] ?? null,
                'estado' => $validated['estado'] ?? 'PENDIENTE',
                'descripcion' => $validated['descripcion'] ?? null,
                'observaciones' => $validated['observaciones'] ?? null,
                'facturable' => $validated['facturable'] ?? false,
                'fecha_inicio' => isset($validated['fecha_inicio'])
                    ? Carbon::parse($validated['fecha_inicio'])->format('Y-m-d')
                    : null,
                'fecha_vencimiento' => isset($validated['fecha_vencimiento'])
                    ? Carbon::parse($validated['fecha_vencimiento'])->format('Y-m-d')
                    : null,
                'fecha_imputacion' => isset($validated['fecha_imputacion'])
                    ? Carbon::parse($validated['fecha_imputacion'])->format('Y-m-d')
                    : null, // Formato correcto para fecha_imputacion
                'tiempo_previsto' => $validated['tiempo_previsto'] ?? null, // Guardar tiempo previsto
                'tiempo_real' => $validated['tiempo_real'] ?? null, // Guardar tiempo real
            ]);


            // Emite el evento
            broadcast(new TaskCreated($task))->toOthers();

            // Si la tarea se crea correctamente, devuelves success: true
            return response()->json([
                'success' => true,
                'task' => $task->load(['cliente', 'asunto', 'tipo', 'users'])
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si hay un error de validación, devuelves success: false con los errores
            return response()->json([
                'success' => false,
                'errors' => $e->errors()  // Devuelve todos los errores de validación
            ], 400);
        } catch (\Exception $e) {
            // Si hay cualquier otro error, devuelves un mensaje genérico
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
