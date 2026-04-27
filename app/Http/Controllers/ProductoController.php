<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\LogActividad;
use App\Models\Gasto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with('proveedor')->latest()->get();

        $categorias = Producto::select('categoria')
            ->distinct()
            ->whereNotNull('categoria')
            ->orderBy('categoria')
            ->pluck('categoria');

        $proveedores = Proveedor::orderBy('nombre')->get();

        return view('productos.index', compact('productos', 'categorias', 'proveedores'));
    }

    public function create()
    {
        abort_unless(auth()->user()->hasPermission('productos.crear'), 403, 'No tienes permiso para crear productos.');

        $categorias = Producto::select('categoria')
            ->distinct()
            ->whereNotNull('categoria')
            ->orderBy('categoria')
            ->pluck('categoria');

        $proveedores = Proveedor::where('estado', 'activo')->orderBy('nombre')->get();

        return view('productos.create', compact('categorias', 'proveedores'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasPermission('productos.crear'), 403, 'No tienes permiso para crear productos.');

        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:productos,nombre',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'categoria' => 'nullable|string|max:100',
            'precio' => 'required|integer|min:0',
            'precio_compra' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'descuento' => 'nullable|integer|min:0|max:100',
            'estado' => 'required|in:activo,inactivo',
            'proveedor_nombre' => 'nullable|string|max:255',
        ], [
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'nombre.unique' => 'Ya existe un producto registrado con este mismo nombre.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser JPG, PNG o WebP.',
            'imagen.max' => 'La imagen no puede pesar más de 2 MB.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.integer' => 'El precio debe ser un número entero (CLP).',
            'stock.required' => 'El stock es obligatorio.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'descuento.integer' => 'el descuento debe ser un número entero.',
            'descuento.min' => 'El descuento no puede ser menor a 0.',
            'descuento.max' => 'El descuento no puede ser mayor a 100.',
            'estado.required' => 'El estado es obligatorio.',
        ]);

        $proveedor_id = null;
        if (!empty($validated['proveedor_nombre'])) {
            $prov = Proveedor::firstOrCreate(
            ['nombre' => trim($validated['proveedor_nombre'])],
            ['estado' => 'activo']
            );
            $proveedor_id = $prov->id;
        }

        $data = $validated;
        unset($data['proveedor_nombre'], $data['imagen']);
        $data['proveedor_id'] = $proveedor_id;

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto = Producto::create($data);

        if ($producto->stock > 0) {
            Gasto::create([
                'producto_id' => $producto->id,
                'proveedor_id' => $producto->proveedor_id,
                'cantidad' => $producto->stock,
                'precio_compra' => $producto->precio_compra,
                'total' => $producto->stock * $producto->precio_compra,
            ]);
        }

        LogActividad::create([
            'user_id' => auth()->id(),
            'accion' => 'Creación',
            'modulo' => 'Productos',
            'detalle' => 'Registró el producto: ' . $producto->nombre,
            'ip_address' => request()->ip(),
        ]);

        auth()->user()->notify(new \App\Notifications\GeneralNotification(
            'Nuevo Producto',
            "Se ha añadido el producto '{$producto->nombre}'.",
            'info',
            route('productos.index')
            ));

        return redirect()->route('productos.index')
            ->with('success', 'Producto "' . $request->nombre . '" añadido correctamente.');
    }

    public function show(Producto $producto)
    {
        $producto->load('proveedor');

        $ultimasVentas = \App\Models\DetalleVenta::where('producto_id', $producto->id)
            ->with('venta')
            ->latest()
            ->take(5)
            ->get();

        $historialGastos = $producto->gastos()->with('proveedor')->latest()->get();

        return view('productos.show', compact('producto', 'ultimasVentas', 'historialGastos'));
    }

    public function edit(Producto $producto)
    {
        abort_unless(auth()->user()->hasPermission('productos.editar'), 403, 'No tienes permiso para editar productos.');

        $categorias = Producto::select('categoria')
            ->distinct()
            ->whereNotNull('categoria')
            ->orderBy('categoria')
            ->pluck('categoria');

        $proveedores = Proveedor::where('estado', 'activo')->orderBy('nombre')->get();

        return view('productos.edit', compact('producto', 'categorias', 'proveedores'));
    }

    public function update(Request $request, Producto $producto)
    {
        abort_unless(auth()->user()->hasPermission('productos.editar'), 403, 'No tienes permiso para editar productos.');

        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:productos,nombre,' . $producto->id,
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'categoria' => 'nullable|string|max:100',
            'precio' => 'required|integer|min:0',
            'precio_compra' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'descuento' => 'nullable|integer|min:0|max:100',
            'estado' => 'required|in:activo,inactivo',
            'proveedor_nombre' => 'nullable|string|max:255',
        ], [
            'nombre.unique' => 'Ya existe otro producto registrado con este mismo nombre.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser JPG, PNG o WebP.',
            'imagen.max' => 'La imagen no puede pesar más de 2 MB.',
        ]);

        $proveedor_id = null;
        if (!empty($validated['proveedor_nombre'])) {
            $prov = Proveedor::firstOrCreate(
            ['nombre' => trim($validated['proveedor_nombre'])],
            ['estado' => 'activo']
            );
            $proveedor_id = $prov->id;
        }

        $data = $validated;
        unset($data['proveedor_nombre'], $data['imagen']);
        $data['proveedor_id'] = $proveedor_id;

        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        if ($request->boolean('eliminar_imagen') && !$request->hasFile('imagen')) {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $data['imagen'] = null;
        }

        $viejo_stock = $producto->stock;
        $producto->update($data);

        if ($producto->stock > $viejo_stock) {
            Gasto::create([
                'producto_id' => $producto->id,
                'proveedor_id' => $producto->proveedor_id,
                'cantidad' => $producto->stock - $viejo_stock,
                'precio_compra' => $producto->precio_compra,
                'total' => ($producto->stock - $viejo_stock) * $producto->precio_compra,
            ]);
        }

        LogActividad::create([
            'user_id' => auth()->id(),
            'accion' => 'Actualización',
            'modulo' => 'Productos',
            'detalle' => 'Actualizó el producto: ' . $producto->nombre,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Producto $producto)
    {
        abort_unless(auth()->user()->hasPermission('productos.eliminar'), 403, 'No tienes permiso para eliminar productos.');

        $nombre = $producto->nombre;
        $producto->delete();

        LogActividad::create([
            'user_id' => auth()->id(),
            'accion' => 'Eliminación',
            'modulo' => 'Productos',
            'detalle' => 'Eliminó el producto: ' . $nombre,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }

    public function import(Request $request)
    {
        abort_unless(auth()->user()->hasPermission('productos.crear'), 403, 'No tienes permiso para importar productos.');

        $request->validate([
            'archivo_csv' => 'required|file|mimes:csv,txt|max:5120',
        ], [
            'archivo_csv.required' => 'Debe seleccionar un archivo CSV.',
            'archivo_csv.mimes' => 'El archivo debe tener formato .csv o .txt.',
        ]);

        $file = $request->file('archivo_csv');
        $handle = fopen($file->getRealPath(), "r");

        fgetcsv($handle, 1000, ",");

        $agregados = 0;
        $omitidos = 0;
        $nombresAgregados = [];

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                if (empty(trim($row[0] ?? '')))
                    continue;

                $nombre = trim($row[0]);
                if (Producto::where('nombre', $nombre)->exists()) {
                    $omitidos++;
                    continue;
                }

                $proveedorNombre = trim($row[3] ?? '');
                $proveedor_id = null;
                if (!empty($proveedorNombre)) {
                    $prov = \App\Models\Proveedor::firstOrCreate(
                    ['nombre' => $proveedorNombre],
                    ['estado' => 'activo']
                    );
                    $proveedor_id = $prov->id;
                }

                Producto::create([
                    'nombre' => $nombre,
                    'descripcion' => $row[1] ?? null,
                    'categoria' => $row[2] ?? null,
                    'proveedor_id' => $proveedor_id,
                    'precio' => is_numeric($row[4] ?? null) ? (int)$row[4] : 0,
                    'stock' => is_numeric($row[5] ?? null) ? (int)$row[5] : 0,
                    'descuento' => is_numeric($row[7] ?? null) ? (int)$row[7] : 0,
                    'estado' => in_array(strtolower(trim($row[6] ?? '')), ['activo', 'inactivo']) ? strtolower(trim($row[6])) : 'activo',
                ]);
                $agregados++;
                if (count($nombresAgregados) < 300) {
                    $nombresAgregados[] = "• " . $nombre;
                }
            }
            fclose($handle);
            \Illuminate\Support\Facades\DB::commit();

            $detalleStr = "Se importó masivamente un archivo CSV.\n";
            $detalleStr .= "Total insertados exitosamente: {$agregados}.\n";
            $detalleStr .= "Total omitidos (por nombre duplicado): {$omitidos}.\n\n";
            if ($agregados > 0) {
                $detalleStr .= "Artículos cargados en esta sesión:\n";
                $detalleStr .= implode("\n", $nombresAgregados);
                if ($agregados > 300) {
                    $detalleStr .= "\n... y " . ($agregados - 300) . " artículos más.";
                }
            }

            LogActividad::create([
                'user_id' => auth()->id(),
                'accion' => 'Importación CSV',
                'modulo' => 'Productos',
                'detalle' => $detalleStr,
                'ip_address' => request()->ip(),
            ]);

            auth()->user()->notify(new \App\Notifications\GeneralNotification(
                'Carga Masiva Completada',
                "Se importaron {$agregados} productos nuevos al sistema.",
                'success',
                route('productos.index')
                ));

            return redirect()->route('productos.index')
                ->with('success', "Importación completada: {$agregados} añadidos, {$omitidos} omitidos por nombre duplicado.");
        }
        catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            fclose($handle);
            return back()->withErrors(['error' => 'Error estructural al leer CSV. Asegúrese de que tenga el formato correcto: Nombre, Descripción, Categoría, Proveedor, Precio, Stock, Estado.']);
        }
    }

    public function exportExcel()
    {
        abort_unless(auth()->user()->hasPermission('productos.crear'), 403);

        $productos = Producto::with('proveedor')->latest()->get();

        LogActividad::create([
            'user_id' => auth()->id(),
            'accion' => 'Exportación',
            'modulo' => 'Productos',
            'detalle' => 'Exportó el catálogo completo de productos a Excel/CSV (' . $productos->count() . ' registros).',
            'ip_address' => request()->ip(),
        ]);

        $fileName = 'productos_' . now()->format('Y-m-d_H-i') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($productos) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['Nombre', 'Descripción', 'Categoría', 'Proveedor', 'Precio (CLP)', 'Stock', 'Estado'], ';');
            foreach ($productos as $p) {
                fputcsv($handle, [
                    $p->nombre,
                    $p->descripcion ?? '',
                    $p->categoria ?? '',
                    $p->proveedor->nombre ?? '',
                    $p->precio,
                    $p->stock,
                    $p->estado,
                ], ';');
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        abort_unless(auth()->user()->hasPermission('productos.crear'), 403);

        $productos = Producto::with('proveedor')->latest()->get();

        LogActividad::create([
            'user_id' => auth()->id(),
            'accion' => 'Exportación',
            'modulo' => 'Productos',
            'detalle' => 'Exportó el catálogo completo de productos a PDF (' . $productos->count() . ' registros).',
            'ip_address' => request()->ip(),
        ]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('productos.export_pdf', compact('productos'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('productos_' . now()->format('Y-m-d_H-i') . '.pdf');
    }
}
