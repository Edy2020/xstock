<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';

    protected $fillable = [
        'subtotal',
        'descuento_total',
        'total',
        'metodo_pago',
        'notas',
    ];

    protected $casts = [
        'subtotal'        => 'integer',
        'descuento_total' => 'integer',
        'total'           => 'integer',
    ];

    // Relación con los detalles
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'venta_id');
    }

    public function getMetodoPagoFormatAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->metodo_pago));
    }
}
