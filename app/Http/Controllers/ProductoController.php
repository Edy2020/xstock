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

        $request->validate([
            'nombre'       => 'required|string|max:255',
            'categoria'    => 'nullable|string|max:100',
            'precio'       => 'required|integer|min:0',
            'stock'        => 'required|integer|min:0',
            'estado'       => 'required|in:activo,inactivo',
            'proveedor_id' => 'nullable|exists:proveedores,id',
        ], [
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.integer'  => 'El precio debe ser un número entero (CLP).',
            'stock.required'  => 'El stock es obligatorio.',
            'stock.integer'   => 'El stock debe ser un número entero.',
            'estado.required' => 'El estado es obligatorio.',
            'proveedor_id.exists' => 'El proveedor seleccionado no es válido.',
        ]);

        $producto = Producto::create($request->only('nombre', 'categoria', 'precio', 'stock', 'estado', 'proveedor_id'));

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
        return view('productos.show', compact('producto'));
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
            'nombre'       => 'required|string|max:255',
            'categoria'    => 'nullable|string|max:100',
            'precio'       => 'required|integer|min:0',
            'stock'        => 'required|integer|min:0',
            'estado'       => 'required|in:activo,inactivo',
            'proveedor_id' => 'nullable|exists:proveedores,id',
        ]);

        $producto->update($validated);

        // Registrar Historial
        LogActividad::create([
            'user_id' => auth()->id(),
            'accion' => 'Actualización',
            'modulo' => 'Productos',
            'detalle' => 'Actualizó el producto: ' . $producto->nombre,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('productos.edit', $producto->id)
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
            ->with('success', 'Producto "' . $nombre . '" eliminado.');
    }
}
