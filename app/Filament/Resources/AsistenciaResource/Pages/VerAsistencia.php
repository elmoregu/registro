<?php

namespace App\Filament\Resources\AsistenciaResource\Pages;

use App\Filament\Resources\AsistenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use App\Services\UfService;

class VerAsistencia extends ViewRecord
{
    protected static string $resource = AsistenciaResource::class;
    //protected static ?string $view = 'filament.resources.asistencia-resource.pages.ver-asistencia';
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make(4) // Define un grid de 2 columnas
                    ->schema([
                        TextEntry::make('usuario.nombre')->label('Usuario: '),
                        TextEntry::make('usuario.empresa.razon_social')->label('Empresa'),
                        TextEntry::make('valorAsistencia.valor')->label('Cant. UF')
                            ->prefix('$'),
                        TextEntry::make('valor_uf')
                            ->label('Valor UF: ')
                            ->state(fn() => app(\App\Services\UfService::class)->getValorUf())
                            ->prefix('$'),
                        TextEntry::make('valor_calculado') // Añade el nuevo TextEntry
                            ->label('Costo Asistencia: ')
                            ->state(function ($record): ?string {
                                $ufService = app(UfService::class);
                                $valorUf = $ufService->getValorUf();
                                $valorAsistencia = $record->valorAsistencia->valor ?? 0;

                                if ($valorUf !== null) {
                                    return '$ ' . number_format(round($valorAsistencia * $valorUf), 0, ',', '.');
                                }

                                return null;
                            }),
                        //->format('2f'),
                        TextEntry::make('valorAsistencia.tipo')->label('Tipo'),
                        TextEntry::make('fecha')->label('Fecha')
                            ->date(),
                        TextEntry::make('status')->label('Estado')
                            ->badge()
                            ->color(fn($record) => $record->status == 'Pagado' ? 'success' : ($record->status == 'Cobrar' ? 'danger' : 'warning'))
                            ->icon(fn($record) => $record->status == 'Pagado' ? 'heroicon-o-check-circle' : ($record->status == 'Cobrar' ? 'heroicon-o-x-circle' : 'heroicon-o-exclamation-triangle')),
                    ]),
                TextEntry::make('descripcion')->label('Descripción:')
                    ->state(function ($record): string {
                        return empty($record->descripcion) ? 'Asistencia de Equipo' : $record->descripcion;
                    })

                    ->columnSpanFull() // Ocupa todo el ancho

                ,
                TextEntry::make('notas')->label('Notas')
                    ->columnSpanFull() // Ocupa todo el ancho
                    ->markdown()
                    ->html(),
            ]);
    }
}
