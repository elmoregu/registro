<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\UsuarioObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

//#[ObservedBy([UsuarioObserver::class])]
class Usuario extends Model
{
    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'url_img',
        'empresa_id'
    ];

    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }

    public function equipos(){
        return $this->hasMany(Equipo::class);
    }

    public function asistencias(){
        return $this->hasMany(Asistencia::class);
    }

    public function perteneceAequipos(){
        return $this->belongsTo(Equipo::class);
    }


}
