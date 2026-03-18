<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\LogActividad;
use Illuminate\Http\Request;

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
            'nombre'       => 'required|string|max:255|unique:productos,nombre',
            'descripcion'  => 'nullable|string',
            'categoria'    => 'nullable|string|max:100',
            'precio'       => 'required|integer|min:0',
            'stock'        => 'required|integer|min:0',
            'estado'       => 'required|in:activo,inactivo',
            'proveedor_nombre' => 'nullable|string|max:255',
        ], [
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'nombre.unique'   => 'Ya existe un producto registrado con este mismo nombre.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.integer'  => 'El precio debe ser un número entero (CLP).',
            'stock.required'  => 'El stock es obligatorio.',
            'stock.integer'   => 'El stock debe ser un número entero.',
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
        unset($data['proveedor_nombre']);
        $data['proveedor_id'] = $proveedor_id;

        $producto = Producto::create($data);

        // Registrar Historial
        LogActividad::create([
            'user_id' => auth()->id(),
            'accion' => 'Creación',
            'modulo' => 'Productos',
            'detalle' => 'Registró el producto: ' . $producto->nombre,
            'ip_address' => request()->ip(),
        ]);

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

        return view('productos.show', compact('producto', 'ultimasVentas'));
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
            'nombre'       => 'required|string|max:255|unique:productos,nombre,' . $producto->id,
            'descripcion'  => 'nullable|string',
            'categoria'    => 'nullable|string|max:100',
            'precio'       => 'required|integer|min:0',
            'stock'        => 'required|integer|min:0',
            'estado'       => 'required|in:activo,inactivo',
            'proveedor_nombre' => 'nullable|string|max:255',
        ], [
            'nombre.unique' => 'Ya existe otro producto registrado con este mismo nombre.',
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
        unset($data['proveedor_nombre']);
        $data['proveedor_id'] = $proveedor_id;

        $producto->update($data);

        // Registrar Historial
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

        // Registrar Historial
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
            'archivo_csv.mimes'    => 'El archivo debe tener formato .csv o .txt.',
        ]);

        $file = $request->file('archivo_csv');
        $handle = fopen($file->getRealPath(), "r");
        
        // Saltamos la primera fila de cabeceras
        fgetcsv($handle, 1000, ",");
        
        $agregados = 0;
        $omitidos = 0;

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                if (empty(trim($row[0] ?? ''))) continue;

                $nombre = trim($row[0]);
                // Verificamos unicidad de nombre antes de insertar
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
                    'nombre'       => $nombre,
                    'descripcion'  => $row[1] ?? null,
                    'categoria'    => $row[2] ?? null,
                    'proveedor_id' => $proveedor_id,
                    'precio'       => is_numeric($row[4] ?? null) ? (int)$row[4] : 0,
                    'stock'        => is_numeric($row[5] ?? null) ? (int)$row[5] : 0,
                    'estado'       => in_array(strtolower(trim($row[6] ?? '')), ['activo', 'inactivo']) ? strtolower(trim($row[6])) : 'activo',
                ]);
                $agregados++;
            }
            fclose($handle);
            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('productos.index')
                ->with('success', "Importación completada: {$agregados} añadidos, {$omitidos} omitidos por nombre duplicado.");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            fclose($handle);
            return back()->withErrors(['error' => 'Error estructural al leer CSV. Asegúrese de que tenga el formato correcto: Nombre, Descripción, Categoría, Proveedor, Precio, Stock, Estado.']);
        }
    }
}
