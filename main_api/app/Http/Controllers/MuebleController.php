<?php

namespace App\Http\Controllers;
use App\Http\Controllers\CookiePersonalizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Http;

class MuebleController extends Controller
{

    protected $carpetaPrivadaPrincipal = 'imagenes/pagprincipal';
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, \App\Services\FurnitureServices $furnitureService, \App\Services\CategoryService $categoryService)
    {
        // Consumimos la API de Muebles usando el servicio
        $responseMuebles = $furnitureService->getMuebles();
        $muebles = collect($responseMuebles['datos']['data'] ?? [])->map(fn($item) => (object)$item);

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
        $filtro = [];

        if ($request->has('filtro') && is_array($request->filtro)) {
            foreach ($request->filtro as $i => $valor) {
                if ($valor != null) {
                    $filtro[$i] = $valor;
                }
            }
        }

        try {
            $responseMuebles = $furnitureService->getMuebles();
            $muebles = collect($responseMuebles['datos']['data'] ?? [])->map(fn($item) => (object)$item);
        } catch (\Exception $e) {
            $muebles = collect([]);
        }

        if (isset($filtro['nombre'])) {
            $termino = strtolower($filtro['nombre']);
            $muebles = $muebles->filter(function ($mueble) use ($termino) {
                return str_contains(strtolower($mueble->nombre_producto), $termino);
            });
        }
    if (isset($filtro['categoria_id'])) {
        $muebles = $muebles->filter(function ($mueble) use ($filtro) {
            return ($mueble->categoria_id ?? null) == $filtro['categoria_id'];
        });
    }
    if (isset($filtro['precio_min'])) {
        $muebles = $muebles->where('precio_venta', '>=', $filtro['precio_min']);
    }
    if (isset($filtro['precio_max'])) {
        $muebles = $muebles->where('precio_venta', '<=', $filtro['precio_max']);
    }
    if (isset($filtro['color'])) {
        $muebles = $muebles->filter(function ($mueble) use ($filtro) {
            return str_contains(strtolower($mueble->color), strtolower($filtro['color']));
        });
    }
    if (isset($filtro['novedad'])) {
        $muebles = $muebles->where('novedad', 1);
    }


        return $this->ordenar($muebles, $request->orden ?? '', $filtro, $categoryService);
    }

    public function ordenar($muebles, $orden, $filtro, \App\Services\CategoryService $categoryService)
    {
        switch ($orden) {
        case 'precio_asc':
            $muebles = $muebles->sortBy('precio_venta');
            break;
        case 'precio_desc':
            $muebles = $muebles->sortByDesc('precio_venta');
            break;
        case 'nombre_asc':
            $muebles = $muebles->sortBy('nombre_producto');
            break;
        case 'nombre_desc':
            $muebles = $muebles->sortByDesc('nombre_producto');
            break;
        default:
            $muebles = $muebles->sortByDesc('created_at');
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
