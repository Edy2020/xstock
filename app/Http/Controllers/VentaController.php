<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\LogActividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with(['detalles', 'vendedor'])->latest()->get();
        
        $totalVentas = Venta::count();
        $ingresosHoy = Venta::whereDate('created_at', now()->toDateString())->sum('total');

        return view('ventas.index', compact('ventas', 'totalVentas', 'ingresosHoy'));
    }

    public function create()
    {
        abort_unless(auth()->user()->hasPermission('ventas.crear'), 403, 'No tienes permiso para registrar ventas.');

        $productos = Producto::where('estado', 'activo')
            ->where('stock', '>', 0)
            ->orderBy('nombre')
            ->get();
            
        return view('ventas.create', compact('productos'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasPermission('ventas.crear'), 403, 'No tienes permiso para registrar ventas.');

        $request->validate([
            'metodo_pago' => 'required|string|in:Efectivo,Tarjeta de crédito,Tarjeta de débito,Transferencia',
            'notas'       => 'nullable|string',
            'productos'   => 'required|string',
            'descuento_global' => 'nullable|numeric|min:0|max:100',
        ]);

        $productosJson = json_decode($request->productos, true);

        if (empty($productosJson)) {
            return back()->withErrors(['error' => 'Debe añadir al menos un producto a la venta.'])->withInput();
        }

        try {
            DB::beginTransaction();

            $subtotalVenta = 0;
            $descuentoTotalVenta = 0;
            $totalVenta = 0;

            $venta = Venta::create([
                'user_id'         => auth()->id(),
                'subtotal'        => 0,
                'descuento_total' => 0,
                'total'           => 0,
                'metodo_pago'     => $request->metodo_pago,
                'notas'           => $request->notas,
            ]);

            foreach ($productosJson as $item) {
                $producto = Producto::where('id', $item['id'])->lockForUpdate()->first();

                if (!$producto || $producto->stock < $item['cantidad']) {
                    throw new \Exception("Stock insuficiente para: " . ($producto ? $producto->nombre : 'Producto desconocido'));
                }

                $cantidad = (int) $item['cantidad'];
                $precioUnitario = (int) $producto->precio;
                $descuentoPorcentaje = (int) ($item['descuento'] ?? 0);
                
                $montoDescuento = ($precioUnitario * $cantidad) * ($descuentoPorcentaje / 100);
                $subtotalItem = ($precioUnitario * $cantidad) - $montoDescuento;

                DetalleVenta::create([
                    'venta_id'             => $venta->id,
                    'producto_id'          => $producto->id,
                    'producto_nombre'      => $producto->nombre,
                    'cantidad'             => $cantidad,
                    'precio_unitario'      => $precioUnitario,
                    'descuento_porcentaje' => $descuentoPorcentaje,
                    'subtotal'             => $subtotalItem,
                ]);

                $producto->stock -= $cantidad;
                $producto->save();

                if ($producto->stock <= 5) {
                    auth()->user()->notify(new \App\Notifications\GeneralNotification(
                        'Stock Crítico',
                        "El producto '{$producto->nombre}' tiene " . $producto->stock . " unidades disponibles.",
                        'danger',
                        route('productos.index')
                    ));
                }

                $subtotalVenta += ($precioUnitario * $cantidad);
                $descuentoTotalVenta += $montoDescuento;
                $totalVenta += $subtotalItem;
            }

            $descuentoGlobalPorcentaje = (float) ($request->descuento_global ?? 0);
            if ($descuentoGlobalPorcentaje > 0 && $descuentoGlobalPorcentaje <= 100) {
                $montoDescGlobal = $totalVenta * ($descuentoGlobalPorcentaje / 100);
                $totalVenta -= $montoDescGlobal;
                $descuentoTotalVenta += $montoDescGlobal;
            }

            $venta->update([
                'subtotal'        => $subtotalVenta,
                'descuento_total' => $descuentoTotalVenta,
                'total'           => $totalVenta,
            ]);
            LogActividad::create([
                'user_id' => auth()->id(),
                'accion' => 'Creación',
                'modulo' => 'Ventas',
                'detalle' => 'Registró nueva venta #' . str_pad($venta->id, 5, '0', STR_PAD_LEFT) . ' por $' . number_format($totalVenta, 0, ',', '.'),
                'ip_address' => request()->ip(),
            ]);

            auth()->user()->notify(new \App\Notifications\GeneralNotification(
                'Venta Registrada',
                'Se completó una venta por $' . number_format($totalVenta, 0, ',', '.'),
                'success',
                route('ventas.show', $venta->id)
            ));

            DB::commit();

            return redirect()->route('ventas.show', $venta)
                ->with('success', 'Venta registrada exitosamente. Total: $ ' . number_format($totalVenta, 0, ',', '.'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al procesar la venta: ' . $e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        $venta = Venta::with(['detalles', 'vendedor'])->findOrFail($id);
        return view('ventas.show', compact('venta'));
    }

    public function anular(Venta $venta)
    {
        abort_unless(auth()->user()->hasPermission('ventas.anular'), 403, 'No tienes permiso para anular ventas.');

        if ($venta->estado === 'anulada') {
            return back()->withErrors(['error' => 'La venta ya se encuentra anulada.']);
        }

        try {
            DB::beginTransaction();
            
            foreach ($venta->detalles as $detalle) {
                if ($detalle->producto_id) {
                    Producto::where('id', $detalle->producto_id)->increment('stock', $detalle->cantidad);
                }
            }
            
            $venta->update(['estado' => 'anulada']);

            LogActividad::create([
                'user_id' => auth()->id(),
                'accion' => 'Anulación',
                'modulo' => 'Ventas',
                'detalle' => 'Anuló venta #' . str_pad($venta->id, 5, '0', STR_PAD_LEFT) . ' de $' . number_format($venta->total, 0, ',', '.'),
                'ip_address' => request()->ip(),
            ]);
            
            DB::commit();
            
            return redirect()->route('ventas.index')->with('success', 'Venta anulada y stock devuelto exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al anular la venta: ' . $e->getMessage()]);
        }
    }

    public function destroy(Venta $venta)
    {
        abort_unless(auth()->user()->hasPermission('ventas.anular'), 403, 'No tienes permiso para eliminar ventas.');

        if ($venta->estado !== 'anulada') {
            return back()->withErrors(['error' => 'La venta debe estar anulada antes de poder eliminarla permanentemente.']);
        }

        try {
            DB::beginTransaction();
            
            $ventaId = $venta->id;
            $totalOriginal = $venta->total;

            $venta->delete();

            LogActividad::create([
                'user_id' => auth()->id(),
                'accion' => 'Eliminación',
                'modulo' => 'Ventas',
                'detalle' => 'Eliminó permanentemente venta #' . str_pad($ventaId, 5, '0', STR_PAD_LEFT) . ' de $' . number_format($totalOriginal, 0, ',', '.'),
                'ip_address' => request()->ip(),
            ]);
            
            DB::commit();
            
            return redirect()->route('ventas.index')->with('success', 'Venta eliminada permanentemente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al eliminar la venta: ' . $e->getMessage()]);
        }
    }
    public function exportOptions()
    {
        return view('ventas.export_options');
    }

    public function exportExcel(Request $request)
    {
        $periodo = $request->get('periodo', 'todo');
        $ventas  = $this->getVentasByPeriod($periodo);

        LogActividad::create([
            'user_id'    => auth()->id(),
            'accion'     => 'Exportación',
            'modulo'     => 'Ventas',
            'detalle'    => 'Exportó ventas a CSV — período: ' . $periodo . ' (' . $ventas->count() . ' registros).',
            'ip_address' => request()->ip(),
        ]);

        $fileName = 'ventas_' . $periodo . '_' . now()->format('Y-m-d_H-i') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($ventas) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['ID Venta', 'Fecha', 'Hora', 'Vendedor', 'Método Pago', 'Items (Qty)', 'Subtotal', 'Descuento', 'Total', 'Notas'], ';');
            foreach ($ventas as $v) {
                fputcsv($handle, [
                    '#' . str_pad($v->id, 5, '0', STR_PAD_LEFT),
                    $v->created_at->format('d/m/Y'),
                    $v->created_at->format('H:i'),
                    $v->vendedor->name ?? 'Desconocido',
                    $v->metodo_pago,
                    $v->detalles->sum('cantidad'),
                    $v->subtotal,
                    $v->descuento_total,
                    $v->total,
                    $v->notas ?? '',
                ], ';');
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $periodo = $request->get('periodo', 'todo');
        $ventas  = $this->getVentasByPeriod($periodo);

        LogActividad::create([
            'user_id'    => auth()->id(),
            'accion'     => 'Exportación',
            'modulo'     => 'Ventas',
            'detalle'    => 'Exportó ventas a PDF — período: ' . $periodo . ' (' . $ventas->count() . ' registros).',
            'ip_address' => request()->ip(),
        ]);

        $pdf = Pdf::loadView('ventas.export_pdf', compact('ventas', 'periodo'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('ventas_' . $periodo . '_' . now()->format('Y-m-d_H-i') . '.pdf');
    }

    private function getVentasByPeriod(string $periodo)
    {
        $query = Venta::with(['detalles', 'vendedor'])->latest();

        return match($periodo) {
            'hoy'    => $query->whereDate('created_at', now()->toDateString())->get(),
            'semana' => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->get(),
            'mes'    => $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->get(),
            'anno'   => $query->whereYear('created_at', now()->year)->get(),
            default  => $query->get(),
        };
    }
}
