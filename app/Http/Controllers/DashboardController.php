<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\Proveedor;
use App\Models\Gasto;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProductos = Producto::count();
        $productosMes = Producto::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $ventasHoy = Venta::whereDate('created_at', now()->toDateString())->count();
        $ventasAyer = Venta::whereDate('created_at', now()->subDay()->toDateString())->count();

        $porcentajeVentas = 0;
        if ($ventasAyer > 0) {
            $porcentajeVentas = round((($ventasHoy - $ventasAyer) / $ventasAyer) * 100);
        }
        else if ($ventasHoy > 0) {
            $porcentajeVentas = 100;
        }

        $umbralCritico = 5;
        $countStockCritico = Producto::where('stock', '<=', $umbralCritico)
            ->where('estado', 'activo')
            ->count();

        $productosStockCritico = Producto::where('stock', '<=', $umbralCritico)
            ->where('estado', 'activo')
            ->orderBy('stock', 'asc')
            ->take(7)
            ->get();

        $totalGastos = Gasto::sum('total');
        $gastosMes = Gasto::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $ultimasVentas = Venta::with('detalles')->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalProductos', 'productosMes',
            'ventasHoy', 'porcentajeVentas',
            'countStockCritico', 'productosStockCritico', 'umbralCritico',
            'totalGastos', 'gastosMes',
            'ultimasVentas'
        ));
    }
}
