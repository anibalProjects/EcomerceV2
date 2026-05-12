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

    public function getMuebles(): array
    {
        $response = Http::acceptJson()
            ->timeout(10)
            ->get($this->baseUrl . '/muebles');

        return [
            'estado' => $response->status(),
            'datos' => $response->json(),
        ];
    }

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

    public function reduceStock(int $id, int $cantidad): array
    {
        $response = Http::acceptJson()
            ->timeout(10)
            ->post($this->baseUrl . '/muebles/' . $id . '/reduce-stock', [
                'cantidad' => $cantidad,
            ]);

        return [
            'estado' => $response->status(),
            'datos' => $response->json(),
        ];
    }

    public function getMuebleById(int $id, ?string $token = null): array
    {
        $request = Http::acceptJson()->timeout(10);
        if ($token) {
            $request = $request->withToken($token);
        }
        $response = $request->get($this->baseUrl . '/muebles/' . $id);

        return [
            'estado' => $response->status(),
            'datos' => $response->json(),
        ];
    }

    public function storeMueble(array $data, string $token): array
    {
        $response = Http::withToken($token)
            ->acceptJson()
            ->post($this->baseUrl . '/muebles', $data);

        return [
            'estado' => $response->status(),
            'datos' => $response->json(),
        ];
    }

    public function updateMueble(int $id, array $data, string $token): array
    {
        $response = Http::withToken($token)
            ->acceptJson()
            ->put($this->baseUrl . '/muebles/' . $id, $data);

        return [
            'estado' => $response->status(),
            'datos' => $response->json(),
        ];
    }

    public function deleteMueble(int $id, string $token): array
    {
        $response = Http::withToken($token)
            ->acceptJson()
            ->delete($this->baseUrl . '/muebles/' . $id);

        return [
            'estado' => $response->status(),
            'datos' => $response->json(),
        ];
    }
}