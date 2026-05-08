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
        $sesionId = $request->sesionId ?? session()->getId();
        

        
        // Consumimos la API de Muebles
        try {
            $url = env('FORNITURE_API_URL') . '/muebles';
            $response = Http::get($url);
            
            if ($response->successful()) {
                $mueblesData = json_decode($response->body());
                $muebles = collect($mueblesData);
            } else {
                $muebles = collect([]);
            }
        } catch (\Exception $e) {
            $muebles = collect([]);
        }

        // Otros datos necesarios
        $preferencias = CookiePersonalizacion::getPersonalizacion($sesionId);
        $tema = $preferencias['tema'] ?? 'light';
        $moneda = $preferencias['moneda'] ?? 'EUR';
        $usuario = Session::get('usuario_logueado');
        // Convertimos el array de sesión en objeto para que la vista pueda usar $usuario->nombre etc.
        $usuario = $usuario ? (object) $usuario : null;
        
        // De momento, categorías vacías hasta que hagamos el endpoint en la API
        $categorias = collect([]); 

        return view('home', compact('muebles', 'categorias', 'sesionId', 'usuario', 'tema', 'moneda'));
    }

    public function getGaleria($sesionId)
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
        $sesionId = $request->input('sesionId') ?? session()->getId();
        
        // Pedimos el mueble a la API
        $url = env('FORNITURE_API_URL') . '/muebles/' . $id;
        $response = Http::get($url);

        if ($response->failed()) {
            abort(404, 'Mueble no encontrado en la API');
        }
        $mueble = json_decode($response->body());

        $preferencias = CookiePersonalizacion::getPersonalizacion($sesionId);
        $tema = $preferencias['tema'] ?? 'light';
        $moneda = $preferencias['moneda'] ?? 'EUR';

        return view('showMueble', compact('mueble', 'productosRelacionados', 'sesionId', 'moneda', 'tema'));
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

        $query = Mueble::query()
                        ->with('categoria')
                        ->where('activo', 1);

        if (isset($filtro['nombre'])) {
            $query->where('nombre', 'like', '%' . $filtro['nombre'] . '%');
        }
        if (isset($filtro['categoria_id'])) {
            $query->where('categoria_id', $filtro['categoria_id']);
        }
        if (isset($filtro['precio_min']) && isset($filtro['precio_max'])) {
            $query->whereBetween('precio', [$filtro['precio_min'], $filtro['precio_max']]);
        }
        if (isset($filtro['color'])) {
            $query->where('color', 'like', '%' . $filtro['color'] . '%');
        }
        if (isset($filtro['novedad'])) {
            $query->where('novedad', 1);
        }

        $sesionId = $request->sesionId;

        return $this->ordenar($query, $request->orden ?? '', $filtro, $sesionId);
    }

    public function ordenar($query, $orden, $filtro, $sesionId)
    {
        switch ($orden) {
            case 'precio_asc':
                $query->orderBy('precio', 'asc');
                break;
            case 'precio_desc':
                $query->orderBy('precio', 'desc');
                break;
            case 'nombre_asc':
                $query->orderBy('nombre', 'asc');
                break;
            case 'nombre_desc':
                $query->orderBy('nombre', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $categorias = Categoria::all();

        $preferencias = CookiePersonalizacion::getPersonalizacion($sesionId);
        $tema = $preferencias['tema'];
        $moneda = $preferencias['moneda'];
        $usuario = Session::get('usuario_logueado');
        $usuario = $usuario ? (object) $usuario : null;
        $muebles = $query->paginate($preferencias['paginacion'])->withQueryString();

        return view('home', [
            'muebles' => $muebles,
            'categorias' => $categorias,
            'filtro' => $filtro,
            'orden' => $orden,
            'sesionId' => $sesionId,
            'tema' => $tema,
            'moneda' => $moneda,
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
