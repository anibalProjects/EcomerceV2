<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mueble;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class carritoController extends Controller
{
    public function index(Request $request)
    {
        $sesionId = $request->input('sesionId');
        $usuario = User::buscarUsuario($sesionId);

        dd($user = Auth::user());

        $carrito = Session::get('carrito_'. $usuario->id, []);
        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        return view('carritoView', compact('sesionId','carrito', 'total'));
    }

    public function store(Request $request){

        $request->validate([
            'cantidad' => 'required|int|min:1|max:10'
        ]);
        //Datos del usuario
        $sesionId = $request->query('sesionId');
        $usuario = User::buscarUsuario($sesionId);
        //Asignar carrito a cada usuario
        $carrito = Session::get('carrito_'.$usuario->id, []);
        //buscar el producto que nos solicita
        $producto_id = $request->producto_id;
        $producto = Mueble::find($producto_id);
        $cantidad = (int) $request->cantidad;

        //comprobar que hay stock y si producto estaba ya en el carrito
        if ($producto['stock'] >= $cantidad) {

            if(isset($carrito[$producto_id])){
                $carrito[$producto_id]['cantidad'] += $cantidad ;
            } else {
                $carrito[$producto_id] = [
                    'nombre' => $producto->nombre,
                    'precio' => $producto->precio
                    //Aqui iria la imagen o la galeria
                ];
            }
        }
          $carrito[$producto_id] = $producto;
        Session::put('carrito_'.$usuario->id, $carrito);
        return redirect()->back()->with('success', 'Producto añadido al carrito.');
    }

    public function show(string $id){}
    public function edit(string $id){}
    public function update(Request $request, string $id){}

    public function destroy(Request $request, $producto_id)
    {
        $sesionId = $request->input('sesionId');
        $usuario = User::buscarUsuario($sesionId);

        if ($usuario) {
            $carrito = Session::get('carrito_' . $usuario->id, []);
            unset($carrito[$producto_id]);

            Session::put('carrito_' . $usuario->id, $carrito);
        }

        return redirect()->back()->with('success', 'Producto eliminado del carrito.');
    }

    public function empty(Request $request)
    {
        $sesionId = $request->input('sesionId');
        $usuario = User::buscarUsuario($sesionId);

        if ($usuario) {
            Session::forget('carrito_'.$usuario->id);
        }

         return redirect()->route('carrito.index', ['sesionId' => $sesionId]);
    }
}
