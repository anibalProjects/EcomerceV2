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
        $ids = $request->input('ids');

        if (!$ids) {
            return response()->json(['mensaje' => 'No se proporcionaron IDs'], 400);
        }

        $arrayIds = is_array($ids) ? $ids : explode(',', $ids);
        $muebles = Mueble::with(['category', 'galeria'])->whereIn('id', $arrayIds)->get();

        return MuebleResource::collection($muebles);
    }

    /**
     * Store a newly created resource in storage.
     * (El middleware check.abilities ya garantiza el permiso.)
     */
    public function store(StoreMuebleRequest $request)
    {
        $mueble = Mueble::create($request->validated());
        return (new MuebleResource($mueble))->response()->setStatusCode(201);
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
     * (El middleware check.abilities ya garantiza el permiso.)
     */
    public function update(UpdateMuebleRequest $request, string $id)
    {
        $mueble = Mueble::findOrFail($id);
        $mueble->update($request->validated());
        return new MuebleResource($mueble->fresh(['category', 'galeria']));
    }

    /**
     * Remove the specified resource from storage.
     * (El middleware check.abilities ya garantiza el permiso.)
     */
    public function destroy(string $id)
    {
        $mueble = Mueble::findOrFail($id);
        $mueble->delete();
        return response()->json(['mensaje' => 'Mueble eliminado correctamente'], 200);
    }

    /**
     * Reduce stock of a specific furniture (llamado internamente desde main_api al comprar).
     */
    public function reduceStock(Request $request, string $id)
    {
        $mueble = Mueble::find($id);

        if (!$mueble) {
            return response()->json(['mensaje' => 'Mueble no encontrado'], 404);
        }

        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        $cantidad = (int) $request->input('cantidad', 1);

        if ($mueble->stock < $cantidad) {
            return response()->json([
                'mensaje' => 'Stock insuficiente para el producto: ' . $mueble->nombre,
                'stock_actual' => $mueble->stock,
            ], 400);
        }

        $mueble->stock -= $cantidad;
        $mueble->save();

        return response()->json([
            'mensaje'     => 'Stock reducido correctamente',
            'id'          => $mueble->id,
            'nuevo_stock' => $mueble->stock,
        ], 200);
    }
}
