<?php

namespace App\Http\Controllers;

use App\Events\CustomerCreated;
use App\Events\CustomerDeleted;
use App\Events\CustomerUpdated;
use App\Exports\CustomersExport;
use App\Models\Cliente;
use App\Models\TipoCliente;
use App\Models\Clasificacion;
use App\Models\Tributacion;
use App\Models\Situacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

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
            ->orderBy('id', 'asc') // Orden secundario por ID
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
        try {
            $sortKey = $request->query('sortKey', 'clientes.created_at'); // Campo por defecto
            $sortDirection = $request->query('sortDirection', 'desc'); // Dirección por defecto
            $filters = $request->all(); // Capturar todos los filtros enviados

            // Crear la consulta base
            $query = Cliente::query()->select('clientes.*');

            // Ordenación en relaciones
            if ($sortKey === 'tipoCliente.nombre') {
                $query->leftJoin('tipo_clientes', 'clientes.tipo_cliente_id', '=', 'tipo_clientes.id');
                $query->addSelect('tipo_clientes.nombre as tipo_cliente_nombre');
                $sortKey = 'tipo_clientes.nombre';
            } elseif ($sortKey === 'clasificacion.nombre') {
                $query->leftJoin('clasificaciones', 'clientes.clasificacion_id', '=', 'clasificaciones.id');
                $query->addSelect('clasificaciones.nombre as clasificacion_nombre');
                $sortKey = 'clasificaciones.nombre';
            } elseif ($sortKey === 'tributacion.nombre') {
                $query->leftJoin('tributaciones', 'clientes.tributacion_id', '=', 'tributaciones.id');
                $query->addSelect('tributaciones.nombre as tributacion_nombre');
                $sortKey = 'tributaciones.nombre';
            } elseif ($sortKey === 'situacion.nombre') {
                $query->leftJoin('situaciones', 'clientes.situacion_id', '=', 'situaciones.id');
                $query->addSelect('situaciones.nombre as situacion_nombre');
                $sortKey = 'situaciones.nombre';
            }

            // Filtrar por nombre fiscal del cliente
            if (!empty($filters['nombre_fiscal'])) {
                $query->where('nombre_fiscal', 'like', '%' . $filters['nombre_fiscal'] . '%');
            }

            // Filtrar por NIF
            if (!empty($filters['nif'])) {
                $query->where('nif', 'like', '%' . $filters['nif'] . '%');
            }

            // Filtrar por tipo de cliente
            if (!empty($filters['tipo_cliente'])) {
                $tipoCliente = TipoCliente::where('nombre', 'like', '%' . $filters['tipo_cliente'] . '%')->first();
                if ($tipoCliente) {
                    $query->where('tipo_cliente_id', $tipoCliente->id);
                }
            }

            // Filtrar por clasificación
            if (!empty($filters['clasificacion'])) {
                $clasificacion = Clasificacion::where('nombre', 'like', '%' . $filters['clasificacion'] . '%')->first();
                if ($clasificacion) {
                    $query->where('clasificacion_id', $clasificacion->id);
                }
            }

            // Filtrar por tributación
            if (!empty($filters['tributacion'])) {
                $tributacion = Tributacion::where('nombre', 'like', '%' . $filters['tributacion'] . '%')->first();
                if ($tributacion) {
                    $query->where('tributacion_id', $tributacion->id);
                }
            }

            // Filtrar por situación
            if (!empty($filters['situacion'])) {
                $situacion = Situacion::where('nombre', 'like', '%' . $filters['situacion'] . '%')->first();
                if ($situacion) {
                    $query->where('situacion_id', $situacion->id);
                }
            }
            // Filtrar por móvil
            if (!empty($filters['movil'])) {
                $query->where('movil', 'like', '%' . $filters['movil'] . '%');
            }

            // Filtrar por fijo
            if (!empty($filters['fijo'])) {
                $query->where('fijo', 'like', '%' . $filters['fijo'] . '%');
            }

            // Filtrar por email
            if (!empty($filters['email'])) {
                $query->where('email', 'like', '%' . $filters['email'] . '%');
            }

            // Filtrar por dirección
            if (!empty($filters['direccion'])) {
                $query->where('direccion', 'like', '%' . $filters['direccion'] . '%');
            }

            // Filtrar por código postal
            if (!empty($filters['codigo_postal'])) {
                $query->where('codigo_postal', 'like', '%' . $filters['codigo_postal'] . '%');
            }

            // Filtrar por población
            if (!empty($filters['poblacion'])) {
                $query->where('poblacion', 'like', '%' . $filters['poblacion'] . '%');
            }

            // Filtrar por usuario responsable asignado
            if (!empty($filters['usuario'])) {
                $userIds = explode(',', $filters['usuario']);
                $query->whereHas('users', function ($q) use ($userIds) {
                    $q->whereIn('users.id', $userIds);
                });
            }

            // Filtrar por datos bancarios
            if (!empty($filters['datos_bancarios'])) {
                $query->where('datos_bancarios', 'like', '%' . $filters['datos_bancarios'] . '%');
            }

            // Filtrar por subclase
            if (!empty($filters['subclase'])) {
                $query->where('subclase', 'like', '%' . $filters['subclase'] . '%');
            }

            // Filtrar por puntaje
            if (!empty($filters['puntaje'])) {
                $query->where('puntaje', '=', $filters['puntaje']);
            }

            // Filtrar por código SAGE
            if (!empty($filters['codigo_sage'])) {
                $query->where('codigo_sage', 'like', '%' . $filters['codigo_sage'] . '%');
            }

            // Filtrar por segundo teléfono
            if (!empty($filters['segundo_telefono'])) {
                $query->where('segundo_telefono', 'like', '%' . $filters['segundo_telefono'] . '%');
            }

            // Filtrar por persona de contacto
            if (!empty($filters['persona_contacto'])) {
                $query->where('persona_contacto', 'like', '%' . $filters['persona_contacto'] . '%');
            }

            // Evitar duplicados y ordenar
            $query->distinct()->orderBy($sortKey, $sortDirection);

            // Incluir relaciones necesarias
            $query->with(['tipoCliente', 'clasificacion', 'tributacion', 'situacion', 'users']);

            // Paginación
            $clientes = $query->paginate(50);

            return response()->json([
                'success' => true,
                'customers' => $clientes->items(),
                'pagination' => [
                    'total' => $clientes->total(),
                    'current_page' => $clientes->currentPage(),
                    'last_page' => $clientes->lastPage(),
                    'per_page' => $clientes->perPage(),
                    'next_page_url' => $clientes->nextPageUrl(),
                    'prev_page_url' => $clientes->previousPageUrl()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener clientes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor.',
            ], 500);
        }
    }



    public function show($id)
    {
        try {
            // Encuentra el customer por su ID o lanza un error si no se encuentra
            $customer = Cliente::with(['tipoCliente', 'clasificacion', 'tributacion', 'situacion', 'users'])->findOrFail($id);

            // Obtén la lista de todos los usuarios
            $usuarios = User::all();

            // Renderizar la vista modal con los detalles del customer y la lista de usuarios
            $html = view('customers.partials.customer-detail-modal', compact('customer', 'usuarios'))->render();

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
                'nombre_fiscal' => 'required|string|max:255',
                'nif' => 'nullable|string|max:255',
                'movil' => 'nullable|string|max:255',
                'segundo_telefono' => 'nullable|string|max:255',
                'persona_contacto' => 'nullable|string|max:255',
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
                'segundo_telefono' => $validated['segundo_telefono'] ?? null,
                'persona_contacto' => $validated['persona_contacto'] ?? null,
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


    public function filter(Request $request)
    {
        try {
            // Obtener los filtros enviados desde el frontend
            $filters = $request->all();

            // Crear una consulta base para filtrar los clientes
            $query = Cliente::with(['clasificacion', 'tipoCliente', 'tributacion', 'situacion', 'users']); // Asegurarse de cargar las relaciones necesarias

            // Filtrar por nombre fiscal del cliente
            if (!empty($filters['nombre_fiscal'])) {
                $query->where('nombre_fiscal', 'like', '%' . $filters['nombre_fiscal'] . '%');
            }

            // Filtrar por NIF
            if (!empty($filters['nif'])) {
                $query->where('nif', 'like', '%' . $filters['nif'] . '%');
            }

            // Filtrar por tipo de cliente
            if (!empty($filters['tipo_cliente'])) {
                $tipoCliente = TipoCliente::where('nombre', 'like', '%' . $filters['tipo_cliente'] . '%')->first();
                if ($tipoCliente) {
                    $query->where('tipo_cliente_id', $tipoCliente->id);
                }
            }

            // Filtrar por clasificación
            if (!empty($filters['clasificacion'])) {
                $clasificacion = Clasificacion::where('nombre', 'like', '%' . $filters['clasificacion'] . '%')->first();
                if ($clasificacion) {
                    $query->where('clasificacion_id', $clasificacion->id);
                }
            }

            // Filtrar por tributación
            if (!empty($filters['tributacion'])) {
                $tributacion = Tributacion::where('nombre', 'like', '%' . $filters['tributacion'] . '%')->first();
                if ($tributacion) {
                    $query->where('tributacion_id', $tributacion->id);
                }
            }

            // Filtrar por situación
            if (!empty($filters['situacion'])) {
                $situacion = Situacion::where('nombre', 'like', '%' . $filters['situacion'] . '%')->first();
                if ($situacion) {
                    $query->where('situacion_id', $situacion->id);
                }
            }
            // Filtrar por móvil
            if (!empty($filters['movil'])) {
                $query->where('movil', 'like', '%' . $filters['movil'] . '%');
            }

            // Filtrar por fijo
            if (!empty($filters['fijo'])) {
                $query->where('fijo', 'like', '%' . $filters['fijo'] . '%');
            }

            // Filtrar por email
            if (!empty($filters['email'])) {
                $query->where('email', 'like', '%' . $filters['email'] . '%');
            }

            // Filtrar por dirección
            if (!empty($filters['direccion'])) {
                $query->where('direccion', 'like', '%' . $filters['direccion'] . '%');
            }

            // Filtrar por código postal
            if (!empty($filters['codigo_postal'])) {
                $query->where('codigo_postal', 'like', '%' . $filters['codigo_postal'] . '%');
            }

            // Filtrar por población
            if (!empty($filters['poblacion'])) {
                $query->where('poblacion', 'like', '%' . $filters['poblacion'] . '%');
            }

            // Filtrar por usuario responsable asignado
            if (!empty($filters['usuario'])) {
                $userIds = explode(',', $filters['usuario']);
                $query->whereHas('users', function ($q) use ($userIds) {
                    $q->whereIn('users.id', $userIds);
                });
            }

            // Filtrar por datos bancarios
            if (!empty($filters['datos_bancarios'])) {
                $query->where('datos_bancarios', 'like', '%' . $filters['datos_bancarios'] . '%');
            }

            // Filtrar por subclase
            if (!empty($filters['subclase'])) {
                $query->where('subclase', 'like', '%' . $filters['subclase'] . '%');
            }

            // Filtrar por puntaje
            if (!empty($filters['puntaje'])) {
                $query->where('puntaje', '=', $filters['puntaje']);
            }

            // Filtrar por código SAGE
            if (!empty($filters['codigo_sage'])) {
                $query->where('codigo_sage', 'like', '%' . $filters['codigo_sage'] . '%');
            }

            // Filtrar por segundo teléfono
            if (!empty($filters['segundo_telefono'])) {
                $query->where('segundo_telefono', 'like', '%' . $filters['segundo_telefono'] . '%');
            }

            // Filtrar por persona de contacto
            if (!empty($filters['persona_contacto'])) {
                $query->where('persona_contacto', 'like', '%' . $filters['persona_contacto'] . '%');
            }

            // Añadir el orden por fecha de creación, de más reciente a más antigua
            $query->orderBy('created_at', 'desc');

            // Ejecutar la consulta y obtener los clientes filtrados
            $filteredCustomers = $query->paginate(50);

            // Devolver los clientes filtrados como respuesta JSON
            return response()->json([
                'success' => true,
                'filteredCustomers' => $filteredCustomers->items(),
                'pagination' => [
                    'current_page' => $filteredCustomers->currentPage(),
                    'last_page' => $filteredCustomers->lastPage(),
                    'next_page_url' => $filteredCustomers->nextPageUrl(),
                    'prev_page_url' => $filteredCustomers->previousPageUrl(),
                    'total' => $filteredCustomers->total(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Buscar la customer por su ID
            $customer = Cliente::findOrFail($id);

            // Emitir el evento para que otros usuarios sepan que esta tarea ha sido eliminada
            broadcast(new CustomerDeleted($customer->id));  // Solo enviamos la ID de la tarea


            // Eliminar relaciones en la tabla pivot 'cliente_user'
            $customer->users()->detach();  // Eliminar todas las relaciones con usuarios

            // Eliminar la customer
            $customer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cliente eliminado correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el cliente: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            // Buscar el cliente por su ID con relaciones (users, tipoCliente, clasificacion, tributacion, situacion)
            $customer = Cliente::with(['tipoCliente', 'clasificacion', 'tributacion', 'situacion', 'users'])->findOrFail($id);

            // Obtener todos los datos necesarios para los select del formulario
            $tipoCliente = TipoCliente::all();
            $clasificaciones = Clasificacion::all();
            $tributaciones = Tributacion::all();
            $situaciones = Situacion::all();

            // Devolver los datos en formato JSON para el formulario de edición
            return response()->json([
                'customer' => $customer,
                'tipoCliente' => $tipoCliente,
                'clasificaciones' => $clasificaciones,
                'tributaciones' => $tributaciones,
                'situaciones' => $situaciones
            ]);
        } catch (\Exception $e) {
            // Manejar cualquier error que ocurra durante la búsqueda del cliente
            return response()->json(['error' => 'Error al cargar el cliente: ' . $e->getMessage()], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            // Iniciar una transacción para asegurar la integridad de los datos
            DB::beginTransaction();

            // Validar los datos de la solicitud
            $validated = $request->validate([
                'nombre_fiscalEdit' => 'required|string|max:255', // Validar nombre fiscal
                'nifEdit' => 'nullable|string|max:255',  // Validar NIF
                'movilEdit' => 'nullable|string|max:255',  // Validar móvil
                'segundo_telefonoEdit' => 'nullable|string|max:255',  // Validar móvil
                'persona_contactoEdit' => 'nullable|string|max:255',  // Validar móvil
                'fijoEdit' => 'nullable|string|max:255',  // Validar fijo
                'emailEdit' => 'nullable|email|max:255',  // Validar email
                'direccionEdit' => 'nullable|string|max:255',  // Validar dirección
                'codigo_postalEdit' => 'nullable|string|max:255',  // Validar código postal
                'poblacionEdit' => 'nullable|string|max:255',  // Validar población
                'datos_bancariosEdit' => 'nullable|string',  // Validar datos bancarios
                'tipo_clienteEdit' => 'nullable|exists:tipo_clientes,id',  // Validar tipo cliente
                'clasificacionEdit' => 'nullable|exists:clasificaciones,id',  // Validar clasificación
                'tributacionEdit' => 'nullable|exists:tributaciones,id',  // Validar tributación
                'situacionEdit' => 'nullable|exists:situaciones,id',  // Validar situación
                'subclaseEdit' => 'nullable|string|max:255',  // Validar subclase
                'puntajeEdit' => 'nullable|integer',  // Validar puntaje
                'codigo_sageEdit' => 'nullable|integer',  // Validar código sage
                'usersEdit' => 'nullable|array',  // Validar usuarios asignados
                'usersEdit.*' => 'exists:users,id',  // Cada usuario debe existir en la tabla de usuarios
            ]);

            // Buscar el cliente por ID
            $customer = Cliente::findOrFail($id);

            // Actualizar el cliente con los datos validados
            $customer->update([
                'nombre_fiscal' => $validated['nombre_fiscalEdit'],
                'nif' => $validated['nifEdit'],
                'movil' => $validated['movilEdit'],
                'segundo_telefono' => $validated['segundo_telefonoEdit'],
                'persona_contacto' => $validated['persona_contactoEdit'],
                'fijo' => $validated['fijoEdit'],
                'email' => $validated['emailEdit'],
                'direccion' => $validated['direccionEdit'],
                'codigo_postal' => $validated['codigo_postalEdit'],
                'poblacion' => $validated['poblacionEdit'],
                'datos_bancarios' => $validated['datos_bancariosEdit'],
                'tipo_cliente_id' => $validated['tipo_clienteEdit'],
                'clasificacion_id' => $validated['clasificacionEdit'],
                'tributacion_id' => $validated['tributacionEdit'],
                'situacion_id' => $validated['situacionEdit'],
                'subclase' => $validated['subclaseEdit'],
                'puntaje' => $validated['puntajeEdit'],
                'codigo_sage' => $validated['codigo_sageEdit'],
            ]);

            // Asociar los usuarios al cliente (si se han seleccionado)
            if (!empty($validated['usersEdit'])) {
                $customer->users()->sync($validated['usersEdit']); // Asocia los usuarios al cliente
            }

            Log::debug('Emitiendo evento CustomerUpdated para el cliente con ID: ' . $customer->id);

            // Emitir el evento para que otros usuarios sean notificados de la actualización
            broadcast(new CustomerUpdated($customer));

            // Confirmar la transacción
            DB::commit();

            // Devolver el cliente actualizado
            return response()->json([
                'success' => true,
                'customer' => $customer->load(['tipoCliente', 'clasificacion', 'tributacion', 'situacion', 'users']),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack(); // Deshacer la transacción en caso de error de validación
            return response()->json(['success' => false, 'errors' => $e->errors()], 400);
        } catch (\Exception $e) {
            DB::rollBack(); // Deshacer la transacción en caso de error general
            return response()->json(['success' => false, 'message' => 'Error al actualizar el cliente: ' . $e->getMessage()], 500);
        }
    }



    // Método de exportación de clientes 
    public function exportFilteredCustomers(Request $request)
    {
        // Obtén los filtros aplicados desde la solicitud
        $filters = $request->all();

        // Aplica los filtros a la consulta de tareas
        $query = Cliente::select([
            'id',
            'nombre_fiscal',
            'nif',
            'tipo_cliente_id',
            'clasificacion_id',
            'tributacion_id',
            'situacion_id',
            'movil',
            'fijo',
            'email',
            'direccion',
            'codigo_postal',
            'poblacion',
            'datos_bancarios',
            'subclase',
            'puntaje',
            'codigo_sage',
            'segundo_telefono',
            'persona_contacto',
            'created_at'
        ])->with(['tipoCliente', 'clasificacion', 'tributacion', 'situacion', 'users']);


        // Filtrar por nombre fiscal del cliente
        if (!empty($filters['nombre_fiscal'])) {
            $query->where('nombre_fiscal', 'like', '%' . $filters['nombre_fiscal'] . '%');
        }

        // Filtrar por NIF
        if (!empty($filters['nif'])) {
            $query->where('nif', 'like', '%' . $filters['nif'] . '%');
        }

        // Filtrar por tipo de cliente
        if (!empty($filters['tipo_cliente'])) {
            $tipoCliente = TipoCliente::where('nombre', 'like', '%' . $filters['tipo_cliente'] . '%')->first();
            if ($tipoCliente) {
                $query->where('tipo_cliente_id', $tipoCliente->id);
            }
        }

        // Filtrar por clasificación
        if (!empty($filters['clasificacion'])) {
            $clasificacion = Clasificacion::where('nombre', 'like', '%' . $filters['clasificacion'] . '%')->first();
            if ($clasificacion) {
                $query->where('clasificacion_id', $clasificacion->id);
            }
        }

        // Filtrar por tributación
        if (!empty($filters['tributacion'])) {
            $tributacion = Tributacion::where('nombre', 'like', '%' . $filters['tributacion'] . '%')->first();
            if ($tributacion) {
                $query->where('tributacion_id', $tributacion->id);
            }
        }

        // Filtrar por situación
        if (!empty($filters['situacion'])) {
            $situacion = Situacion::where('nombre', 'like', '%' . $filters['situacion'] . '%')->first();
            if ($situacion) {
                $query->where('situacion_id', $situacion->id);
            }
        }
        // Filtrar por móvil
        if (!empty($filters['movil'])) {
            $query->where('movil', 'like', '%' . $filters['movil'] . '%');
        }

        // Filtrar por fijo
        if (!empty($filters['fijo'])) {
            $query->where('fijo', 'like', '%' . $filters['fijo'] . '%');
        }

        // Filtrar por email
        if (!empty($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }

        // Filtrar por dirección
        if (!empty($filters['direccion'])) {
            $query->where('direccion', 'like', '%' . $filters['direccion'] . '%');
        }

        // Filtrar por código postal
        if (!empty($filters['codigo_postal'])) {
            $query->where('codigo_postal', 'like', '%' . $filters['codigo_postal'] . '%');
        }

        // Filtrar por población
        if (!empty($filters['poblacion'])) {
            $query->where('poblacion', 'like', '%' . $filters['poblacion'] . '%');
        }

        // Filtrar por usuario responsable asignado
        if (!empty($filters['usuario'])) {
            $userIds = explode(',', $filters['usuario']);
            $query->whereHas('users', function ($q) use ($userIds) {
                $q->whereIn('users.id', $userIds);
            });
        }

        // Filtrar por datos bancarios
        if (!empty($filters['datos_bancarios'])) {
            $query->where('datos_bancarios', 'like', '%' . $filters['datos_bancarios'] . '%');
        }

        // Filtrar por subclase
        if (!empty($filters['subclase'])) {
            $query->where('subclase', 'like', '%' . $filters['subclase'] . '%');
        }

        // Filtrar por puntaje
        if (!empty($filters['puntaje'])) {
            $query->where('puntaje', '=', $filters['puntaje']);
        }

        // Filtrar por código SAGE
        if (!empty($filters['codigo_sage'])) {
            $query->where('codigo_sage', 'like', '%' . $filters['codigo_sage'] . '%');
        }

        // Filtrar por segundo teléfono
        if (!empty($filters['segundo_telefono'])) {
            $query->where('segundo_telefono', 'like', '%' . $filters['segundo_telefono'] . '%');
        }

        // Filtrar por persona de contacto
        if (!empty($filters['persona_contacto'])) {
            $query->where('persona_contacto', 'like', '%' . $filters['persona_contacto'] . '%');
        }


        // Añadir el orden por fecha de creación, de más reciente a más antigua
        $query->orderBy('created_at', 'desc');



        $filteredCustomers = $query->get();
        $fileName = $filters['fileName'] ?? 'clientes_filtrados.xlsx';

        // Exporta las tareas filtradas

        return Excel::download(new CustomersExport($filteredCustomers), $fileName);
    }
}
