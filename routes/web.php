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
    Route::prefix('productos')->name('productos.')->group(function () {
        Route::get('/',            fn() => view('productos.index'))->name('index');
        Route::get('/crear',       fn() => view('productos.create'))->name('create');
        Route::post('/',           fn() => redirect()->route('productos.index'))->name('store');
        Route::get('/{id}',        fn($id) => view('productos.show', ['producto' => null]))->name('show');
        Route::get('/{id}/editar', fn($id) => view('productos.edit', ['producto' => null]))->name('edit');
        Route::put('/{id}',        fn($id) => redirect()->route('productos.index'))->name('update');
        Route::delete('/{id}',     fn($id) => redirect()->route('productos.index'))->name('destroy');
    });

    // Ventas
    Route::prefix('ventas')->name('ventas.')->group(function () {
        Route::get('/',        fn() => view('ventas.index'))->name('index');
        Route::get('/nueva',   fn() => view('ventas.create'))->name('create');
        Route::post('/',       fn() => redirect()->route('ventas.index'))->name('store');
        Route::get('/{id}',    fn($id) => view('ventas.index'))->name('show');
    });

    // Proveedores
    Route::prefix('proveedores')->name('proveedores.')->group(function () {
        Route::get('/',            fn() => view('proveedores.index'))->name('index');
        Route::get('/crear',       fn() => view('proveedores.create'))->name('create');
        Route::post('/',           fn() => redirect()->route('proveedores.index'))->name('store');
        Route::get('/{id}/editar', fn($id) => view('proveedores.edit', ['proveedor' => null]))->name('edit');
        Route::put('/{id}',        fn($id) => redirect()->route('proveedores.index'))->name('update');
        Route::delete('/{id}',     fn($id) => redirect()->route('proveedores.index'))->name('destroy');
    });

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
