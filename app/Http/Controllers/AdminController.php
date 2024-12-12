<?php

namespace App\Http\Controllers;

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
}
