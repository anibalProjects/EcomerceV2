<?php

namespace App\Http\Controllers;

use App\Models\Mueble;
use Illuminate\Http\Request;

class MuebleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $muebles = Mueble::with(['category', 'galeria'])->where('activo', true)->get();

        return response()->json($muebles);
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
        $mueble = Mueble::with(['category', 'galeria'])->find($id);

        if (!$mueble) {
            return response()->json(['mensaje' => 'Mueble no encontrado'], 404);
        }

        return response()->json($mueble);
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
