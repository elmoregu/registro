<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class InformeController extends Controller
{
    public function exportarPdf(Request $request)
    {
        $uf = app(\App\Services\UfService::class)->getValorUf();

        // Filtrar por empresa si se selecciona una
        $empresaId = $request->input('empresa_id');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $asistencias = DB::table('asistencias')
            ->join('valor_asistencias', 'valor_asistencias.id', '=', 'asistencias.valor_asistencias_id')
            ->join('empresas', 'empresas.id', '=', 'valor_asistencias.empresa_id')
            ->join('usuarios', 'usuarios.id', '=', 'asistencias.usuario_id')
            ->when($empresaId, function ($query, $empresaId) {
                return $query->where('empresas.id', $empresaId);
            })
            ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                return $query->whereBetween('asistencias.fecha', [$fechaInicio, $fechaFin]);
            })
            ->select(
                'empresas.id as empresa_id',
                'asistencias.id as asistencia_id',
                'usuarios.nombre as usuario',
                'valor_asistencias.tipo as tipo_asistencia',
                DB::raw('valor_asistencias.valor * ' . $uf . ' as monto_clp'),
                'asistencias.fecha',
                'asistencias.descripcion as asunto',
                'empresas.razon_social as empresa'
            )
            ->groupBy(
                'empresas.id',
                'asistencias.id',
                'usuarios.nombre',
                'valor_asistencias.tipo',
                'asistencias.fecha',
                'asistencias.descripcion',
                'empresas.razon_social'
            )
            ->orderBy('asistencias.id')
            ->get();

        // Generar el PDF
        $pdf = Pdf::loadView('pdf.informe-asistencias-detallado', compact('asistencias'));

        // Descargar el PDF
        return $pdf->download('informe-asistencias.pdf');
    }
}
