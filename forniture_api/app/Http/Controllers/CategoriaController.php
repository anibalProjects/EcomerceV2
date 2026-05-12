<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Listar todas las categorías.
     */
    public function index()
    {
        $categorias = Category::withCount('muebles')->get();
        return CategoryResource::collection($categorias);
    }

    /**
     * Ver el detalle de una categoría (con sus muebles activos).
     */
    public function show(string $id)
    {
        $categoria = Category::withCount('muebles')->find($id);

        if (!$categoria) {
            return response()->json(['mensaje' => 'Categoría no encontrada'], 404);
        }

        return new CategoryResource($categoria);
    }

    /**
     * Crear una nueva categoría.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:categories,nombre',
        ]);

        $categoria = Category::create(['nombre' => $request->nombre]);
        return new CategoryResource($categoria);
    }

    /**
     * Actualizar una categoría existente.
     */
    public function update(Request $request, string $id)
    {
        $categoria = Category::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100|unique:categories,nombre,' . $id,
        ]);

        $categoria->update(['nombre' => $request->nombre]);
        return new CategoryResource($categoria);
    }

    /**
     * Eliminar una categoría.
     */
    public function destroy(string $id)
    {
        $categoria = Category::findOrFail($id);
        $categoria->delete();
        return response()->json(['mensaje' => 'Categoría eliminada correctamente'], 200);
    }
}
