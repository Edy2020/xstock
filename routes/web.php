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
        Route::get('/',        [\App\Http\Controllers\VentaController::class, 'index'])->name('index');
        Route::get('/nueva',   [\App\Http\Controllers\VentaController::class, 'create'])->name('create');
        Route::post('/',       [\App\Http\Controllers\VentaController::class, 'store'])->name('store');
        Route::get('/{venta}', [\App\Http\Controllers\VentaController::class, 'show'])->name('show');
        Route::delete('/{venta}', [\App\Http\Controllers\VentaController::class, 'destroy'])->name('destroy');
    });

    // Proveedores
    Route::resource('proveedores', \App\Http\Controllers\ProveedorController::class)
        ->except(['show']);


    // Estadísticas
    Route::get('/estadisticas', [\App\Http\Controllers\EstadisticaController::class, 'index'])->name('estadisticas.index');

    // ─── ADMINISTRACIÓN ──────────────────────────────────────

    // Historial / Logs
    Route::get('/historial', [\App\Http\Controllers\HistorialController::class, 'index'])->name('historial.index');

    // Usuarios
    Route::get('/usuarios', fn() => view('usuarios.index'))->name('usuarios.index');

    // Roles y Permisos
    Route::get('/roles', fn() => view('roles.index'))->name('roles.index');

});

require __DIR__.'/auth.php';
