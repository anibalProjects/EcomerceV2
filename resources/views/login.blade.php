<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>

    @php
        // Valor por defecto si la cookie no existe
        $tema = Cookie::has('tema_visual') ? Cookie::get('tema_visual') : 'claro';
    @endphp

    <link rel="stylesheet" href="{{ asset("css/{$tema}.css") }}">

</head>
<body class="login-page">
    <div class="login-container">
        <h1>Iniciar Sesión</h1>

        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('mensaje'))
            <div class="mensaje">{{ session('mensaje') }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="" required>
            </div>

            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" value="" required>
            </div>

            <!-- Selector de Tema Visual -->
            <div class="form-group theme-selector">
                <label>Tema Visual</label>
                <div class="theme-options">
                    <div class="theme-option">
                        <input type="radio" name="tema" id="tema-claro" value="claro" checked>
                        <label for="tema-claro">Claro</label>
                    </div>
                    <div class="theme-option">
                        <input type="radio" name="tema" id="tema-oscuro" value="oscuro">
                        <label for="tema-oscuro">Oscuro</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="moneda">Moneda</label>
                <select name="moneda" id="moneda">
                    <option value="EUR" {{ (Cookie::get('moneda') ?? 'EUR') == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                    <option value="USD" {{ (Cookie::get('moneda') ?? 'EUR') == 'USD' ? 'selected' : '' }}>Dólar ($)</option>
                    <option value="GBP" {{ (Cookie::get('moneda') ?? 'EUR') == 'GBP' ? 'selected' : '' }}>Libra (£)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="paginacion">Productos por página:</label>
                <select name="paginacion" id="paginacion">
                    <option value="6">6</option>
                    <option value="12">12</option>
                    <option value="24">24</option>
                </select>
            </div>

            <button type="submit" class="btn submit-btn">Entrar</button>
        </form>
        <a href="{{ route('usuarios.create') }}">Haz click Registro</a>
    </div>
</body>
</html>
