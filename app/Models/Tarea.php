<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'estado',
        'usuario_id',
        'fecha_vencimiento',
        'prioridad',
    ];

    protected $casts = [
        'fecha_vencimiento' => 'datetime',
    ];

    // Relación con usuario (quién la creó o a quién está asignada)
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
