<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Filament\Notifications\Notification;
use Livewire\Livewire;
use Livewire\Component;
use Illuminate\Support\Facades\Event;


class Equipo extends Model
{
    use HasFactory;
    protected $fillable = [
        'serie',
        'descripcion',
        'procesador',
        'ram',
        'sistema',
        'anydesk',
        'obs',
        'usuario_id',
        'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function usuarios(){
        return $this->hasMany(Usuario::class);
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }

    public function historial()
    {
        return $this->hasMany(HistorialEquipo::class);
    }



    protected static function boot()
    {
        parent::boot();

        // Cuando se crea un equipo nuevo, se guarda en el historial
        static::created(function ($equipo) {
            HistorialEquipo::create([
                'equipo_id' => $equipo->id,
                'usuario_id' => $equipo->usuario_id,
                'fecha' => now(),
                'accion' => 'Creado',
            ]);
            Event::dispatch('historialUpdate');
            //Event::dispatch('historialCreated');
        });

        static::updating(function ($equipo) {
            if ($equipo->isDirty('usuario_id')) {
                HistorialEquipo::create([
                    'equipo_id' => $equipo->id,
                    'usuario_id' => $equipo->usuario_id,
                    'fecha' => now(),
                    'accion' => 'Actualizado',
                ]);
            }
            Event::dispatch('historialUpdated');
            //Event::dispatch('historialUpdated');
        });

        // Emitir un evento de Livewire para actualizar el Relation Manager

    }
}
