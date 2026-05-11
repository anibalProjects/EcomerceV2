<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FurnitureServices
{
    protected string $baseUrl;

    public function __construct()
    {
        // obtenemos la URL del servicio de API http://localhost:8001/api
        $this->baseUrl = rtrim(config('services.remote_furniture_api.base_url'), '/');
    }

    // Definimos el servicio de Login para acceder a la otra API
    public function getMueblesByIds(array $ids): array
    {
        $response = Http::acceptJson()
            ->timeout(10)
            ->get($this->baseUrl . '/muebles-lista', [
                'ids' => $ids,
            ]);

        return [
            'estado' => $response->status(),
            'datos' => $response->json(),
        ];
    }

}