<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Mueble;
use App\Models\Galeria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MueblesAdministracionController extends Controller
{
    protected $carpetaPrivada = 'muebles';


    public function index(Request $request)
    {
        $muebles = Mueble::with('categoria')->get();

        // Filtrado
        if ($request->has('texto') && $request->texto) {
            $muebles = $muebles->filter(function($mueble) use ($request) {
                return str_contains(strtolower($mueble->nombre), strtolower($request->texto)) ||
                       str_contains(strtolower($mueble->descripcion), strtolower($request->texto));
            });
        }

        return view('Admin.Muebles.index', compact('muebles'));
    }

    public function create(Request $request)
    {
        $categorias = Categoria::all();
        return view('Admin.Muebles.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'color_principal' => 'required|string',
            'materiales' => 'required|string',
            'dimensiones' => 'required|string',
            'descripcion' => 'nullable|string',
            'imagen_principal' => 'nullable|image|max:4096'
        ]);

        $data = $request->all();
        $data['novedad'] = $request->has('novedad');
        $data['activo'] = $request->has('activo');

        if ($request->hasFile('imagen_principal')) {
            $file = $request->file('imagen_principal');
            $nombre = 'principal_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs($this->carpetaPrivada, $nombre, 'public');
            $data['imagen_principal'] = $nombre;
        }

        $mueble = Mueble::create($data);

        return redirect()->route('admin.muebles.index')->with('success', 'Mueble creado correctamente');
    }

    public function edit(Request $request, $id)
    {
        $mueble = Mueble::findOrFail($id);
        $categorias = Categoria::all();
        return view('Admin.Muebles.edit', compact('mueble', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $mueble = Mueble::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'color_principal' => 'required|string',
            'materiales' => 'required|string',
            'dimensiones' => 'required|string',
            'descripcion' => 'nullable|string',
            'imagen_principal' => 'nullable|image|max:4096'
        ]);

        $data = $request->all();
        $data['novedad'] = $request->has('novedad');
        $data['activo'] = $request->has('activo');

        // Solo guardar imagen si se subió una nueva
        if ($request->hasFile('imagen_principal')) {
            $ruta = $request->file('imagen_principal')->store('imagenes/pagprincipal');
            $data['imagen_principal'] = $ruta;
        }

        $mueble->update($data);

        return redirect()->route('admin.muebles.index')->with('success', 'Mueble actualizado correctamente');
    }

    public function destroy(Request $request, $id)
    {
        $mueble = Mueble::findOrFail($id);
        // Elimina imagen principal
        if ($mueble->imagen_principal) {
             Storage::disk('public')->delete($this->carpetaPrivada . '/' . $mueble->imagen_principal);
        }
        // Elimina imagenes de la galeria
        foreach($mueble->galeria as $img) {
            Storage::disk('public')->delete($this->carpetaPrivada . '/' . $img->ruta);
            $img->delete();
        }

        $mueble->delete();
        return redirect()->route('admin.muebles.index')->with('success', 'Mueble eliminado correctamente');
    }

    // GALERÍA
    public function galeria(Request $request, $id)
    {
        $mueble = Mueble::with('galeria')->findOrFail($id);
        return view('Admin.Muebles.galeria', compact('mueble'));
    }

    public function uploadGaleria(Request $request, $id)
    {
        $request->validate([
            'imagenes.*' => 'required|image|max:4096'
        ]);

        $mueble = Mueble::findOrFail($id);
        foreach($request->file('imagenes') as $file) {
            $ruta = $file->store('imagenes/secundarias');

                Galeria::create([
                    'mueble_id' => $mueble->id,
                    'ruta' => $ruta,
                    'es_principal' => false,
                    'orden' => 0
                ]);
        }

        return back()->with('success', 'Imágenes subidas correctamente');
    }

    public function deleteImagenGaleria($id)
    {
        $imagen = Galeria::findOrFail($id);
        if (Storage::disk('public')->exists($this->carpetaPrivada . '/' . $imagen->ruta)) {
            Storage::disk('public')->delete($this->carpetaPrivada . '/' . $imagen->ruta);
        }
        $imagen->delete();
        return back()->with('success', 'Imagen eliminada');
    }

    public function setPrincipalGaleria($id) {
        $imagen = Galeria::findOrFail($id);
        $mueble = $imagen->mueble;

        // Quita la principal anterior
        $mueble->galeria()->update(['es_principal' => false]);

        // Y pone la nueva como principal
        $imagen->update(['es_principal' => true]);

        return back()->with('success', 'Imagen principal actualizada');
    }
}
