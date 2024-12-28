<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\TaskController;
use App\Models\Asunto;
use App\Models\Clasificacion;
use App\Models\Situacion;
use App\Models\Tipo;
use App\Models\TipoCliente;
use App\Models\Tributacion;
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
        return response()->json(Asunto::all(['id', 'nombre']));
    });

    Route::get('/tipos', function () {
        return response()->json(Tipo::all(['id', 'nombre']));
    });

    Route::get('/clasificaciones', function () {
        return response()->json(Clasificacion::all(['id', 'nombre']));
    });

    Route::get('/situaciones', function () {
        return response()->json(Situacion::all(['id', 'nombre']));
    });

    Route::get('/tributaciones', function () {
        return response()->json(Tributacion::all(['id', 'nombre']));
    });

    Route::get('/tiposcliente', function () {
        return response()->json(TipoCliente::all(['id', 'nombre']));
    });

    // Rutas dinámicas para actualización y eliminación
    Route::put('/{entity}/{id}', [AdminController::class, 'update'])
        ->whereIn('entity', ['asuntos', 'tipos', 'clasificaciones', 'situaciones', 'tributaciones', 'tiposcliente']);
    Route::delete('/{entity}/{id}', [AdminController::class, 'destroy'])
        ->whereIn('entity', ['asuntos', 'tipos', 'clasificaciones', 'situaciones', 'tributaciones', 'tiposcliente']);

    // Gestión de Usuarios
    Route::get('/users', [AdminController::class, 'indexUsers']); // Cambiamos el método al correcto
    Route::post('/users', [AdminController::class, 'storeUser']); // Método específico para crear usuarios
    Route::put('/users/{id}', [AdminController::class, 'updateUser']); // Método específico para actualizar usuarios
    Route::delete('/users/{id}', [AdminController::class, 'destroyUser']); // Método específico para eliminar usuarios

    // Estadísticas
    Route::prefix('stats')->group(function () {
        Route::get('/kpis', [AdminController::class, 'getKPIs']); // KPIs generales
        Route::get('/charts', [AdminController::class, 'getCharts']); // Datos de gráficos
        Route::post('/filtered-data', [AdminController::class, 'getFilteredData']); // Datos con filtros aplicados
    });

    // Tareas Periódicas
    Route::get('/periodic-tasks', [AdminController::class, 'indexPeriodic']);
    Route::get('/periodic-tasks/{id}', [AdminController::class, 'showPeriodic']);
    Route::put('/periodic-tasks/{id}', [AdminController::class, 'updatePeriodic']);

    Route::get('/calendar/tasks', [CalendarController::class, 'getTasks']);


});
