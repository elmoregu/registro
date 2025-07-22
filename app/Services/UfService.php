<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UfService
{
    public function getValorUf(): ?float
    {
        try {
            $response = Http::get('https://mindicador.cl/api/uf');
            $data = $response->json();

            if (isset($data['serie']) && is_array($data['serie']) && count($data['serie']) > 0) {
                // La API devuelve una serie de valores. Tomamos el primero (el más reciente)
                return $data['serie'][0]['valor'] ?? null;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error al obtener el valor de la UF desde mindicador.cl: ' . $e->getMessage());
            return null;
        }
    }
}
