<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskExportController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

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
    $user = Auth::user();

    if ($user && $user->hasRole('employee') && $user->name === 'Nacho') {
        return redirect()->route('billing.index');
    }

    return redirect()->route('tasks.index');
})->middleware(['auth', 'verified'])->name('home');



Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    });

    
    Route::get('/tareas', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tareas', [TaskController::class, 'store'])->name('tasks.store');
    Route::post('/tareas/filtrar', [TaskController::class, 'filter'])->name('tareas.filtrar');
    Route::get('/tareas/getTasks', [TaskController::class, 'getTasks'])->name('tareas.get');

    Route::get('/tareas/{id}', [TaskController::class, 'show'])->name('tareas.show');
    Route::delete('/tareas/{id}', [TaskController::class, 'destroy'])->name('tareas.destroy');
    Route::get('/tareas/{id}/edit', [TaskController::class, 'edit'])->name('tareas.edit');
    Route::put('/tareas/{id}', [TaskController::class, 'update'])->name('tareas.update');
    Route::post('/tareas/export', [TaskController::class, 'exportFilteredTasks'])->name('tareas.export');

    Route::get('/billing', [TaskController::class, 'billingIndex'])->name('billing.index');
    Route::get('/billing/getBilling', [TaskController::class, 'getBilling'])->name('billing.get');


    Route::get('/calls', [TaskController::class, 'callsIndex'])->name('calls.index');


    Route::get('/times', [TaskController::class, 'timesIndex'])->name('times.index');
    Route::get('/times/getTimes', [TaskController::class, 'getTimes'])->name('times.get');


    Route::get('/notifications', function () {
        $notifications = auth()->user()->unreadNotifications()->latest()->take(30)->get();
        return response()->json($notifications);
    });

    Route::post('/notifications/{id}/read', function ($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['success' => true]);
    });
    Route::post('/notifications/mark-all-read', function () {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['status' => 'success']);
    });



    Route::get('/clientes', [ClientController::class, 'index'])->name('client.index');
    Route::post('/clientes', [ClientController::class, 'store'])->name('client.store');
    Route::post('/clientes/filtrar', [ClientController::class, 'filter'])->name('clients.filtrar');
    Route::get('/clientes/getCustomers', [ClientController::class, 'getCustomers'])->name('clients.get');

    Route::get('/clientes/{id}', [ClientController::class, 'show'])->name('clients.show');
    Route::delete('/clientes/{id}', [ClientController::class, 'destroy'])->name('clients.destroy');
    Route::get('/clientes/{id}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clientes/{id}', [ClientController::class, 'update'])->name('clients.update');
    Route::post('/clientes/export', [ClientController::class, 'exportFilteredCustomers'])->name('clientes.export');
});

require __DIR__ . '/auth.php';
