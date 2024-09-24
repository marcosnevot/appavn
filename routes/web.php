<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|


Route::get('/', function () {
    return view('welcome');
});

*/

Route::get('/', function () {
    return redirect()->route('tasks.index');
})->middleware(['auth', 'verified'])->name('home');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/tareas', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tareas', [TaskController::class, 'store'])->name('tasks.store');
    Route::post('/tareas/filtrar', [TaskController::class, 'filter'])->name('tareas.filtrar');
    Route::get('/tareas/paginate', [TaskController::class, 'paginate']);

    Route::post('/tareas/{id}', [TaskController::class, 'update']);

   
   
    Route::get('/clientes', [ClientController::class, 'index'])->name('clients.index');
});

require __DIR__ . '/auth.php';
