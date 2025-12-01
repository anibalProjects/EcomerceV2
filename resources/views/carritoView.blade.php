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

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
    @if ($productosDelCarrito->isNotEmpty())
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio Unitario</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productosDelCarrito as $item)
                    <tr>
                        <td>{{ $item->nombre }}</td>
                        <td>{{ number_format($item->precio, 2, ',', '.') }}€</td>
                        <td>{{ $item->pivot->cantidad }}</td>
                        <td>{{ number_format($item->precio * $item->pivot->cantidad, 2, ',', '.') }}€</td>
                        <td>
                            <form action="{{ route('carrito.destroy', ['carrito' => $item->id, 'sesionId' => $sesionId]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <h2>Total: {{ number_format($total, 2, ',', '.') }}€</h2>
        <form action="{{ route('carrito.empty', ['sesionId' => $sesionId]) }}" method="POST">
            @csrf
            <button>Vaciar Carrito</button>
        </form>
    @else
        <div>Tu carrito está vacío.</div>
    @endif

    <br>
    <a href="{{ route('muebles.index', ['sesionId' => $sesionId]) }}">← Seguir comprando</a>
</body>
</html>
