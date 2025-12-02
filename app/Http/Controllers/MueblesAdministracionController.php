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

    private function validarAcceso(): void
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
        $sesionId = $request->get('sesionId');

        $muebles = Mueble::with('categoria')->get();

        // Filtrado
        if ($request->has('texto') && $request->texto) {
            $muebles = $muebles->filter(function($mueble) use ($request) {
                return str_contains(strtolower($mueble->nombre), strtolower($request->texto)) ||
                       str_contains(strtolower($mueble->descripcion), strtolower($request->texto));
            });
        }

        return view('Admin.Muebles.index', compact('muebles', 'sesionId'));
    }

    public function create(Request $request)
    {
        $this->validarAcceso();
        $sesionId = $request->get('sesionId');
        $categorias = Categoria::all();
        return view('Admin.Muebles.create', compact('categorias', 'sesionId'));
    }

    public function store(Request $request)
    {
        $this->validarAcceso();
        $sesionId = $request->get('sesionId');
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
        $data['destacado'] = $request->has('destacado');
        $data['activo'] = $request->has('activo');

        if ($request->hasFile('imagen_principal')) {
            $file = $request->file('imagen_principal');
            $nombre = 'principal_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs($this->carpetaPrivada, $nombre, 'public');
            $data['imagen_principal'] = $nombre;
        }

        $mueble = Mueble::create($data);

        return redirect()->route('admin.muebles.index', ['sesionId' => $sesionId])->with('success', 'Mueble creado correctamente');
    }

    public function edit(Request $request, $id)
    {
        $this->validarAcceso();
        $sesionId = $request->get('sesionId');
        $mueble = Mueble::findOrFail($id);
        $categorias = Categoria::all();
        return view('Admin.Muebles.edit', compact('mueble', 'categorias', 'sesionId'));
    }

    public function update(Request $request, $id)
    {
        $this->validarAcceso();
        $sesionId = $request->get('sesionId');
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
        $data['destacado'] = $request->has('destacado');
        $data['activo'] = $request->has('activo');

        if ($request->hasFile('imagen_principal')) {
            // Elimina la imagen anterior si existe
            if ($mueble->imagen_principal && Storage::disk('public')->exists($this->carpetaPrivada . '/' . $mueble->imagen_principal)) {
                Storage::disk('public')->delete($this->carpetaPrivada . '/' . $mueble->imagen_principal);
            }

            $file = $request->file('imagen_principal');
            $nombre = 'principal_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs($this->carpetaPrivada, $nombre, 'public');
            $data['imagen_principal'] = $nombre;
        }

        $mueble->update($data);

        return redirect()->route('admin.muebles.index', ['sesionId' => $sesionId])->with('success', 'Mueble actualizado correctamente');
    }

    public function destroy(Request $request, $id)
    {
        $this->validarAcceso();
        $sesionId = $request->get('sesionId');
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
        return redirect()->route('admin.muebles.index', ['sesionId' => $sesionId])->with('success', 'Mueble eliminado correctamente');
    }

    // GALERÍA
    public function galeria(Request $request, $id)
    {
        $this->validarAcceso();
        $sesionId = $request->get('sesionId');
        $mueble = Mueble::with('galeria')->findOrFail($id);
        return view('Admin.Muebles.galeria', compact('mueble', 'sesionId'));
    }

    public function uploadGaleria(Request $request, $id)
    {
        $this->validarAcceso();
        $mueble = Mueble::findOrFail($id);

        $request->validate([
            'imagenes.*' => 'required|image|max:4096'
        ]);

        if ($request->hasFile('imagenes')) {
            foreach($request->file('imagenes') as $file) {
                $nombre = 'galeria_' . $id . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs($this->carpetaPrivada, $nombre, 'public');

                Galeria::create([
                    'mueble_id' => $mueble->id,
                    'ruta' => $nombre,
                    'es_principal' => false,
                    'orden' => 0
                ]);
            }
        }

        return back()->with('success', 'Imágenes subidas correctamente');
    }

    public function deleteImagenGaleria($id)
    {
        $this->validarAcceso();
        $imagen = Galeria::findOrFail($id);
        if (Storage::disk('public')->exists($this->carpetaPrivada . '/' . $imagen->ruta)) {
            Storage::disk('public')->delete($this->carpetaPrivada . '/' . $imagen->ruta);
        }
        $imagen->delete();
        return back()->with('success', 'Imagen eliminada');
    }

    public function setPrincipalGaleria($id) {
        $this->validarAcceso();
        $imagen = Galeria::findOrFail($id);
        $mueble = $imagen->mueble;

        // Quita la principal anterior
        $mueble->galeria()->update(['es_principal' => false]);

        // Y pone la nueva como principal
        $imagen->update(['es_principal' => true]);

        return back()->with('success', 'Imagen principal actualizada');
    }
}
