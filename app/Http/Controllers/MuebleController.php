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
    public function index()
    {
        $muebles = Mueble::all();
        $categorias = Categoria::all();
        return view('home', compact('muebles', 'categorias'));
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
    public function show(string $id)
    {
        //
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

        //$mueblesFiltrados = $query->paginate(12);
        $mueblesFiltrados = $query->get()->toArray();
        dd($mueblesFiltrados);
        return $this->ordenar($query, $request->orden, $filtro);
    }

    public function ordenar($query, $orden, $filtro) {

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
            $query->orderBy('id', 'asc');
            break;
    }
    $muebles = $query->get()->toArray();
    dd($muebles);

    }
}
