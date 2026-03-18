<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalle_ventas';

    protected $fillable = [
        'venta_id',
        'producto_id',
        'producto_nombre',
        'cantidad',
        'precio_unitario',
        'descuento_porcentaje',
        'subtotal',
    ];

    protected $casts = [
        'cantidad'             => 'integer',
        'precio_unitario'      => 'integer',
        'descuento_porcentaje' => 'integer',
        'subtotal'             => 'integer',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
