<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Mueble;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Setup base data
        Category::create(['nombre' => 'Sofas']);
    }

    public function test_admin_can_create_mueble()
    {
        Http::fake([
            '*/validate-token?ability=muebles.crear' => Http::response(['valid' => true], 200),
        ]);

        $response = $this->withToken('admin-token')->postJson('/api/muebles', [
            'nombre' => 'Sofa Test',
            'descripcion' => 'Desc Test',
            'precio' => 100,
            'stock' => 10,
            'categoria_id' => 1,
            'color' => 'Red',
        ]);

        $response->assertStatus(201);
    }

    public function test_gestor_can_create_mueble()
    {
        // En el middleware, si la primera ability falla, intenta la segunda.
        // Simulamos que muebles.crear falla pero gestor.muebles.crear tiene éxito.
        Http::fake([
            '*/validate-token?ability=muebles.crear' => Http::response(['error' => 'Forbidden'], 403),
            '*/validate-token?ability=gestor.muebles.crear' => Http::response(['valid' => true], 200),
        ]);

        $response = $this->withToken('gestor-token')->postJson('/api/muebles', [
            'nombre' => 'Sofa Test Gestor',
            'descripcion' => 'Desc Test',
            'precio' => 100,
            'stock' => 10,
            'categoria_id' => 1,
            'color' => 'Blue',
        ]);

        $response->assertStatus(201);
    }

    public function test_cliente_cannot_create_mueble()
    {
        Http::fake([
            '*/validate-token*' => Http::response(['error' => 'Forbidden'], 403),
        ]);

        $response = $this->withToken('cliente-token')->postJson('/api/muebles', [
            'nombre' => 'Sofa Test Cliente',
            'descripcion' => 'Desc Test',
            'precio' => 100,
            'stock' => 10,
            'categoria_id' => 1,
            'color' => 'Green',
        ]);

        $response->assertStatus(403);
        $response->assertJsonFragment(['mensaje' => 'Acceso denegado']);
    }

    public function test_unauthenticated_request_is_rejected()
    {
        $response = $this->postJson('/api/muebles', [
            'nombre' => 'Sofa Test Unauth',
        ]);

        $response->assertStatus(401);
        $response->assertJsonFragment(['error' => 'No puede acceder a esta zona de la web']);
    }
}
