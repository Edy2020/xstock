<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // Rol de Administrador principal: siempre tiene acceso total
    public const ADMIN_ID = 1;

    public const PERMISOS = [
        'productos.ver', 'productos.crear', 'productos.editar', 'productos.eliminar',
        'ventas.ver', 'ventas.crear', 'ventas.anular',
        'proveedores.ver', 'proveedores.crear', 'proveedores.editar', 'proveedores.eliminar',
        'estadisticas.ver', 'historial.ver', 'usuarios.gestionar', 'roles.gestionar',
    ];

    protected $fillable = [
        'nombre',
        'descripcion',
        'permisos',
    ];

    protected $casts = [
        'permisos' => 'array',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function isAdmin(): bool
    {
        return $this->id === self::ADMIN_ID;
    }

    /**
     * Filtra una lista de permisos recibida del formulario dejando solo los válidos.
     */
    public static function sanitizePermisos($permisos): array
    {
        return array_values(array_intersect(self::PERMISOS, (array) $permisos));
    }
}
