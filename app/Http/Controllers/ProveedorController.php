<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $query = Proveedor::withCount('productos');

        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%')
                  ->orWhere('ruc', 'like', '%' . $request->buscar . '%')
                  ->orWhere('contacto', 'like', '%' . $request->buscar . '%');
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $proveedores = $query->latest()->get();

        return view('proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:255',
            'ruc'       => 'nullable|string|max:50',
            'contacto'  => 'nullable|string|max:255',
            'telefono'  => 'nullable|string|max:30',
            'email'     => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:500',
            'notas'     => 'nullable|string',
            'estado'    => 'required|in:activo,inactivo',
        ], [
            'nombre.required' => 'El nombre del proveedor es obligatorio.',
            'email.email'     => 'El email no tiene un formato válido.',
        ]);

        Proveedor::create($request->only(
            'nombre', 'ruc', 'contacto', 'telefono', 'email', 'direccion', 'notas', 'estado'
        ));

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor "' . $request->nombre . '" añadido correctamente.');
    }

    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'nombre'    => 'required|string|max:255',
            'ruc'       => 'nullable|string|max:50',
            'contacto'  => 'nullable|string|max:255',
            'telefono'  => 'nullable|string|max:30',
            'email'     => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:500',
            'notas'     => 'nullable|string',
            'estado'    => 'required|in:activo,inactivo',
        ]);

        $proveedor->update($request->only(
            'nombre', 'ruc', 'contacto', 'telefono', 'email', 'direccion', 'notas', 'estado'
        ));

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Proveedor $proveedor)
    {
        // Quita la relación antes de eliminar
        $proveedor->productos()->update(['proveedor_id' => null]);
        $nombre = $proveedor->nombre;
        $proveedor->delete();

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor "' . $nombre . '" eliminado.');
    }
}
