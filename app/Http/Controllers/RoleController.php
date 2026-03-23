<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\LogActividad;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->orderBy('id')->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
        ]);

        $validated['permisos'] = $request->input('permisos', []);
        $role = Role::create($validated);

        LogActividad::create([
            'user_id' => auth()->id(),
            'accion' => 'Creación',
            'modulo' => 'Roles',
            'detalle' => 'Creó un nuevo rol y configuró sus permisos: ' . $role->nombre,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('roles.index')->with('success', 'Rol creado exitosamente.');
    }

    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    public function show(Role $role)
    {
        return view('roles.show', compact('role'));
    }

    public function updateBulk(Request $request)
    {
        $data = $request->input('roles', []);
        
        foreach (Role::all() as $role) {
            $permisos = $data[$role->id]['permisos'] ?? [];
            $role->permisos = $permisos;
            $role->save();
        }

        LogActividad::create([
            'user_id' => auth()->id(),
            'accion' => 'Actualización',
            'modulo' => 'Roles',
            'detalle' => 'Actualizó masivamente los permisos de los roles del sistema',
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('roles.index')->with('success', 'Matriz de permisos guardada exitosamente.');
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
        ]);
        
        if ($role->id !== 1) { // Protege el nombre del admin si se envía modificado por error
            $role->nombre = $validated['nombre'];
        }
        $role->descripcion = $validated['descripcion'];

        // Permisos
        if ($role->id !== 1) {
            $permisos = $request->input('permisos', []);
            $role->permisos = $permisos;
        }
        $role->save();

        LogActividad::create([
            'user_id' => auth()->id(),
            'accion' => 'Actualización',
            'modulo' => 'Roles',
            'detalle' => 'Actualizó datos y permisos del rol: ' . $role->nombre,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('roles.index')->with('success', 'Rol y permisos actualizados correctamente.');
    }

    public function destroy(Role $role)
    {
        if ($role->id === 1) {
            return back()->with('error', 'No se puede eliminar el rol de Administrador.');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', 'No se puede eliminar el rol porque tiene usuarios asignados.');
        }

        $nombre = $role->nombre;
        $role->delete();

        LogActividad::create([
            'user_id' => auth()->id(),
            'accion' => 'Eliminación',
            'modulo' => 'Roles',
            'detalle' => 'Eliminó el rol: ' . $nombre,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('roles.index')->with('success', 'Rol eliminado exitosamente.');
    }
}
