<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';

    protected $fillable = [
        'user_id',
        'subtotal',
        'descuento_total',
        'total',
        'metodo_pago',
        'notas',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'descuento_total' => 'integer',
        'total' => 'integer',
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class , 'venta_id');
    }
    public function vendedor()
    {
        return $this->belongsTo(\App\Models\User::class , 'user_id');
    }

    public function getMetodoPagoFormatAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->metodo_pago));
    }
}
