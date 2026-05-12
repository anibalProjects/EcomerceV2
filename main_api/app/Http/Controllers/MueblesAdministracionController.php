<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Galeria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class MueblesAdministracionController extends Controller
{
    protected $carpetaPrivada = 'muebles';

    public function index(Request $request)
    {
        $token = Session::get('api_token');
        $url = env('FORNITURE_API_URL') . '/muebles';
        
        $response = Http::withToken($token)->get($url);
        
        $muebles = collect([]);
        if ($response->successful()) {
            $mueblesData = json_decode($response->body());
            $muebles = collect($mueblesData->data);
        }

        // Filtrado en memoria
        if ($request->has('texto') && $request->texto) {
            $texto = strtolower($request->texto);
            $muebles = $muebles->filter(function($mueble) use ($texto) {
                $nombre = strtolower($mueble->nombre_producto ?? '');
                $desc = strtolower($mueble->descripcion ?? '');
                return str_contains($nombre, $texto) || str_contains($desc, $texto);
            });
        }

        return view('Admin.Muebles.index', compact('muebles'));
    }

    public function create(Request $request, \App\Services\CategoryService $categoryService)
    {
        // Recoger categorías de la API
        $responseCat = $categoryService->getCategories();
        $categorias = collect($responseCat['datos']['data'] ?? []);

        return view('Admin.Muebles.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|numeric',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'color_principal' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'imagen_principal' => 'nullable|image|max:4096'
        ]);
        
        $dataToSend = [
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'categoria_id' => $request->categoria_id,
            'stock' => $request->stock,
            'color' => $request->color_principal,
            'novedad' => $request->has('novedad'),
            'activo' => $request->has('activo'),
        ];

        $token = Session::get('api_token');
        $url = env('FORNITURE_API_URL') . '/muebles';

        $response = Http::withToken($token)->post($url, $dataToSend);

        if ($response->successful()) {
            $muebleApi = $response->json();
            $newId = $muebleApi['data']['id'] ?? null;

            if ($newId && $request->hasFile('imagen_principal')) {
                $ruta = $request->file('imagen_principal')->store('imagenes/pagprincipal', 'public');
                Galeria::create([
                    'mueble_id' => $newId,
                    'ruta' => $ruta,
                    'es_principal' => true,
                    'orden' => 0
                ]);
            }

            return redirect()->route('admin.muebles.index')->with('success', 'Mueble creado correctamente en la API');
        }

        return back()->with('error', 'Error al crear : ' . $response->body());
    }

    public function edit(Request $request, $id, \App\Services\CategoryService $categoryService)
    {
        $token = Session::get('api_token');
        $url = env('FORNITURE_API_URL') . '/muebles/' . $id;
        $response = Http::withToken($token)->get($url);

        if ($response->failed()) {
            return redirect()->route('admin.muebles.index')->with('error', 'Mueble no encontrado en la API');
        }

        $muebleData = json_decode($response->body())->data;
        
        // Mapear para la vista de edición local
        $mueble = new \stdClass();
        $mueble->id = $muebleData->id;
        $mueble->nombre = $muebleData->nombre_producto;
        $mueble->descripcion = $muebleData->descripcion;
        $mueble->precio = $muebleData->precio_venta;
        $mueble->stock = $muebleData->stock_disponible;
        $mueble->color_principal = $muebleData->color;
        $mueble->materiales = '';
        $mueble->dimensiones = '';
        $mueble->novedad = $muebleData->novedad;
        $mueble->activo = $muebleData->activo;
        
        // Recoger categorías de la API
        $responseCat = $categoryService->getCategories();
        $categorias = collect($responseCat['datos']['data'] ?? []);
        
        $mueble->categoria_id = $muebleData->categoria_id;
        
        return view('Admin.Muebles.edit', compact('mueble', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|numeric',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'color_principal' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'imagen_principal' => 'nullable|image|max:4096'
        ]);

        $dataToSend = [
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'categoria_id' => $request->categoria_id,
            'stock' => $request->stock,
            'color' => $request->color_principal,
            'novedad' => $request->has('novedad'),
            'activo' => $request->has('activo'),
        ];

        $token = Session::get('api_token');
        $url = env('FORNITURE_API_URL') . '/muebles/' . $id;

        $response = Http::withToken($token)->put($url, $dataToSend);

        if ($response->successful()) {
            if ($request->hasFile('imagen_principal')) {
                $ruta = $request->file('imagen_principal')->store('imagenes/pagprincipal', 'public');
                
                Galeria::where('mueble_id', $id)->update(['es_principal' => false]);
                Galeria::create([
                    'mueble_id' => $id,
                    'ruta' => $ruta,
                    'es_principal' => true,
                    'orden' => 0
                ]);
            }

            return redirect()->route('admin.muebles.index')->with('success', 'Mueble actualizado correctamente en la API');
        }

        return back()->with('error', 'Error al actualizar: ' . $response->body());
    }

    public function destroy(Request $request, $id)
    {
        $token = Session::get('api_token');
        $url = env('FORNITURE_API_URL') . '/muebles/' . $id;

        $response = Http::withToken($token)->delete($url);

        if ($response->successful()) {
            // Eliminar imágenes locales de la galería
            $galeria = Galeria::where('mueble_id', $id)->get();
            foreach($galeria as $img) {
                if (Storage::disk('public')->exists($img->ruta)) {
                    Storage::disk('public')->delete($img->ruta);
                }
                $img->delete();
            }

            return redirect()->route('admin.muebles.index')->with('success', 'Mueble eliminado correctamente en la API');
        }

        return back()->with('error', 'Error al eliminar: ' . $response->body());
    }

    // GALERÍA (Mantenemos gestión local temporalmente)
    public function galeria(Request $request, $id)
    {
        $token = Session::get('api_token');
        $url = env('FORNITURE_API_URL') . '/muebles/' . $id;
        $response = Http::withToken($token)->get($url);

        if ($response->failed()) {
            return redirect()->route('admin.muebles.index')->with('error', 'Mueble no encontrado en la API');
        }

        $muebleData = json_decode($response->body())->data;
        $mueble = new \stdClass();
        $mueble->id = $muebleData->id;
        $mueble->nombre = $muebleData->nombre_producto;
        
        $mueble->galeria = Galeria::where('mueble_id', $id)->get();

        return view('Admin.Muebles.galeria', compact('mueble'));
    }

    public function uploadGaleria(Request $request, $id)
    {
        $request->validate([
            'imagenes.*' => 'required|image|max:4096'
        ]);

        foreach($request->file('imagenes') as $file) {
            $ruta = $file->store('imagenes/secundarias', 'public');

            Galeria::create([
                'mueble_id' => $id,
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
        if (Storage::disk('public')->exists($imagen->ruta)) {
            Storage::disk('public')->delete($imagen->ruta);
        }
        $imagen->delete();
        return back()->with('success', 'Imagen eliminada');
    }

    public function setPrincipalGaleria($id) {
        $imagen = Galeria::findOrFail($id);
        
        Galeria::where('mueble_id', $imagen->mueble_id)->update(['es_principal' => false]);
        $imagen->update(['es_principal' => true]);

        return back()->with('success', 'Imagen principal actualizada');
    }
}
