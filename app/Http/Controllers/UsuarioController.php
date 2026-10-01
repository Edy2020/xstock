<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\LogActividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $usuarios = $query->paginate(20);
        $roles = Role::orderBy('nombre')->get();

        return view('usuarios.index', compact('usuarios', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', Rules\Password::defaults()],
            'role_id' => ['required', 'exists:roles,id'],
            'estado' => ['required', 'in:activo,inactivo'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'estado' => $request->estado,
        ]);

        LogActividad::create([
            'user_id' => auth()->id(),
            'accion' => 'Creación',
            'modulo' => 'Usuarios',
            'detalle' => 'Creó usuario: ' . $user->name,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $usuario->id],
            'role_id' => ['required', 'exists:roles,id'],
            'estado' => ['required', 'in:activo,inactivo'],
        ]);

        $pierdeAdmin = $usuario->role_id === Role::ADMIN_ID
            && ((int) $request->role_id !== Role::ADMIN_ID || $request->estado === 'inactivo');

        if ($usuario->id === auth()->id() && ((int) $request->role_id !== $usuario->role_id || $request->estado === 'inactivo')) {
            return back()->withErrors(['error' => 'No puedes cambiar tu propio rol ni desactivar tu propia cuenta.'])->withInput();
        }

        if ($pierdeAdmin && $this->esUltimoAdmin($usuario)) {
            return back()->withErrors(['error' => 'Debe existir al menos un Administrador activo en el sistema.'])->withInput();
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'estado' => $request->estado,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => ['required', Rules\Password::defaults()]]);
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        LogActividad::create([
            'user_id' => auth()->id(),
            'accion' => 'Actualización',
            'modulo' => 'Usuarios',
            'detalle' => 'Modificó datos del usuario: ' . $usuario->name,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario modificado.');
    }

    public function destroy(User $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return redirect()->route('usuarios.index')->withErrors(['error' => 'No puedes eliminarte a ti mismo.']);
        }

        if ($usuario->role_id === Role::ADMIN_ID && $this->esUltimoAdmin($usuario)) {
            return redirect()->route('usuarios.index')->withErrors(['error' => 'No se puede eliminar al último Administrador activo.']);
        }

        $nombre = $usuario->name;
        $usuario->delete();

        LogActividad::create([
            'user_id' => auth()->id(),
            'accion' => 'Eliminación',
            'modulo' => 'Usuarios',
            'detalle' => 'Eliminó al usuario: ' . $nombre,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
    }

    private function esUltimoAdmin(User $usuario): bool
    {
        return !User::where('role_id', Role::ADMIN_ID)
            ->where('estado', 'activo')
            ->where('id', '!=', $usuario->id)
            ->exists();
    }
}
