<?php

namespace App\Http\Controllers;
use App\Http\Controllers\CookiePersonalizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class MuebleController extends Controller
{

    protected $carpetaPrivadaPrincipal = 'imagenes/pagprincipal';
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, \App\Services\FurnitureServices $furnitureService, \App\Services\CategoryService $categoryService)
    {
        $params = $request->only(['page', 'per_page', 'orden']);
        
        // Consumimos la API de Muebles usando el servicio
        $responseMuebles = $furnitureService->getMuebles($params);
        $datos = $responseMuebles['datos'];
        
        $mueblesData = collect($datos['data'] ?? [])->map(fn($item) => (object)$item);

        if (isset($datos['meta'])) {
            $muebles = new LengthAwarePaginator(
                $mueblesData,
                $datos['meta']['total'],
                $datos['meta']['per_page'],
                $datos['meta']['current_page'],
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $muebles = $mueblesData;
        }

        // Consumimos la API de Categorías usando el servicio
        $responseCategorias = $categoryService->getCategories();
        $categorias = collect($responseCategorias['datos']['data'] ?? [])->map(fn($item) => (object)$item);

        $usuario = Session::get('usuario_logueado');
        $usuario = $usuario ? (object) $usuario : null;
        $preferencias = CookiePersonalizacion::getPersonalizacion(null, $usuario->id ?? null);
        $tema = $preferencias['tema'];
        $moneda = $preferencias['moneda'];
        
        return view('home', compact('muebles', 'categorias', 'usuario', 'tema', 'moneda'));
    }

    public function getGaleria()
    {
        // Implementar si es necesario
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request, \App\Services\FurnitureServices $furnitureService)
    {

        // Pedimos el mueble a la API usando el servicio
        $response = $furnitureService->getMuebleById($id);

        if ($response['estado'] !== 200) {
            abort(404, 'Mueble no encontrado en la API');
        }
        $mueble = (object) ($response['datos']['data'] ?? $response['datos']);

        $usuario = Session::get('usuario_logueado');
        $usuario = $usuario ? (object) $usuario : null;
        $preferencias = CookiePersonalizacion::getPersonalizacion(null, $usuario->id ?? null);
        $tema = $preferencias['tema'];
        $moneda = $preferencias['moneda'];

        return view('showMueble', compact('mueble', /* 'productosRelacionados' */'moneda', 'tema'));
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    public function filtrar(Request $request, \App\Services\FurnitureServices $furnitureService, \App\Services\CategoryService $categoryService)
    {
        $filtro = $request->input('filtro', []);
        $orden = $request->input('orden');

        // Mapear los nombres de los filtros a lo que espera la API
        $params = [
            'nombre' => $filtro['nombre'] ?? null,
            'categoria_id' => $filtro['categoria_id'] ?? null,
            'precio_min' => $filtro['precio_min'] ?? null,
            'precio_max' => $filtro['precio_max'] ?? null,
            'color' => $filtro['color'] ?? null,
            'novedad' => $filtro['novedad'] ?? null,
            'orden' => $orden,
            'page' => $request->input('page'),
        ];

        // Limpiar parámetros nulos
        $params = array_filter($params, fn($v) => $v !== null);

        try {
            $responseMuebles = $furnitureService->getMuebles($params);
            $datos = $responseMuebles['datos'];
            $mueblesData = collect($datos['data'] ?? [])->map(fn($item) => (object)$item);

            if (isset($datos['meta'])) {
                $muebles = new LengthAwarePaginator(
                    $mueblesData,
                    $datos['meta']['total'],
                    $datos['meta']['per_page'],
                    $datos['meta']['current_page'],
                    ['path' => $request->url(), 'query' => $request->query()]
                );
            } else {
                $muebles = $mueblesData;
            }
        } catch (\Exception $e) {
            $muebles = new LengthAwarePaginator(collect([]), 0, 12);
        }

        $responseCategorias = $categoryService->getCategories();
        $categorias = collect($responseCategorias['datos']['data'] ?? [])->map(fn($item) => (object)$item);

        $usuario = Session::get('usuario_logueado');
        $usuario = $usuario ? (object) $usuario : null;
        $preferencias = CookiePersonalizacion::getPersonalizacion(null, $usuario->id ?? null);
        $tema = $preferencias['tema'];
        $moneda = $preferencias['moneda'];

        return view('home', [
            'muebles' => $muebles,
            'categorias' => $categorias,
            'filtro' => $filtro,
            'orden' => $orden,
            'usuario' => $usuario,
            'tema' => $tema,
            'moneda' => $moneda,
        ]);
    }

    public function showMueble(string $path)
    {
        $path = urldecode($path);
        $disk = Storage::disk('local'); // storage/app/private

        if (! $disk->exists($path)) {
            abort(404);
        }

        $fullPath = $disk->path($path);
        $mime = $disk->mimeType($path) ?? 'application/octet-stream';

        return response()->file($fullPath, ['Content-Type' => $mime]);
    }
}
