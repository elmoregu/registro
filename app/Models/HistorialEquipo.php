<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialEquipo extends Model
{
    protected $fillable = ['equipo_id', 'usuario_id', 'fecha', 'accion'];

    //public $timestamps = false; // No necesitamos created_at ni updated_at

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
