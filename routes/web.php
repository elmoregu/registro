<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Resources\AsistenciaResource\Pages\FiltrarAsistencias;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\InformeController;


Route::get('/', [InicioController::class, 'index'])->name('inicio');
Route::post('/buscar-asistencia', [InicioController::class, 'buscarAsistencia'])->name('buscarAsistencia');


Route::get('/informes/asistencias', [InformeController::class, 'asistenciasPorCobrar'])->name('informes.asistencias');

Route::get('/exportar-informe-pdf', [InformeController::class, 'exportarPdf'])->name('exportar-informe-pdf');

Route::get('/exportar-informe-pdf', [InformeController::class, 'exportarPdf'])->name('exportar-informe-pdf');

//Route::get('/filtrar-asistencias', FiltrarAsistencias::class)->name('filtrar-asistencias');<?php
