<?php

namespace App\Http\Controllers;

use App\Models\Mueble;
use Illuminate\Http\Request;
use App\Http\Resources\MuebleResource;
use App\Http\Requests\StoreMuebleRequest;
use App\Http\Requests\UpdateMuebleRequest;

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
     * Display a listing of specific resources (for cart).
     */
    public function showByIds(Request $request)
    {
        $ids = $request->query('ids');

        if (!$ids) {
            return response()->json(['mensaje' => 'No se proporcionaron IDs'], 400);
        }

        $arrayIds = explode(',', $ids);
        $muebles = Mueble::with(['category', 'galeria'])->whereIn('id', $arrayIds)->get();

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
    public function update(UpdateMuebleRequest $request, string $id) // Usa el Request específico aquí
{
    $mueble = Mueble::findOrFail($id);

    if (!$request->user()->tokenCan('muebles.editar')) {
        return response()->json(['mensaje' => 'No tienes permiso para editar muebles'], 403);
    }

    $mueble->update($request->validated());

    return new MuebleResource($mueble);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {

        $mueble = Mueble::findOrFail($id);

        if (!$request->user()->tokenCan('muebles.eliminar')) {
            return response()->json(['mensaje' => 'No tienes permiso para eliminar muebles'], 403);
        }
        $mueble->delete();

        return response()->json(['mensaje' => 'Mueble eliminado correctamente'], 200);
    }
}
