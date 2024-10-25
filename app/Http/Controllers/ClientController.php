<?php

namespace App\Http\Controllers;

use App\Events\CustomerCreated;
use App\Models\Cliente;
use App\Models\TipoCliente;
use App\Models\Clasificacion;
use App\Models\Tributacion;
use App\Models\Situacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ClientController extends Controller
{
    /**
     * Muestra la vista principal de Clientes.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtener todos los clientes de la base de datos, ordenados por las más recientes
        $clientes = Cliente::with(['tipoCliente', 'clasificacion', 'tributacion', 'situacion', 'users'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Obtener datos adicionales necesarios para el formulario
        $tiposClientes = TipoCliente::all();  // Cambié el nombre de la variable aquí
        $clasificaciones = Clasificacion::all();
        $tributaciones = Tributacion::all();
        $situaciones = Situacion::all();
        $usuarios = User::all();

        // Pasar los clientes y los datos adicionales a la vista
        return view('customers.index', compact('clientes', 'tiposClientes', 'clasificaciones', 'tributaciones', 'situaciones', 'usuarios'));
    }

    public function getCustomers(Request $request)
    {
        // Obtener todas las tareas con las relaciones necesarias, ordenadas por la más reciente
        $clientes = Cliente::with(['tipoCliente', 'clasificacion', 'tributacion', 'situacion', 'users'])
            ->orderBy('created_at', 'desc')
            ->paginate(50); // Ajustar el número de tareas por página

        // Devolver las tareas en formato JSON, junto con enlaces de paginación
        return response()->json([
            'success' => true,
            'customers' => $clientes->items(), // Las tareas actuales
            'pagination' => [
                'total' => $clientes->total(),
                'current_page' => $clientes->currentPage(),
                'last_page' => $clientes->lastPage(),
                'per_page' => $clientes->perPage(),
                'next_page_url' => $clientes->nextPageUrl(),
                'prev_page_url' => $clientes->previousPageUrl()
            ]
        ]);
    }



    public function store(Request $request)
    {
        try {
            // Inicia una transacción de base de datos
            DB::beginTransaction();

            // Validar la solicitud
            $validated = $request->validate([
                'nombre_fiscal' => 'required|string|max:255',
                'nif' => 'nullable|string|max:255',
                'movil' => 'nullable|string|max:255',
                'fijo' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'direccion' => 'nullable|string|max:255',
                'codigo_postal' => 'nullable|string|max:255',
                'poblacion' => 'nullable|string|max:255',
                'datos_bancarios' => 'nullable|string',
                'tipo_cliente_id' => 'nullable|exists:tipo_clientes,id',
                'tipo_cliente_nombre' => 'nullable|string|max:255',
                'clasificacion_id' => 'nullable|exists:clasificaciones,id',
                'clasificacion_nombre' => 'nullable|string|max:255',
                'tributacion_id' => 'nullable|exists:tributaciones,id',
                'tributacion_nombre' => 'nullable|string|max:255',
                'situacion_id' => 'nullable|exists:situaciones,id',
                'situacion_nombre' => 'nullable|string|max:255',
                'subclase' => 'nullable|string|max:255',
                'puntaje' => 'nullable|integer',
                'codigo_sage' => 'nullable|integer',
                'users' => 'nullable|array', // Validar que sea un array de usuarios
                'users.*' => 'exists:users,id' // Validar que cada usuario exista en la tabla 'users'
            ]);

            // Verificar y crear nuevo Tipo de Cliente si es necesario
            if (!$validated['tipo_cliente_id'] && !empty($validated['tipo_cliente_nombre'])) {
                $tipoClienteExistente = TipoCliente::where('nombre', strtoupper($validated['tipo_cliente_nombre']))->first();

                if ($tipoClienteExistente) {
                    $validated['tipo_cliente_id'] = $tipoClienteExistente->id;
                } else {
                    $tipoCliente = TipoCliente::create(['nombre' => strtoupper($validated['tipo_cliente_nombre'])]);
                    $validated['tipo_cliente_id'] = $tipoCliente->id;
                }
            }

            // Verificar y crear nueva Clasificación si es necesario
            if (!$validated['clasificacion_id'] && !empty($validated['clasificacion_nombre'])) {
                $clasificacionExistente = Clasificacion::where('nombre', strtoupper($validated['clasificacion_nombre']))->first();

                if ($clasificacionExistente) {
                    $validated['clasificacion_id'] = $clasificacionExistente->id;
                } else {
                    $clasificacion = Clasificacion::create(['nombre' => strtoupper($validated['clasificacion_nombre'])]);
                    $validated['clasificacion_id'] = $clasificacion->id;
                }
            }

            // Verificar y crear nueva Tributación si es necesario
            if (!$validated['tributacion_id'] && !empty($validated['tributacion_nombre'])) {
                $tributacionExistente = Tributacion::where('nombre', strtoupper($validated['tributacion_nombre']))->first();

                if ($tributacionExistente) {
                    $validated['tributacion_id'] = $tributacionExistente->id;
                } else {
                    $tributacion = Tributacion::create(['nombre' => strtoupper($validated['tributacion_nombre'])]);
                    $validated['tributacion_id'] = $tributacion->id;
                }
            }

            // Verificar y crear nueva Situación si es necesario
            if (!$validated['situacion_id'] && !empty($validated['situacion_nombre'])) {
                $situacionExistente = Situacion::where('nombre', strtoupper($validated['situacion_nombre']))->first();

                if ($situacionExistente) {
                    $validated['situacion_id'] = $situacionExistente->id;
                } else {
                    $situacion = Situacion::create(['nombre' => strtoupper($validated['situacion_nombre'])]);
                    $validated['situacion_id'] = $situacion->id;
                }
            }

            // Crear el cliente
            $cliente = Cliente::create([
                'nombre_fiscal' => $validated['nombre_fiscal'],
                'nif' => $validated['nif'] ?? null,
                'movil' => $validated['movil'] ?? null,
                'fijo' => $validated['fijo'] ?? null,
                'email' => $validated['email'] ?? null,
                'direccion' => $validated['direccion'] ?? null,
                'codigo_postal' => $validated['codigo_postal'] ?? null,
                'poblacion' => $validated['poblacion'] ?? null,
                'datos_bancarios' => $validated['datos_bancarios'] ?? null,
                'tipo_cliente_id' => $validated['tipo_cliente_id'] ?? null,
                'clasificacion_id' => $validated['clasificacion_id'] ?? null,
                'tributacion_id' => $validated['tributacion_id'] ?? null,
                'situacion_id' => $validated['situacion_id'] ?? null,
                'subclase' => $validated['subclase'] ?? null,
                'puntaje' => $validated['puntaje'] ?? null,
                'codigo_sage' => $validated['codigo_sage'] ?? null
            ]);

            // Asociar los usuarios al cliente (si se han seleccionado)
            if (!empty($validated['users'])) {
                $cliente->users()->sync($validated['users']); // Asocia los usuarios al cliente
            }

            // Emitir evento para notificar a otros usuarios
            broadcast(new CustomerCreated($cliente));

            // Confirmar la transacción
            DB::commit();

            // Devolver respuesta exitosa con el cliente creado
            return response()->json([
                'success' => true,
                'customer' => $cliente->load(['clasificacion', 'tipoCliente', 'tributacion', 'situacion'])
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
            Log::error($e); // Capturar el error detallado
            return response()->json(['success' => false, 'message' => 'Error al crear el cliente'], 500);
        }
    }
}
