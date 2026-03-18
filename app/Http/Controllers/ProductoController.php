<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with('proveedor');

        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('proveedor_id')) {
            $query->where('proveedor_id', $request->proveedor_id);
        }

        $productos = $query->latest()->get();

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

        Producto::create($request->only('nombre', 'categoria', 'precio', 'stock', 'estado', 'proveedor_id'));

        return redirect()->route('productos.index')
            ->with('success', 'Producto "' . $request->nombre . '" añadido correctamente.');
    }

    public function show(Producto $producto)
    {
        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
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
        $request->validate([
            'nombre'       => 'required|string|max:255',
            'categoria'    => 'nullable|string|max:100',
            'precio'       => 'required|integer|min:0',
            'stock'        => 'required|integer|min:0',
            'estado'       => 'required|in:activo,inactivo',
            'proveedor_id' => 'nullable|exists:proveedores,id',
        ]);

        $producto->update($request->only('nombre', 'categoria', 'precio', 'stock', 'estado', 'proveedor_id'));

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $nombre = $producto->nombre;
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto "' . $nombre . '" eliminado.');
    }
}
