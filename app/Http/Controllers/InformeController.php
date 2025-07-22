<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class InformeController extends Controller
{
    public function exportarPdf()
    {
       $uf = app(\App\Services\UfService::class)->getValorUf();

        // Consulta para obtener las asistencias "Por Cobrar" agrupadas por empresa
        $informes = DB::table('asistencias')
            ->join('valor_asistencias', 'valor_asistencias.id', '=', 'asistencias.valor_asistencias_id')
            ->join('empresas', 'empresas.id', '=', 'valor_asistencias.empresa_id')
            ->where('asistencias.status', 'Cobrar')
            ->select(
                'empresas.razon_social as empresa',
                DB::raw('COUNT(asistencias.id) as total_asistencias'),
                DB::raw('SUM(valor_asistencias.valor) as total_valor'),
                DB::raw('SUM(valor_asistencias.valor) * ' . $uf . ' as total_en_pesos')
            )
            ->groupBy('empresas.razon_social')
            ->get();

        // Generar el PDF
        $pdf = Pdf::loadView('pdf.informe-asistencias', compact('informes'));

        // Descargar el PDF
        return $pdf->download('informe-asistencias.pdf');
    }
    
}
