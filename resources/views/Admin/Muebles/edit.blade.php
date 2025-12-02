<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mueble</title>
    @php
        $tema = Cookie::get('tema_visual', 'claro');
    @endphp
    <link rel="stylesheet" href="{{ asset("css/{$tema}.css") }}">
</head>
<body>
    <div class="card">
        <h2>Editar Mueble</h2>

        <form method="POST" action="{{ route('admin.muebles.update', [$mueble->id, 'sesionId' => $sesionId]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $mueble->nombre) }}" required maxlength="255">
                @error('nombre') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="categoria_id">Categoría</label>
                <select id="categoria_id" name="categoria_id" required>
                    <option value="">Seleccione una categoría</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ old('categoria_id', $mueble->categoria_id) == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('categoria_id') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" required>{{ old('descripcion', $mueble->descripcion) }}</textarea>
                @error('descripcion') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="precio">Precio</label>
                <input type="number" id="precio" name="precio" value="{{ old('precio', $mueble->precio) }}" step="0.01" min="0" required>
                @error('precio') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" id="stock" name="stock" value="{{ old('stock', $mueble->stock) }}" min="0" required>
                @error('stock') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="color_principal">Color Principal</label>
                <input type="text" id="color_principal" name="color_principal" value="{{ old('color_principal', $mueble->color_principal) }}" required>
                @error('color_principal') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="materiales">Materiales</label>
                <textarea id="materiales" name="materiales" required>{{ old('materiales', $mueble->materiales) }}</textarea>
                @error('materiales') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="dimensiones">Dimensiones</label>
                <textarea id="dimensiones" name="dimensiones" required>{{ old('dimensiones', $mueble->dimensiones) }}</textarea>
                @error('dimensiones') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="imagen_principal">Imagen Principal</label>
                @if($mueble->imagen_principal)
                    <div style="margin-bottom: 10px;">
                        <img src="{{ asset('storage/muebles/' . $mueble->imagen_principal) }}" alt="Actual" style="max-width: 100px;">
                    </div>
                @endif
                <input type="file" id="imagen_principal" name="imagen_principal" accept="image/*">
                @error('imagen_principal') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="destacado" {{ old('destacado', $mueble->destacado) ? 'checked' : '' }}> Destacado
                </label>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="activo" {{ old('activo', $mueble->activo) ? 'checked' : '' }}> Activo
                </label>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">Actualizar Mueble</button>
                <a href="{{ route('admin.muebles.index', ['sesionId' => $sesionId]) }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
