<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gasto extends Model
{
    use HasFactory;

    protected $fillable = [
        'producto_id',
        'proveedor_id',
        'cantidad',
        'precio_compra',
        'total',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_compra' => 'integer',
        'total' => 'integer',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }
}
