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
    protected $carpetaPublica = 'img/muebles';

    public function index(Request $request, \App\Services\FurnitureServices $furnitureService)
    {
        $response = $furnitureService->getMuebles();
        
        $muebles = collect([]);
        if ($response['estado'] === 200) {
            $muebles = collect($response['datos']['data'] ?? [])->map(fn($item) => (object)$item);
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
        $categorias = collect($responseCat['datos']['data'] ?? [])->map(fn($item) => (object)$item);

        return view('Admin.Muebles.create', compact('categorias'));
    }

    public function store(Request $request, \App\Services\FurnitureServices $furnitureService)
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
        $result = $furnitureService->storeMueble($dataToSend, $token);

        if ($result['estado'] >= 200 && $result['estado'] < 300) {
            $newId = $result['datos']['data']['id'] ?? null;

            if ($newId && $request->hasFile('imagen_principal')) {
                $file = $request->file('imagen_principal');
                $filename = time() . '_principal_' . $file->getClientOriginalName();
                $file->move(public_path($this->carpetaPublica), $filename);
                $ruta = $this->carpetaPublica . '/' . $filename;

                Galeria::create([
                    'mueble_id' => $newId,
                    'ruta' => $ruta,
                    'es_principal' => true,
                    'orden' => 0
                ]);
            }

            return redirect()->route('admin.muebles.index')->with('success', 'Mueble creado correctamente en la API');
        }

        return back()->with('error', 'Error al crear : ' . json_encode($result['datos']));
    }

    public function edit(Request $request, $id, \App\Services\CategoryService $categoryService, \App\Services\FurnitureServices $furnitureService)
    {
        $token = Session::get('api_token');
        $result = $furnitureService->getMuebleById($id, $token);

        if ($result['estado'] !== 200) {
            return redirect()->route('admin.muebles.index')->with('error', 'Mueble no encontrado en la API');
        }

        $muebleData = (object) ($result['datos']['data'] ?? $result['datos']);
        
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
        $categorias = collect($responseCat['datos']['data'] ?? [])->map(fn($item) => (object)$item);
        
        $mueble->categoria_id = $muebleData->categoria_id;
        
        return view('Admin.Muebles.edit', compact('mueble', 'categorias'));
    }

    public function update(Request $request, $id, \App\Services\FurnitureServices $furnitureService)
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
        $result = $furnitureService->updateMueble($id, $dataToSend, $token);

        if ($result['estado'] >= 200 && $result['estado'] < 300) {
            if ($request->hasFile('imagen_principal')) {
                $file = $request->file('imagen_principal');
                $filename = time() . '_principal_' . $file->getClientOriginalName();
                $file->move(public_path($this->carpetaPublica), $filename);
                $ruta = $this->carpetaPublica . '/' . $filename;
                
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

        return back()->with('error', 'Error al actualizar: ' . json_encode($result['datos']));
    }

    public function destroy(Request $request, $id, \App\Services\FurnitureServices $furnitureService)
    {
        $token = Session::get('api_token');
        $result = $furnitureService->deleteMueble($id, $token);

        if ($result['estado'] >= 200 && $result['estado'] < 300) {
            // Eliminar imágenes locales de la galería
            $galeria = Galeria::where('mueble_id', $id)->get();
            foreach($galeria as $img) {
                $fullPath = public_path($img->ruta);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
                $img->delete();
            }

            return redirect()->route('admin.muebles.index')->with('success', 'Mueble eliminado correctamente en la API');
        }

        return back()->with('error', 'Error al eliminar: ' . json_encode($result['datos']));
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
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path($this->carpetaPublica), $filename);
            $ruta = $this->carpetaPublica . '/' . $filename;

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
        $fullPath = public_path($imagen->ruta);
        if (file_exists($fullPath)) {
            unlink($fullPath);
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
