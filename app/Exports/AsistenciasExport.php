<?php

namespace App\Exports;

use App\Models\Asistencia;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AsistenciasExport implements FromQuery, WithHeadings
{
    use Exportable;

    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = Asistencia::query();

        // Aplica los filtros
        if (isset($this->filters['empresa'])) {
            $query->whereHas('usuario', function ($q) {
                $q->where('empresa_id', $this->filters['empresa']);
            });
            if (isset($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        return $query;
        }
    }


    public function headings(): array
    {
        return [
            'ID',
            'Usuario ID',
            'Empresa ID',
            'Equipo ID',
            'Valor Asistencia ID',
            'Fecha',
            'Hora Entrada',
            'Hora Salida',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Asistencia::all();
    }
}
