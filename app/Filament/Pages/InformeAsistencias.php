<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Asistencia;
use App\Models\ValorAsistencia;
use App\Models\Empresa;
use App\Models\Usuario;
use App\Models\Equipo;
use App\Filament\Resources\InformeResource;
use App\Services\UfService;

use Illuminate\Support\Facades\DB;

class InformeAsistencias extends Page
{

    protected static ?string $navigationGroup = 'Informes';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Informe de Asistencias';
    protected static ?string $slug = 'informes/asistencias';
    protected static ?string $title = 'Informe de Asistencias por Cobrar';

    public $informes;

    public function mount()
    {
        $uf = app(UfService::class)->getValorUf();
        // Consulta para obtener las asistencias "Por Cobrar" agrupadas por empresa
        $this->informes = DB::table('asistencias')
            ->join('valor_asistencias', 'valor_asistencias.id', '=', 'asistencias.valor_asistencias_id')
            ->join('empresas', 'empresas.id', '=', 'valor_asistencias.empresa_id')
            ->where('asistencias.status', 'Cobrar')
            ->select(
                'empresas.razon_social as empresa',
                DB::raw('COUNT(asistencias.id) as total_asistencias'),
                DB::raw('SUM(valor_asistencias.valor) * ' . $uf . ' as total_valor')
            )
            ->groupBy('empresas.razon_social')
            ->get();
    }

    protected static string $view = 'filament.resources.informe-resource.pages.informe-asistencias';
}
