<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InformeController extends Controller
{
    public function asistenciasPorCobrar()
    {
        // Consulta para obtener las asistencias "Por Cobrar" agrupadas por empresa
        $informes = DB::table('asistencias')
            ->join('valor_asistencias', 'valor_asistencias.id', '=', 'asistencias.valor_asistencias_id')
            ->join('empresas', 'empresas.id', '=', 'valor_asistencias.empresa_id')
            ->where('asistencias.status', 'Cobrar')
            ->select(
                'empresas.razon_social as empresa',
                DB::raw('COUNT(asistencias.id) as total_asistencias'),
                DB::raw('SUM(valor_asistencias.valor) as total_valor')
            )
            ->groupBy('empresas.razon_social')
            ->get();

        // Retornar la vista con los datos
        return view('informes.asistencias', compact('informes'));
    }
}
