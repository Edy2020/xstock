<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('nombre', 'Administrador')->first();
        $vendedorRole = Role::where('nombre', 'Vendedor')->first();

        User::updateOrCreate(
        ['email' => 'admin@xstock.cl'],
        [
            'name' => 'XStock',
            'password' => Hash::make('admin1234'),
            'role_id' => $adminRole ? $adminRole->id : null,
            'estado' => 'activo',
        ]
        );

        User::updateOrCreate(
        ['email' => 'vendedor@xstock.cl'],
        [
            'name' => 'Vendedor',
            'password' => Hash::make('admin1234'),
            'role_id' => $vendedorRole ? $vendedorRole->id : null,
            'estado' => 'activo',
        ]
        );
    }
}
