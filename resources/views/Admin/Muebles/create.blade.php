<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Mueble</title>
    @php
        $tema = Cookie::get('tema_visual', 'claro');
    @endphp
    <link rel="stylesheet" href="{{ asset("css/{$tema}.css") }}">
</head>
<body>
    <div class="card">
        <h2>Crear Nuevo Mueble</h2>

        <form method="POST" action="{{ route('admin.muebles.store', ['sesionId' => $sesionId]) }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required maxlength="255">
                @error('nombre') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="categoria_id">Categoría</label>
                <select id="categoria_id" name="categoria_id" required>
                    <option value="">Seleccione una categoría</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('categoria_id') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" required>{{ old('descripcion') }}</textarea>
                @error('descripcion') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="precio">Precio</label>
                <input type="number" id="precio" name="precio" value="{{ old('precio') }}" step="0.01" min="0" required>
                @error('precio') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" id="stock" name="stock" value="{{ old('stock') }}" min="0" required>
                @error('stock') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="color_principal">Color Principal</label>
                <input type="text" id="color_principal" name="color_principal" value="{{ old('color_principal') }}" required>
                @error('color_principal') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="materiales">Materiales</label>
                <textarea id="materiales" name="materiales" required>{{ old('materiales') }}</textarea>
                @error('materiales') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="dimensiones">Dimensiones</label>
                <textarea id="dimensiones" name="dimensiones" required>{{ old('dimensiones') }}</textarea>
                @error('dimensiones') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="imagen_principal">Imagen Principal</label>
                <input type="file" id="imagen_principal" name="imagen_principal" accept="image/*">
                @error('imagen_principal') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="destacado" {{ old('destacado') ? 'checked' : '' }}> Destacado
                </label>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="activo" {{ old('activo', true) ? 'checked' : '' }}> Activo
                </label>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">Crear Mueble</button>
                <a href="{{ route('admin.muebles.index', ['sesionId' => $sesionId]) }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
