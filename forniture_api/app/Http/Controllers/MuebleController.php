<?php

namespace App\Http\Controllers;

use App\Models\Mueble;
use Illuminate\Http\Request;
use App\Http\Resources\MuebleResource;
use App\Http\Requests\StoreMuebleRequest;

class MuebleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $muebles = Mueble::with(['category', 'galeria'])
            ->activos() 
            ->deCategoria($request->query('categoria_id')) 
            ->ordenarPrecio($request->query('orden', 'asc')) 
            ->get();

        return MuebleResource::collection($muebles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMuebleRequest $request)
    {
        if (!$request->user()->tokenCan('muebles.crear')) {
            return response()->json(['mensaje' => 'No tienes permiso para crear muebles'], 403);
        }

        $mueble = Mueble::create($request->validated());
        return new MuebleResource($mueble);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $mueble = Mueble::with(['category', 'galeria'])->find($id);

        if (!$mueble) {
            return response()->json(['mensaje' => 'Mueble no encontrado'], 404);
        }

        return new MuebleResource($mueble);
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
}
