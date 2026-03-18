<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\Proveedor;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Productos
        $totalProductos = Producto::count();
        $productosMes = Producto::whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->count();

        // 2. Ventas Hoy
        $ventasHoy = Venta::whereDate('created_at', now()->toDateString())->count();
        $ventasAyer = Venta::whereDate('created_at', now()->subDay()->toDateString())->count();
        
        $porcentajeVentas = 0;
        if ($ventasAyer > 0) {
            $porcentajeVentas = round((($ventasHoy - $ventasAyer) / $ventasAyer) * 100);
        } else if ($ventasHoy > 0) {
            $porcentajeVentas = 100;
        }

        // 3. Stock Crítico
        $umbralCritico = 5; // Consideramos crítico stock <= 5
        $countStockCritico = Producto::where('stock', '<=', $umbralCritico)
                                     ->where('estado', 'activo')
                                     ->count();

        $productosStockCritico = Producto::where('stock', '<=', $umbralCritico)
                                         ->where('estado', 'activo')
                                         ->orderBy('stock', 'asc')
                                         ->take(7)
                                         ->get();

        // 4. Proveedores
        $totalProveedores = Proveedor::count();
        $proveedoresActivos = Proveedor::where('estado', 'activo')->count();

        // 5. Últimas Ventas
        $ultimasVentas = Venta::with('detalles')->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalProductos', 'productosMes',
            'ventasHoy', 'porcentajeVentas',
            'countStockCritico', 'productosStockCritico', 'umbralCritico',
            'totalProveedores', 'proveedoresActivos',
            'ultimasVentas'
        ));
    }
}
