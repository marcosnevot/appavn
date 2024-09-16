<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Muestra la vista principal de Clientes.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Aquí iría la lógica para obtener los clientes (más adelante)
        return view('clients.index');  // Carga la vista de Clientes
    }
}
