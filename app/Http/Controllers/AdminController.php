<?php

namespace App\Http\Controllers;

use App\Models\Asunto;
use App\Models\Tipo;
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
            default => throw new \Exception('Entidad no soportada.'),
        };
    }
}
