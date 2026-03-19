<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recordatorio extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titulo',
        'descripcion',
        'fecha',
        'color',
        'completado',
        'notificado',
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'completado' => 'boolean',
        'notificado' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
