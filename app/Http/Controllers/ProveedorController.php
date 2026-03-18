<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\LogActividad;
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
        abort_unless(auth()->user()->hasPermission('proveedores.crear'), 403, 'No tienes permiso para crear proveedores.');
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasPermission('proveedores.crear'), 403, 'No tienes permiso para crear proveedores.');

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
        abort_unless(auth()->user()->hasPermission('proveedores.editar'), 403, 'No tienes permiso para editar proveedores.');
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        abort_unless(auth()->user()->hasPermission('proveedores.editar'), 403, 'No tienes permiso para editar proveedores.');

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
        abort_unless(auth()->user()->hasPermission('proveedores.eliminar'), 403, 'No tienes permiso para eliminar proveedores.');

        // Quita la relación antes de eliminar
        $proveedor->productos()->update(['proveedor_id' => null]);
        $nombre = $proveedor->nombre;
        $proveedor->delete();

        // Registrar Historial
        LogActividad::create([
            'user_id' => auth()->id(),
            'accion' => 'Eliminación',
            'modulo' => 'Proveedores',
            'detalle' => 'Eliminó al proveedor: ' . $nombre,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor "' . $nombre . '" eliminado.');
    }
}
