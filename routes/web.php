<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes — XStock
|--------------------------------------------------------------------------
*/

// Redirigir raíz al dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard principal
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas protegidas
Route::middleware('auth')->group(function () {

    // Perfil de usuario (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ─── GESTIÓN ─────────────────────────────────────────────

    // Productos
    Route::resource('productos', \App\Http\Controllers\ProductoController::class);


    // Ventas
    Route::prefix('ventas')->name('ventas.')->group(function () {
        Route::get('/',        fn() => view('ventas.index'))->name('index');
        Route::get('/nueva',   fn() => view('ventas.create'))->name('create');
        Route::post('/',       fn() => redirect()->route('ventas.index'))->name('store');
        Route::get('/{id}',    fn($id) => view('ventas.index'))->name('show');
    });

    // Proveedores
    Route::resource('proveedores', \App\Http\Controllers\ProveedorController::class)
        ->except(['show']);


    // Estadísticas
    Route::get('/estadisticas', fn() => view('estadisticas.index'))->name('estadisticas.index');

    // ─── ADMINISTRACIÓN ──────────────────────────────────────

    // Historial / Logs
    Route::get('/historial', fn() => view('historial.index'))->name('historial.index');

    // Usuarios
    Route::get('/usuarios', fn() => view('usuarios.index'))->name('usuarios.index');

    // Roles y Permisos
    Route::get('/roles', fn() => view('roles.index'))->name('roles.index');

});

require __DIR__.'/auth.php';
