<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';

    protected $fillable = [
        'nombre',
        'ruc',
        'contacto',
        'telefono',
        'email',
        'direccion',
        'notas',
        'estado',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class , 'proveedor_id');
    }

    public function getBadgeEstadoAttribute(): string
    {
        return $this->estado === 'activo' ? 'badge-green' : 'badge-gray';
    }

    public function getLabelEstadoAttribute(): string
    {
        return $this->estado === 'activo' ? 'Activo' : 'Inactivo';
    }
}
