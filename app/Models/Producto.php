<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::saving(function ($producto) {
            if ($producto->stock <= 0) {
                $producto->estado = 'inactivo';
            }
        });
    }

    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'categoria',
        'precio',
        'stock',
        'estado',
        'proveedor_id',
    ];

    protected $casts = [
        'precio' => 'integer',
        'stock' => 'integer',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class , 'proveedor_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
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
