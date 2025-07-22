<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    //
    protected $fillable = [
        'razon_social',
        'rut',
        'direccion',
        'telefono',
        'correo'
    ];

    public function usuarios(){
        return $this->hasMany(Usuario::class);
    }

    public function valorAsistencias(){
        return $this->hasMany(valorAsistencia::class);
    }


}
