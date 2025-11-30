<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vista de prueba</title>
</head>

<body>
    <h2>listado de muebles</h2>
    <form action="{{ route('carrito.index') }}">
        @csrf
        <input type="hidden" name="sesionId" value="{{ $sesionId }}">
        <button>Ver Carrito</button>
    </form>
    @foreach ($muebles as $m)
        <form action="{{ route('carrito.store', ['sesionId' => $sesionId]) }}" method="POST">
            @csrf
            <div>
                <input type="hidden" name="producto_id" value="{{ $m->id }}">
                <h5>{{ $m->nombre }}</h5>
                <p>{{ $m->precio }}€</p>
                <input  type="number" name="cantidad" value="1"/><br>
                <button>añadir</button>
            </div>
        </form>
    @endforeach
</body>

</html>
