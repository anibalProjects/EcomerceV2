<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mueble;
use App\Models\Carrito;
use App\Services\AuthApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class carritoController extends Controller
{
    public function index(Request $request, AuthApiService $authApiService)
    {
        $token = Session::get('api_token');
        $usuario = $authApiService->validateToken($token);
        if ($usuario['estado'] === 200) {
        $sesionId = Session::get('usuario_logueado');
        }

        if($usuario){
             //Busco el carrito del usuario o si no lo creo
            $carrito = Carrito::firstOrCreate(
                ['usuario_id' => $usuario['datos']['usuario']['id']],
                ['sesionId' => $sesionId]
            );

            $total = 0;
            //Recorro muebles y recojo el precio de cada mueble y a total le sumo su cantidad gracias a la tabla intermedia
            foreach ($carrito->muebles as $mueble) {
                $total += $mueble->precio * $mueble->pivot->cantidad;
            }

            $preferencias = CookiePersonalizacion::getPersonalizacion($sesionId);
            $tema = $preferencias['tema'];
            $moneda = $preferencias['moneda'];

            return view('carrito.carritoView', ['sesionId' => $sesionId, 'productosDelCarrito' => $carrito->muebles, 'total' => $total, 'moneda' => $moneda, 'tema' => $tema]);
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
        if ($usuario['estado'] === 200) {
        }
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

    public function destroy(Request $request, $producto)
    {
        $sesionId = $request->input('sesionId');
        $usuario = User::buscarUsuario($sesionId);

        if ($usuario) {
            $carrito = Carrito::where('usuario_id', $usuario->id)->first();
            if ($carrito) {
                $carrito->muebles()->detach($producto);
                return redirect()->back()->with('success', 'Se ha eliminado el producto del carrito ');
            }else{
                return redirect()->back()->with('error', 'No se ha podido eliminar el producto del carrito');
            }
        }else{
            return redirect()->route('login.mostrar')->with('error', 'debes iniciar sesion');
        }
    }

    public function update(Request $request, $producto_id){
        //Datos del usuario
        $sesionId = $request->input('sesionId');
        $usuario = User::buscarUsuario($sesionId);
        $producto = Mueble::find($producto_id);
        $carrito = Carrito::where('usuario_id', $usuario->id)->first();

        $productoEnCarrito = $carrito->muebles()->where('mueble_id', $producto_id)->first();
        if($usuario){
            if( $productoEnCarrito ){
                if($request->increment){
                    $productoEnCarrito->pivot->cantidad++;
                    $productoEnCarrito->pivot->save();
                }

                if ($request->decrement) {
                    if ($productoEnCarrito->pivot->cantidad > 1) {
                        $productoEnCarrito->pivot->cantidad--;
                        $productoEnCarrito->pivot->save();
                    } else {
                        $carrito->muebles()->detach($producto_id);
                        return redirect()->back()->with('success', 'Se ha eliminado el producto del carrito ' . $producto->nombre);
                    }
                }
                return redirect()->back();
            }
        }else{
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

            /* $preferencias = CookiePersonalizacion::getPersonalizacion($sesionId);
            $tema = $preferencias['tema'];
            $moneda = $preferencias['moneda']; */
            $tema = "claro";
            $moneda = "euro";
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
