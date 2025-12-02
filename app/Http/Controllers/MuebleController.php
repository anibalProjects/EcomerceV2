<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Mueble;
use Illuminate\Http\Request;

class MuebleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //FALTA: recoger cookie saul
        $sesionId = $request->sesionId;
        $muebles = Mueble::paginate(12);
        $categorias = Categoria::all();
        return view('home', compact('muebles', 'categorias','sesionId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
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

        // Productos relacionados por categoría
        $productosRelacionados = Mueble::where('categoria_id', $mueble->categoria_id)
                                        ->where('id', '!=', $mueble->id)
                                        ->limit(8)
                                        ->get();

        return view('showMueble', compact('mueble', 'productosRelacionados', 'sesionId'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function filtrar (Request $request) {
        $filtro = [];

        if($request->has('filtro') && is_array($request->filtro)){
             foreach ($request->filtro as $i => $valor) {
                if (!$valor == null) {
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
            $query->where('categoria_id', 'like', '%' . $filtro['categoria_id'] . '%');
        }
        if (isset($filtro['precio_min']) && isset($filtro['precio_max'])) {
            $query->whereBetween('precio', [$filtro['precio_min'], $filtro['precio_max']]);
        }
        if (isset($filtro['color'])) {
            $query->where('color', 'like', '%' . $filtro['color'] . '%');
        }
        //CORREGIR: COLOR CON FACTORIA SE CREAN EN INGLES, HABRÍA QUE VER COMO LO HACEMOS
        if (isset($filtro['novedad'])) {
            $query->where('novedad', 1);
        }

        $sesionId = $request->sesionId;
        return $this->ordenar($query, $request->orden, $filtro, $sesionId);
    }

    public function ordenar($query, $orden, $filtro, $sesionId) {

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
        case 'fecha_desc':
            $query->orderBy('created_at', 'desc');
            break;
        case 'fecha_asc':
            $query->orderBy('created_at', 'asc');
            break;

        default:
            $query->orderBy('id', 'asc');
            break;
    }
    $muebles = $query->paginate(12)->withQueryString();
    $categorias = Categoria::all();

    return view('home', [
        'muebles' => $muebles,
        'categorias' => $categorias,
        'filtro' => $filtro,
        'orden' => $orden,
        'sesionId' => $sesionId
    ]);
    }
}
