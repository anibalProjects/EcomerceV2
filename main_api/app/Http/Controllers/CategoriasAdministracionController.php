<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriasAdministracionController extends Controller
{
    private function validarAcceso()
    {
        if (!auth()->check()) {
            abort(403, 'Debes iniciar sesión.');
        }

        if (auth()->user()->rol_id !== 1) {
            abort(403, 'Acceso no autorizado. Se requiere rol de Administrador.');
        }
    }

    public function index(Request $request)
    {
        $this->validarAcceso();

        $categorias = Categoria::all();

        // Filtrado
        if ($request->has('texto') && $request->texto) {
            $categorias = $categorias->filter(function($categoria) use ($request) {
                return str_contains(strtolower($categoria->nombre), strtolower($request->texto)) ||
                       str_contains(strtolower($categoria->descripcion), strtolower($request->texto));
            });
        }

        return view('Admin.Categorias.index', compact('categorias'));
    }

    public function create(Request $request)
    {
        $this->validarAcceso();
        return view('Admin.Categorias.create');
    }

    public function store(Request $request)
    {
        $this->validarAcceso();

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $data = $request->all();
        Categoria::create($data);

        return redirect()->route('admin.categorias.index')->with('success', 'Categoría creada correctamente');
    }

    public function edit(Request $request, $id)
    {
        $this->validarAcceso();
        $categoria = Categoria::findOrFail($id);
        return view('Admin.Categorias.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        $this->validarAcceso();
        $categoria = Categoria::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $data = $request->all();
        $categoria->update($data);

        return redirect()->route('admin.categorias.index')->with('success', 'Categoría actualizada correctamente');
    }

    public function destroy(Request $request, $id)
    {
        $this->validarAcceso();
        $categoria = Categoria::findOrFail($id);

        // Check if category has products
        if ($categoria->muebles()->count() > 0) {
            return redirect()->route('admin.categorias.index')->with('error', 'No se puede eliminar la categoría porque tiene productos asociados');
        }

        $categoria->delete();
        return redirect()->route('admin.categorias.index')->with('success', 'Categoría eliminada correctamente');
    }
}
