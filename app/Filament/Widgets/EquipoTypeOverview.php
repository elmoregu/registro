<?php

namespace App\Filament\Widgets;

use App\Models\Asistencia;
use App\Models\Empresa;
use App\Models\Equipo;
use App\Models\Usuario;
use App\Models\ValorAsistencia;
use App\Services\UfService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class EquipoTypeOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $cantUf = DB::table('asistencias as a')
            ->join('valor_asistencias as va', 'a.valor_asistencias_id', '=', 'va.id')
            ->where('a.status', 'Cobrar')
            ->sum('va.valor');

        $valorUf = app(UfService::class)->getValorUf();
        $valor = $valorUf * $cantUf;

        return [
            Stat::make('Equipos:', Equipo::all()->count()),
            Stat::make('Usuarios:', Usuario::count()),
            Stat::make('Empresas:', Empresa::count()),
            Stat::make('Asistencias:', Asistencia::count()),
            Stat::make('Por Cobrar:', "$ " . number_format($valor, 0, ',', '.'))
                ->color('warning')
                ->icon('heroicon-o-exclamation-triangle')
            //Stat::make('Dogs', Patient::query()->where('type', 'dog')->count()),
            //Stat::make('Rabbits', Patient::query()->where('type', 'rabbit')->count()),
        ];
    }
}
