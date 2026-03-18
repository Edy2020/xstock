<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogActividad extends Model
{
    protected $table = 'log_actividades';

    protected $fillable = [
        'user_id',
        'accion',
        'modulo',
        'detalle',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getBadgeClassAttribute()
    {
        return match($this->accion) {
            'Creación' => 'badge-blue',
            'Venta' => 'badge-green',
            'Actualización' => 'badge-yellow',
            'Eliminación' => 'badge-red',
            'Login' => 'badge-gray',
            default => 'badge-gray',
        };
    }
}
