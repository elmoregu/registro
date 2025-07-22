<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValorAsistencia extends Model
{
    protected $fillable = [
        'empresa_id',
        'tipo',
        'valor',
        'moneda',
    ];

    protected $table = 'valor_asistencias';

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function empresas()
    {
        return $this->hasMany(Empresa::class);
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }
}
