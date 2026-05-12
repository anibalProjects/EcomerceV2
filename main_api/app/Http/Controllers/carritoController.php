<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CookiePersonalizacion;
use App\Models\Carrito;
use App\Models\CarritoProducto;
use App\Models\Mueble;
use App\Models\User;
use App\Services\AuthApiService;
use App\Services\FurnitureServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class carritoController extends Controller
{
    public function index(Request $request, AuthApiService $authApiService, FurnitureServices $furnitureServices)
    {
        $token = Session::get('api_token');
        $usuario = $authApiService->validateToken($token);
        if ($usuario['estado'] === 200) {
            $sesionId = Session::get('usuario_logueado');
        }

        if($usuario){
            $carrito = Carrito::firstOrCreate(
                ['usuario_id' => $usuario['datos']['usuario']['id']],
            );

            $carritoProductos = CarritoProducto::where('carrito_id', $carrito->id)->get();

            $response = $furnitureServices->getMueblesByIds($carritoProductos->pluck('mueble_id')->toArray());
            $mueblesApi = collect($response['datos']['data'] ?? []);

            $productosDelCarrito = $mueblesApi->map(function ($mueble) use ($carritoProductos) {
                $product = $carritoProductos->where('mueble_id', $mueble['id'])->first();
                $cantidad = $product ? $product->cantidad : 1;
                return array_merge($mueble, [
                    'cantidad' => $cantidad,
                    'subtotal' => $mueble['precio_venta'] * $cantidad
                ]);
            });

            $total = $productosDelCarrito->sum('subtotal');

            $preferencias = CookiePersonalizacion::getPersonalizacion(null, $usuario['datos']['usuario']['id']);
            $tema = $preferencias['tema'];
            $moneda = $preferencias['moneda'];

            return view('carrito.carritoView', compact('usuario', 'productosDelCarrito', 'tema', 'moneda', 'total'));
        }else{
            return redirect()->route('login.mostrar')->with('error', 'debes iniciar sesion para ver el carrito');
        }
    }

    public function store(Request $request, AuthApiService $authApiService){

        $request->validate([
            'cantidad' => 'required|int|min:1|max:10'
        ]);

        $token = Session::get('api_token');
        $usuario = $authApiService->validateToken($token);
        if($usuario){
                //Busco el carrito del usuario o si no lo creo
                $carrito = Carrito::firstOrCreate(
                    ['usuario_id' => $usuario['datos']['usuario']['id']],
                );

                //buscar el producto
                $producto_id = $request->producto_id;
                $producto = Mueble::find($producto_id);
                $cantidad = (int) $request->cantidad;

                // Comprobar que hay stock
                if ($producto->stock >= $cantidad) {
                    //compruebo si el producto esta en el carrito
                    $productoEnCarrito = $carrito->muebles()->where('mueble_id', $producto_id)->first();
                    //Si existe edito cantidad  de ese producto que esta asociada a ese carrito si no la inserto al carrito
                    if ($productoEnCarrito) {
                        $nuevaCantidad = $productoEnCarrito->pivot->cantidad + $cantidad;
                        $carrito->muebles()->updateExistingPivot($producto_id, ['cantidad' => $nuevaCantidad]);
                    } else {

                        $carrito->muebles()->attach($producto_id, ['cantidad' => $cantidad]);
                    }
                    return redirect()->back()->with('success', 'Producto' . $producto->nombre . 'añadido al carrito.');
                } else {
                    return redirect()->back()->with('error', 'No hay stock suficiente del producto' . $producto->nombre);
                }
        }else{
            return redirect()->route('login.mostrar')->with('error', 'debes iniciar sesion para añadir productos al carrito');
        }
    }

    public function destroy($producto_id, AuthApiService $authApiService)
    {
        $usuario = $authApiService->validateToken(Session::get('api_token'));

        if ($usuario) {
            $carrito = Carrito::where('usuario_id', $usuario['datos']['usuario']['id'])->first();
            $carrito_producto = CarritoProducto::where('carrito_id', $carrito->id)->where('mueble_id', $producto_id)->first();
            if ($carrito && $carrito_producto) {
                $carrito_producto->delete();
                return redirect()->back()->with('success', 'Se ha eliminado el producto del carrito ');
            }else{
                return redirect()->back()->with('error', 'No se ha podido eliminar el producto del carrito');
            }
        }else{
            return redirect()->route('login.mostrar')->with('error', 'debes iniciar sesion');
        }
    }

    public function update($producto_id, AuthApiService $authApiService, Request $request){
        //Datos del usuario
        $usuario = $authApiService->validateToken(Session::get('api_token'));



        $carrito = Carrito::where('usuario_id', $usuario['datos']['usuario']['id'])->first();

        $productoEnCarrito = CarritoProducto::where('mueble_id', $producto_id)
            ->where('carrito_id', $carrito->id)
            ->first();

        if ($usuario) {
            if (!$productoEnCarrito) {
                return redirect()->back()->with('error', 'Producto no encontrado en el carrito.');
            }

            if ($request->increment) {
                $productoEnCarrito->cantidad++;
                $productoEnCarrito->save();
            }

            if ($request->decrement) {
                if ($productoEnCarrito->cantidad > 1) {
                    $productoEnCarrito->cantidad--;
                    $productoEnCarrito->save();
                } else {
                    $productoEnCarrito->delete();
                    return redirect()->back()->with('success', 'Producto eliminado del carrito.');
                }
            }
            return redirect()->back();
        } else {
            return redirect()->route('login.mostrar')->with('error', 'debes iniciar sesion');
        }
    }

    public function empty(Request $request)
    {

        $sesionId = $request->input('sesionId');
        $usuario = User::buscarUsuario($sesionId);

        if ($usuario) {
            $carrito = Carrito::where('usuario_id', $usuario->id)->first();
            $carrito->muebles()->detach();

            return redirect()->route('carrito.index', ['sesionId' => $sesionId]);
        }else{
            return redirect()->route('login.mostrar')->with('error', 'debes iniciar sesion');
        }
    }

      public function buy(Request $request, AuthApiService $authApiService){

        $usuario = $authApiService->validateToken(Session::get('api_token'));
        $carrito = Carrito::where('usuario_id', $usuario['datos']['usuario']['id'])->first();
        $email = ["email"=> $usuario['datos']['usuario']['email']];
        if($usuario){
            foreach ($carrito->muebles as $mueble) {
                if($mueble->stock >= $mueble->pivot->cantidad){
                    $mueble->stock -= $mueble->pivot->cantidad;
                    $mueble->save();
                }else{
                    return redirect()->back()->with('error', 'No hay stock suficiente para completar la compra del producto: ' . $mueble->nombre);
                }
            }
            $productosDelCarrito = $carrito->muebles;

            $preferencias = CookiePersonalizacion::getPersonalizacion(null, $usuario['datos']['usuario']['id']);
            $tema = $preferencias['tema'];
            $moneda = $preferencias['moneda'];
            return view('carrito.carritoFactura', compact('usuario','email' ,'productosDelCarrito', 'tema', 'moneda'));
        }else{
            return redirect()->route('login.mostrar')->with('error', 'debes iniciar sesion');
        }
    }

    public function returnFromBuy(Request $request, AuthApiService $authApiService){
        $usuario = $authApiService->validateToken(Session::get('api_token'));

        $carrito = Carrito::where('usuario_id', $usuario['datos']['usuario']['id'])->first();
        $carrito->muebles()->detach();

        return redirect()->route('muebles.index');
    }

    public function show(string $id){}
}
