<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CategoriasAdministracionController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $result = $this->categoryService->getCategories();
        $categorias = collect($result['datos']['data'] ?? []);

        // Filtrado en memoria
        if ($request->has('texto') && $request->texto) {
            $texto = strtolower($request->texto);
            $categorias = $categorias->filter(function($categoria) use ($texto) {
                $nombre = strtolower($categoria['nombre'] ?? '');
                return str_contains($nombre, $texto);
            });
        }

        return view('Admin.Categorias.index', compact('categorias'));
    }

    public function create(Request $request)
    {
        return view('Admin.Categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $token = Session::get('api_token');
        $result = $this->categoryService->storeCategory(
            ['nombre' => $request->nombre],
            $token
        );

        if ($result['estado'] >= 200 && $result['estado'] < 300) {
            return redirect()->route('admin.categorias.index')->with('success', 'Categoría creada correctamente');
        }

        return back()->with('error', 'Error al crear la categoría: ' . json_encode($result['datos']));
    }

    public function edit(Request $request, $id)
    {
        $result = $this->categoryService->getCategoryById($id);

        if ($result['estado'] !== 200) {
            return redirect()->route('admin.categorias.index')->with('error', 'Categoría no encontrada en la API');
        }

        $catData = $result['datos']['data'] ?? $result['datos'];
        $categoria = new \stdClass();
        $categoria->id = $catData['id'];
        $categoria->nombre = $catData['nombre'];

        return view('Admin.Categorias.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $token = Session::get('api_token');
        $result = $this->categoryService->updateCategory(
            $id,
            ['nombre' => $request->nombre],
            $token
        );

        if ($result['estado'] >= 200 && $result['estado'] < 300) {
            return redirect()->route('admin.categorias.index')->with('success', 'Categoría actualizada correctamente');
        }

        return back()->with('error', 'Error al actualizar la categoría: ' . json_encode($result['datos']));
    }

    public function destroy(Request $request, $id)
    {
        $token = Session::get('api_token');
        $result = $this->categoryService->deleteCategory($id, $token);

        if ($result['estado'] >= 200 && $result['estado'] < 300) {
            return redirect()->route('admin.categorias.index')->with('success', 'Categoría eliminada correctamente');
        }

        return back()->with('error', 'Error al eliminar la categoría: ' . json_encode($result['datos']));
    }
}
