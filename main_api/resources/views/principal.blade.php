<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel de Usuario (Zona de usuario)</title>
</head>

<body>

@php
    $usuarios = Session::get('usuarios_sesion', []);
    //dd(session()->all());
@endphp
@if ($usuario)
    <h1>Pagina Prueba</h1>
    <p>Usuario: {{ $usuario->nombre }}</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Cerrar sesión</button>
    </form>
@else
    <p>Debes iniciar sesión para ver este contenido.</p>
@endif
</body>

</html>
