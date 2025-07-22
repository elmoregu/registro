<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $fillable = [
        'fecha',
        'descripcion',
        'notas',
        'usuario_id',
        'equipo_id',
        'valor_asistencias_id'
    ];

    protected $table = 'asistencias';

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function valorAsistencia()
    {
        return $this->belongsTo(ValorAsistencia::class, 'valor_asistencias_id');
    }


    protected static function boot()
    {
        parent::boot();
        static::updated(function ($asistencia) {
            if ($asistencia->isDirty('status') && $asistencia->status === 'Pagado') {
                event('statusUpdated');
            }
        });
    }
}
