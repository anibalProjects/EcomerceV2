<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Mueble;
use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class MuebleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sesionId = $request->sesionId ?? session()->getId();
        $usuario = User::buscarUsuario($sesionId);

        $preferencias = CookiePersonalizacion::getPersonalizacion($sesionId);
        $tema = $preferencias['tema'];
        $moneda = $preferencias['moneda'];
        $muebles = Mueble::paginate($preferencias['paginacion']);
        /* $paginacion = $preferencias['paginacion']; */
        $categorias = Categoria::all();

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
        $mueble = Mueble::with('Categoria')->findOrFail($id);
        $sesionId = $request->input('sesionId');
        $preferencias = CookiePersonalizacion::getPersonalizacion($sesionId);
        $tema = $preferencias['tema'];
        $moneda = $preferencias['moneda'];
        $productosRelacionados = Mueble::where('categoria_id', $mueble->categoria_id)
        ->where('id', '!=', $mueble->id)
        ->where('activo', 1)
        ->limit(4)
        ->get();
        
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
        $usuario = User::buscarUsuario($sesionId);
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
}
