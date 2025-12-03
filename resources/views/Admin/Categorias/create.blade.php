<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Categoría</title>
    @php
        $tema = Cookie::get('tema_visual', 'claro');
    @endphp
    <link rel="stylesheet" href="{{ asset("css/{$tema}.css") }}">
</head>
<body>
    <div class="card">
        <h2>Crear Nueva Categoría</h2>

        <form method="POST" action="{{ route('admin.categorias.store', ['sesionId' => $sesionId]) }}">
            @csrf

            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required maxlength="255">
                @error('nombre') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion">{{ old('descripcion') }}</textarea>
                @error('descripcion') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">Crear Categoría</button>
                <a href="{{ route('admin.categorias.index', ['sesionId' => $sesionId]) }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
