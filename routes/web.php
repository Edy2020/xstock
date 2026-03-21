<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard principal
Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class , 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// Rutas protegidas
Route::middleware('auth')->group(function () {

    // Perfil de usuario (Breeze)
    Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');

    // Productos
    Route::post('productos/import', [\App\Http\Controllers\ProductoController::class , 'import'])->name('productos.import');
    Route::get('productos/export/excel', [\App\Http\Controllers\ProductoController::class , 'exportExcel'])->name('productos.export.excel');
    Route::get('productos/export/pdf', [\App\Http\Controllers\ProductoController::class , 'exportPdf'])->name('productos.export.pdf');
    Route::resource('productos', \App\Http\Controllers\ProductoController::class);


    // Ventas
    Route::prefix('ventas')->name('ventas.')->group(function () {
            Route::get('/', [\App\Http\Controllers\VentaController::class , 'index'])->name('index');
            Route::get('/nueva', [\App\Http\Controllers\VentaController::class , 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\VentaController::class , 'store'])->name('store');
            Route::get('/export', [\App\Http\Controllers\VentaController::class , 'exportOptions'])->name('export.options');
            Route::get('/export/excel', [\App\Http\Controllers\VentaController::class , 'exportExcel'])->name('export.excel');
            Route::get('/export/pdf', [\App\Http\Controllers\VentaController::class , 'exportPdf'])->name('export.pdf');
            Route::get('/{venta}', [\App\Http\Controllers\VentaController::class , 'show'])->name('show');
            Route::post('/{venta}/anular', [\App\Http\Controllers\VentaController::class , 'anular'])->name('anular');
            Route::delete('/{venta}', [\App\Http\Controllers\VentaController::class , 'destroy'])->name('destroy');
        }
        );

        // Proveedores
        Route::post('proveedores/import', [\App\Http\Controllers\ProveedorController::class , 'import'])->name('proveedores.import');
        Route::resource('proveedores', \App\Http\Controllers\ProveedorController::class)->parameters([
            'proveedores' => 'proveedor'
        ]);


        // Estadísticas
        Route::get('/estadisticas', [\App\Http\Controllers\EstadisticaController::class , 'index'])->name('estadisticas.index');

        // Notificaciones
        Route::prefix('notificaciones')->name('notificaciones.')->group(function () {
            Route::get('/unread', [\App\Http\Controllers\NotificacionController::class , 'unread'])->name('unread');
            Route::post('/{id}/read', [\App\Http\Controllers\NotificacionController::class , 'markAsRead'])->name('markAsRead');
            Route::post('/clear-all', [\App\Http\Controllers\NotificacionController::class , 'clearAll'])->name('clearAll');
        }
        );

        // Recordatorios (Dashboard Calendar)
        Route::prefix('recordatorios')->name('recordatorios.')->group(function () {
            Route::get('/', [\App\Http\Controllers\RecordatorioController::class , 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\RecordatorioController::class , 'store'])->name('store');
            Route::put('/{recordatorio}', [\App\Http\Controllers\RecordatorioController::class , 'update'])->name('update');
            Route::delete('/{recordatorio}', [\App\Http\Controllers\RecordatorioController::class , 'destroy'])->name('destroy');
        }
        );

        // Historial / Logs
        Route::get('/historial', [\App\Http\Controllers\HistorialController::class , 'index'])->name('historial.index')->middleware('permission:historial.ver');
        Route::get('/historial/{logActividad}', [\App\Http\Controllers\HistorialController::class , 'show'])->name('historial.show')->middleware('permission:historial.ver');

        // Usuarios
        Route::resource('/usuarios', \App\Http\Controllers\UsuarioController::class)->except(['create', 'show', 'edit'])->middleware('permission:usuarios.gestionar');

        // Roles
        Route::get('/roles', [\App\Http\Controllers\RoleController::class , 'index'])->name('roles.index')->middleware('permission:roles.gestionar');
        Route::put('/roles/{role}', [\App\Http\Controllers\RoleController::class , 'update'])->name('roles.update')->middleware('permission:roles.gestionar');
        Route::post('/roles/bulk', [\App\Http\Controllers\RoleController::class , 'updateBulk'])->name('roles.bulk')->middleware('permission:roles.gestionar');
    });

require __DIR__ . '/auth.php';
