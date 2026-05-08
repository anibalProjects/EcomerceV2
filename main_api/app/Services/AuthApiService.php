<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AuthApiService
{
    protected string $baseUrl;

    public function __construct()
    {
        // obtenemos la URL del servicio de API http://localhost:8002/api
        $this->baseUrl = rtrim(config('services.remote_auth_api.base_url'), '/');
    }

    // Definimos el servicio de Login para acceder a la otra API
    public function login(array $data): array
    {
        $response = Http::acceptJson()
            ->timeout(10)
            ->post($this->baseUrl . '/login', [
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

        return [
            'estado' => $response->status(),
            'datos' => $response->json(),
        ];
    }

    // Definimos el servicio de Registrar Usuario desde la otra API
    public function registrar(array $data): array
    {
        $response = Http::acceptJson()
            ->timeout(10)
            ->post($this->baseUrl . '/register', [
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

        return [
            'estado' => $response->status(),
            'datos' => $response->json(),
        ];
    }

    // Definimos el servicio de Perfil de Usuario pasandole previamente el TOKEN de validación.
    public function perfil(string $token): array
    {
        $response = Http::acceptJson()
            ->withToken($token)
            ->timeout(10)
            ->get($this->baseUrl . '/perfil');

        return [
            'estado' => $response->status(),
            'datos' => $response->json(),
        ];
    }

      // Definimos el servicio de cerrar sesión de Usuario pasandole previamente el TOKEN de validación.
    public function logout(string $token): array
    {
        $response = Http::acceptJson()
            ->withToken($token)
            ->timeout(10)
            ->post($this->baseUrl . '/logout');

        return [
            'estado' => $response->status(),
            'datos' => $response->json(),
        ];
    }
}