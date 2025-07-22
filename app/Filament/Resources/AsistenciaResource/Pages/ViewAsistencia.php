<?php

namespace App\Filament\Resources\AsistenciaResource\Pages;

use App\Filament\Resources\AsistenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\view;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms;
use App\Models\Asistencia;
use App\Models\ValorAsistencia;
use Illuminate\Support\Facades\Log;

class ViewAsistencia extends ViewRecord
{
    protected static string $resource = AsistenciaResource::class;
    public function mount($record): void
    {
        parent::mount($record);
        Log::info('Usando la vista personalizada ViewAsistencia');
    }

    protected function getViewData(): array
    {
        return [
            'usuarioooo' => $this->record->usuario->name ?? 'No definido',
            //'empresa' => $this->record->usuario->empresa->nombre ?? 'No definido',
            //'equipo' => $this->record->equipo->nombre ?? 'No definido',
            //'descripcion' => $this->record->descripcion ?? 'No definido',
            'tipo' => $this->record->tipo == 1 ? 'Remota' : 'Presencial',
            'monto' => ValorAsistencia::where('empresa_id', $this->record->usuario->empresa_id ?? null)
                ->where('tipo', $this->record->tipo)
                ->value('valor') ?? 'No definido',
        ];
    }
}
