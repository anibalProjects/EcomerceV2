<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <title>Mi Carrito</title>
</head>
<body>

    <h1>Mi Carrito de Compras</h1>
    @if (!empty($carrito))
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio Unitario</th>
                    <th>Cantidad</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($carrito as $item)
                    <tr>
                        <td>{{ $item['nombre'] }}</td>
                        <td>{{ number_format($item['precio'], 2, ',', '.') }}€</td>
                        <td>{{ $item['cantidad'] }}</td>                        <td>
                            <form action="{{ route('carrito.destroy', ['carrito' => $item->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="sesionId" value="{{ $sesionId }}">
                                <button type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <h2>Total: {{ number_format($total, 2, ',', '.') }}€</h2>
        <form action="{{ route('carrito.empty') }}" method="POST">
            @csrf
            <input type="hidden" name="sesionId" value="{{ $sesionId }}">
            <button>Vaciar Carrito</button>
        </form>
    @else
        <div>Tu carrito está vacío.</div>
    @endif

    <br>
    <a href="{{ url('/') }}">← Seguir comprando</a>
</body>
</html>
