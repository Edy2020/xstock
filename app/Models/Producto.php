<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

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
        'stock'  => 'integer',
    ];

    // Relación con proveedor
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    // Scope para productos activos
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    // Accessor: badge CSS según estado
    public function getBadgeEstadoAttribute(): string
    {
        return $this->estado === 'activo' ? 'badge-green' : 'badge-gray';
    }

    // Accessor: etiqueta legible de estado
    public function getLabelEstadoAttribute(): string
    {
        return $this->estado === 'activo' ? 'Activo' : 'Inactivo';
    }
}
