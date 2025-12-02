<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mueble;
use App\Models\Carrito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class carritoController extends Controller
{
    public function index(Request $request)
    {
        $sesionId = $request->input('sesionId');
        $usuario = User::buscarUsuario($sesionId);

        if($usuario){
             //Busco el carrito del usuario o si no lo creo
            $carrito = Carrito::firstOrCreate(
                ['usuario_id' => $usuario->id],
                ['sesionId' => $sesionId]
            );

            $total = 0;
            //Recorro muebles y recojo el precio de cada mueble y a total le sumo su cantidad gracias a la tabla intermedia
            foreach ($carrito->muebles as $mueble) {
                $total += $mueble->precio * $mueble->pivot->cantidad;
            }

            return view('carritoView', ['sesionId' => $sesionId, 'productosDelCarrito' => $carrito->muebles, 'total' => $total]);
        }else{
            return redirect()->route('login.mostrar')->with('error', 'debes iniciar sesion para ver el carrito');
        }
    }

    public function store(Request $request){

        $request->validate([
            'cantidad' => 'required|int|min:1|max:10'
        ]);


        //Datos del usuario
        $sesionId = $request->input('sesionId');
        $usuario = User::buscarUsuario($sesionId);
        if($usuario){
                //Busco el carrito del usuario o si no lo creo
                $carrito = Carrito::firstOrCreate(
                    ['usuario_id' => $usuario->id],
                    ['sesionId' => $sesionId]
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
                    return redirect()->back()->with('success', 'Producto añadido al carrito.');
                } else {
                    return redirect()->back()->with('error', 'No hay stock suficiente.');
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
                return redirect()->back()->with('success', 'Producto eliminado del carrito');
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
}
