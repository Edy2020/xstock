<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EstadisticaController extends Controller
{
    public function index(Request $request)
    {
        $periodo = $request->query('periodo', 'semana');
        
        $startDate = match($periodo) {
            'dia' => Carbon::today(),
            'semana' => Carbon::now()->startOfWeek(),
            'mes' => Carbon::now()->startOfMonth(),
            'anio' => Carbon::now()->startOfYear(),
            default => Carbon::now()->startOfWeek(),
        };

        // KPIs principales
        $ventasPeriodo = Venta::where('created_at', '>=', $startDate)->count();
        $ingresosPeriodo = Venta::where('created_at', '>=', $startDate)->sum('total');
        
        $totalProductos = Producto::count();
        $nuevoEsteMes = Producto::where('created_at', '>=', Carbon::now()->startOfMonth())->count();
        
        $productosCriticos = Producto::where('stock', '<=', 5)->orderBy('stock', 'asc')->get();
        $stockCritico = $productosCriticos->count();

        // Ventas por día (Esta semana, de Lunes a Domingo)
        $inicioSemana = Carbon::now()->startOfWeek();
        $diasData = collect();
        for ($i = 0; $i <= 6; $i++) {
            $date = $inicioSemana->copy()->addDays($i);
            $ventas = Venta::whereDate('created_at', $date)->count();
            $ingresos = Venta::whereDate('created_at', $date)->sum('total');
            $diasData->push([
                'dia' => ucfirst($date->locale('es')->translatedFormat('l')) . ($date->isToday() ? ' (Hoy)' : ''),
                'ventas' => $ventas,
                'ing' => '$' . number_format($ingresos, 0, ',', '.'),
                'is_today' => $date->isToday(),
            ]);
        }

        // Productos más vendidos (Top 5 histórico o del periodo)
        $topProductos = DetalleVenta::select('producto_nombre', DB::raw('SUM(cantidad) as total_vendido'))
            ->groupBy('producto_nombre')
            ->orderByDesc('total_vendido')
            ->take(5)
            ->get();
            
        $maxVentas = $topProductos->max('total_vendido') ?: 1; // Para calc de % en UI

        $top = $topProductos->map(function ($item) use ($maxVentas) {
            return [
                'nombre' => $item->producto_nombre,
                'ventas' => $item->total_vendido,
                'pct' => round(($item->total_vendido / $maxVentas) * 100)
            ];
        });

        // Inventario por Categoría
        $inventarioCat = Producto::select('categoria', 
                DB::raw('COUNT(*) as cantidad_productos'), 
                DB::raw('SUM(stock) as stock_total'),
                DB::raw('SUM(stock * precio) as valor_total')
            )
            ->groupBy('categoria')
            ->get();

        // Tendencia Últimas 4 Semanas
        $semanasData = collect();
        for ($i = 3; $i >= 0; $i--) {
            $start = Carbon::now()->subWeeks($i)->startOfWeek();
            $end = Carbon::now()->subWeeks($i)->endOfWeek();
            
            $ventasCount = Venta::whereBetween('created_at', [$start, $end])->count();
            $ingresosSum = Venta::whereBetween('created_at', [$start, $end])->sum('total');
            
            $semanasData->push([
                'sem' => 'Sem ' . (4 - $i) . ' (' . ucfirst($start->locale('es')->translatedFormat('d M')) . ' — ' . ucfirst($end->locale('es')->translatedFormat('d M')) . ')',
                'ventas' => $ventasCount,
                'ing' => '$' . number_format($ingresosSum, 0, ',', '.'),
            ]);
        }

        return view('estadisticas.index', compact(
            'periodo', 'ventasPeriodo', 'ingresosPeriodo', 'totalProductos', 'nuevoEsteMes', 'stockCritico', 'productosCriticos',
            'diasData', 'top', 'inventarioCat', 'semanasData'
        ));
    }
}
