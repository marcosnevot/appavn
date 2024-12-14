<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\TaskController;
use App\Models\Asunto;
use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/asuntos', function () {
        logger('Acceso a /api/asuntos');
        return response()->json(Asunto::all(['id', 'nombre']));
    });


    Route::get('/tipos', function () {
        return response()->json(Tipo::all(['id', 'nombre']));
    });
});
