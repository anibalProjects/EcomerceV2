<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Mueble;
use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class MuebleController extends Controller
{

    protected $carpetaPrivadaPrincipal = 'imagenes/pagprincipal';
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        // Consumimos la API de Muebles
        try {
            $url = env('FORNITURE_API_URL') . '/muebles';
            $response = Http::get($url);
            
            if ($response->successful()) {
                $mueblesData = json_decode($response->body());
                $muebles = collect($mueblesData->data);
            } else {
                $muebles = collect([]);
            }
        } catch (\Exception $e) {
            $muebles = collect([]);
        }
        // Otros datos necesarios
        $tema = 'light';
        $moneda = $preferencias['moneda'] ?? 'EUR';
        $usuario = Session::get('usuario_logueado');
        // Convertimos el array de sesión en objeto para que la vista pueda usar $usuario->nombre etc.
        $usuario = $usuario ? (object) $usuario : null;
        
        // De momento, categorías vacías hasta que hagamos el endpoint en la API
        $categorias = collect([]); 
        
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
    public function show(string $id, Request $request)
    {
        
        // Pedimos el mueble a la API
        $url = env('FORNITURE_API_URL') . '/muebles/' . $id;
        $response = Http::get($url);

        if ($response->failed()) {
            abort(404, 'Mueble no encontrado en la API');
        }
        $mueble = json_decode($response->body())->data;

        $tema = 'light';
        $moneda = 'EUR';

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

    public function filtrar(Request $request)
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
            $url = env('FORNITURE_API_URL') . '/muebles';
            $response = Http::get($url);
            
            if ($response->successful()) {
                $mueblesData = json_decode($response->body());
                $muebles = collect($mueblesData->data);
            } else {
                $muebles = collect([]);
            }
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
            return $mueble->categoria == $filtro['categoria_id'];
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


        return $this->ordenar($muebles, $request->orden ?? '', $filtro);
    }

    public function ordenar($muebles, $orden, $filtro)
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


        $categorias = Categoria::all();

        $usuario = Session::get('usuario_logueado');
        $usuario = $usuario ? (object) $usuario : null;

        return view('home', [
            'muebles' => $muebles,
            'categorias' => $categorias,
            'filtro' => $filtro,
            'orden' => $orden,
            'usuario' => $usuario,
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
