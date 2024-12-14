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

    public function getAsuntos()
    {
        return response()->json(Asunto::all(['id', 'nombre']));
    }

    public function getTipos()
    {
        return response()->json(Tipo::all(['id', 'nombre']));
    }
}
