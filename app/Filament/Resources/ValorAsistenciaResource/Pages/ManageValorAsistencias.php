<?php

namespace App\Filament\Resources\ValorAsistenciaResource\Pages;

use App\Filament\Resources\ValorAsistenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageValorAsistencias extends ManageRecords
{
    protected static string $resource = ValorAsistenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
