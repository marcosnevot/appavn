<?php

namespace App\Http\Controllers;

use App\Models\Asunto;
use App\Models\Clasificacion;
use App\Models\Situacion;
use App\Models\Tarea;
use App\Models\Tipo;
use App\Models\TipoCliente;
use App\Models\Tributacion;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Muestra la vista principal del panel de administrador.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.index');
    }

    public function periodicIndex()
    {
        return view('periodic.index');
    }


    /**
     * Actualiza un dato (Asunto o Tipo).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $entity
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $entity, $id)
    {
        $model = $this->getModel($entity);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $item = $model::findOrFail($id);
        $item->nombre = $validated['nombre'];
        $item->save();

        return response()->json(['message' => ucfirst($entity) . ' actualizado correctamente.', 'item' => $item]);
    }

    /**
     * Elimina un dato (Asunto o Tipo).
     *
     * @param  string  $entity
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($entity, $id)
    {
        $model = $this->getModel($entity);

        $item = $model::findOrFail($id);
        $item->delete();

        return response()->json(['message' => ucfirst($entity) . ' eliminado correctamente.']);
    }

    /**
     * Obtiene el modelo correspondiente a la entidad.
     *
     * @param  string  $entity
     * @return string
     * @throws \Exception
     */
    protected function getModel($entity)
    {
        return match ($entity) {
            'asuntos' => Asunto::class,
            'tipos' => Tipo::class,
            'clasificaciones' => Clasificacion::class,
            'situaciones' => Situacion::class,
            'tributaciones' => Tributacion::class,
            'tiposcliente' => TipoCliente::class,
            default => throw new \Exception('Entidad no soportada.'),
        };
    }


    /**
     * Carga todos los usuarios.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexUsers()
    {
        try {
            $users = User::all(['id', 'name', 'email']);
            $users = $users->map(function ($user) {
                $user->role = $user->getRoleNames()->first(); // Obtiene el primer rol asignado
                return $user;
            });

            return response()->json($users, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    /**
     * Crea un nuevo usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:5', // Validación de contraseña
            'role' => 'required|exists:roles,name', // Valida que el rol exista
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']), // Encripta la contraseña
        ]);

        $user->assignRole($validated['role']); // Asigna el rol

        return response()->json(['message' => 'Usuario creado correctamente.', 'user' => $user], 201);
    }



    /**
     * Actualiza un usuario existente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validación de entrada
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:5', // Contraseña opcional
            'role' => 'required|exists:roles,name', // Rol obligatorio
        ]);

        // Construcción de los datos para actualizar
        $dataToUpdate = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        // Solo agrega la contraseña si está presente
        if (isset($validated['password'])) {
            $dataToUpdate['password'] = bcrypt($validated['password']);
        }

        // Actualiza el usuario
        $user->update($dataToUpdate);

        $user->syncRoles($validated['role']); // Actualiza el rol

        return response()->json(['message' => 'Usuario actualizado correctamente.', 'user' => $user], 200);
    }


    /**
     * Elimina un usuario.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('admin')) {
            return response()->json(['message' => 'No se puede eliminar un usuario con rol de administrador.'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'Usuario eliminado correctamente.'], 204);
    }






    /**
     * Muestra la lista de tareas periódicas con soporte para filtros.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexPeriodic(Request $request)
    {
        $query = Tarea::query();

        // Filtrar solo las tareas con periodicidad definida (distinta de "NO")
        $query->where('periodicidad', '<>', 'NO');

        // Aplicar relaciones para cliente, asunto y asignaciones
        $query->with(['cliente', 'asunto', 'users']);

        // Aplicar filtros adicionales si existen
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        if ($request->filled('cliente')) {
            $query->whereHas('cliente', function ($q) use ($request) {
                $q->where('nombre_fiscal', 'like', '%' . $request->cliente . '%');
            });
        }

        if ($request->filled('asunto')) {
            $query->whereHas('asunto', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->asunto . '%');
            });
        }

        // Filtro por usuario (asignacion)
        if ($request->has('asignacion') && !empty($request->asignacion)) {
            $query->whereHas('users', function (Builder $q) use ($request) {
                $q->where('users.id', $request->asignacion);
            });
        }


        if ($request->filled('descripcion')) {
            $query->where('descripcion', 'like', '%' . $request->descripcion . '%');
        }

        // Filtrar por periodicidad si está presente
        if ($request->has('periodicidad') && !empty($request->periodicidad)) {
            $query->where('periodicidad', $request->periodicidad);
        }

        if ($request->filled('fecha_inicio_generacion')) {
            $query->where('fecha_inicio_generacion', $request->fecha_inicio_generacion);
        }

        $query->orderBy('periodicidad', 'asc');

        $tareas = $query->get();

        // Transformar los datos para incluir nombres de relaciones
        $tareasTransformadas = $tareas->map(function ($tarea) {
            return [
                'id' => $tarea->id,
                'cliente_nombre' => $tarea->cliente->nombre_fiscal ?? null,
                'asunto_nombre' => $tarea->asunto->nombre ?? null,
                'descripcion' => $tarea->descripcion,
                'asignacion_nombre' => $tarea->users->pluck('name')->join(', ') ?? null,
                'periodicidad' => $tarea->periodicidad,
                'fecha_inicio_generacion' => $tarea->fecha_inicio_generacion,
            ];
        });

        return response()->json($tareasTransformadas);
    }

    /**
     * Muestra los detalles de una tarea periódica específica.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPeriodic($id)
    {
        $tarea = Tarea::findOrFail($id);

        return response()->json($tarea);
    }

    /**
     * Actualiza una tarea periódica específica.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePeriodic(Request $request, $id)
    {
        $validated = $request->validate([
            'periodicidad' => 'required|in:NO,SEMANAL,MENSUAL,TRIMESTRAL,ANUAL',
            'fecha_inicio_generacion' => 'nullable|date',
        ]);

        $tarea = Tarea::findOrFail($id);

        // Si la periodicidad es "NO", eliminamos la fecha de inicio generación
        if ($validated['periodicidad'] === 'NO') {
            $validated['fecha_inicio_generacion'] = null;
        }

        $tarea->update($validated);


        return response()->json([
            'success' => true,
            'message' => 'Tarea actualizada correctamente.',
            'tarea' => $tarea,
        ]);
    }
}
