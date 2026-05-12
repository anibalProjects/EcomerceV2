<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CategoryService
{
    protected string $baseUrl;

    public function __construct()
    {
        // obtenemos la URL del servicio de API http://localhost:8001/api
        $this->baseUrl = rtrim(config('services.remote_furniture_api.base_url'), '/');
    }

    // Definimos el servicio de Login para acceder a la otra API
    public function getCategories(): array
    {
        $response = Http::acceptJson()
            ->timeout(10)
            ->get($this->baseUrl . '/categorias');

        return [
            'estado' => $response->status(),
            'datos' => $response->json(),
        ];
    }

    public function getCategoryById(int $id): array
    {
        $response = Http::acceptJson()
            ->timeout(10)
            ->get($this->baseUrl . '/categorias/' . $id);

        return [
            'estado' => $response->status(),
            'datos' => $response->json(),
        ];
    }

    public function storeCategory(array $data, string $token): array
    {
        $response = Http::withToken($token)
            ->acceptJson()
            ->post($this->baseUrl . '/categorias', $data);

        return [
            'estado' => $response->status(),
            'datos' => $response->json(),
        ];
    }

    public function updateCategory(int $id, array $data, string $token): array
    {
        $response = Http::withToken($token)
            ->acceptJson()
            ->put($this->baseUrl . '/categorias/' . $id, $data);

        return [
            'estado' => $response->status(),
            'datos' => $response->json(),
        ];
    }

    public function deleteCategory(int $id, string $token): array
    {
        $response = Http::withToken($token)
            ->acceptJson()
            ->delete($this->baseUrl . '/categorias/' . $id);

        return [
            'estado' => $response->status(),
            'datos' => $response->json(),
        ];
    }
}