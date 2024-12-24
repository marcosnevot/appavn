<?php

namespace App\Http\Controllers;

use App\Models\Asunto;
use App\Models\Clasificacion;
use App\Models\Situacion;
use App\Models\Tipo;
use App\Models\TipoCliente;
use App\Models\Tributacion;
use App\Models\User;
use Illuminate\Http\Request;

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
}
