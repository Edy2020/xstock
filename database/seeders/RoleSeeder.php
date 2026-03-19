<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $adminPermisos = [
            'productos.ver', 'productos.crear', 'productos.editar', 'productos.eliminar',
            'ventas.ver', 'ventas.crear', 'ventas.anular',
            'proveedores.ver', 'proveedores.crear', 'proveedores.editar', 'proveedores.eliminar',
            'estadisticas.ver', 'historial.ver', 'usuarios.gestionar', 'roles.gestionar',
        ];

        $vendedorPermisos = [
            'productos.ver', 'ventas.ver', 'ventas.crear', 'estadisticas.ver',
        ];

        $almacenPermisos = [
            'productos.ver', 'productos.crear', 'productos.editar',
            'proveedores.ver', 'proveedores.crear', 'proveedores.editar',
        ];

        $adminRole = Role::firstOrCreate(['nombre' => 'Administrador'], [
            'descripcion' => 'Acceso total al sistema. Puede gestionar usuarios, roles y configuración.',
            'permisos' => $adminPermisos,
        ]);

        Role::firstOrCreate(['nombre' => 'Vendedor'], [
            'descripcion' => 'Puede registrar ventas, ver productos y consultar clientes.',
            'permisos' => $vendedorPermisos,
        ]);

        Role::firstOrCreate(['nombre' => 'Almacén'], [
            'descripcion' => 'Gestiona el inventario: añadir, editar y ver productos y proveedores.',
            'permisos' => $almacenPermisos,
        ]);

        // Asegurar que haya un usuario vinculado al rol Admin (el primer usuario genérico)
        $user = User::first();
        if ($user) {
            $user->update([
                'role_id' => $adminRole->id,
                'estado' => 'activo'
            ]);
        }
    }
}
