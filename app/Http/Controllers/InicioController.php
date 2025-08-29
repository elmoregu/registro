<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InicioController extends Controller
{
    public function index()
    {
        $asistencias = collect();

        return view('inicio', compact('asistencias'));
    }

    public function buscarAsistencia(Request $request)
    {
        $empresa = $request->input('empresa');
        $ticket = $request->input('ticket');

        // Realizar la búsqueda en la base de datos
        $asistencias = \Illuminate\Support\Facades\DB::table('asistencias')
            ->join('valor_asistencias', 'valor_asistencias.id', '=', 'asistencias.valor_asistencias_id')
            ->join('empresas', 'empresas.id', '=', 'valor_asistencias.empresa_id')
            ->where('empresas.razon_social', $empresa)
            ->where('asistencias.id', $ticket)
            ->select(
                'asistencias.id',
                'asistencias.fecha',
                'asistencias.notas',
                'asistencias.status',
                'asistencias.created_at',
                'asistencias.descripcion',
                'valor_asistencias.valor',
                'empresas.razon_social',
            )
            ->get();

        return view('inicio', compact('asistencias'));
    }
}
