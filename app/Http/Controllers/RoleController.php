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
        $permisos = $request->input('permisos', []);
        $role->permisos = $permisos;
        $role->save();

        LogActividad::create([
            'user_id' => auth()->id(),
            'accion' => 'Actualización',
            'modulo' => 'Roles',
            'detalle' => 'Actualizó permisos del rol: ' . $role->nombre,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('roles.index')->with('success', 'Permisos actualizados correctamente.');
    }
}
