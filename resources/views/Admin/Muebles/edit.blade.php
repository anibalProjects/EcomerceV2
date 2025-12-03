@extends('layout.app')

@section('title', 'Editar Mueble')

@section('content')

<div class="card p-4">
    <h2 class="mb-4 text-center">Editar Mueble</h2>

    <form method="POST" action="{{ route('admin.muebles.update', [$mueble->id, 'sesionId' => $sesionId]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mx-auto w-50">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $mueble->nombre) }}" required maxlength="255" class="form-control form-control-lg py-2">
                @error('nombre') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="categoria_id" class="form-label">Categoría</label>
                <select id="categoria_id" name="categoria_id" required class="form-select form-select-lg py-2">
                    <option value="">Seleccione una categoría</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ old('categoria_id', $mueble->categoria_id) == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('categoria_id') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea id="descripcion" name="descripcion" required class="form-control form-control-lg py-2">{{ old('descripcion', $mueble->descripcion) }}</textarea>
                @error('descripcion') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" id="precio" name="precio" value="{{ old('precio', $mueble->precio) }}" step="0.01" min="0" required class="form-control form-control-lg py-2">
                @error('precio') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" id="stock" name="stock" value="{{ old('stock', $mueble->stock) }}" min="0" required class="form-control form-control-lg py-2">
                @error('stock') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="color_principal" class="form-label">Color Principal</label>
                <input type="text" id="color_principal" name="color_principal" value="{{ old('color_principal', $mueble->color_principal) }}" required class="form-control form-control-lg py-2">
                @error('color_principal') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="materiales" class="form-label">Materiales</label>
                <textarea id="materiales" name="materiales" required class="form-control form-control-lg py-2">{{ old('materiales', $mueble->materiales) }}</textarea>
                @error('materiales') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="dimensiones" class="form-label">Dimensiones</label>
                <textarea id="dimensiones" name="dimensiones" required class="form-control form-control-lg py-2">{{ old('dimensiones', $mueble->dimensiones) }}</textarea>
                @error('dimensiones') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="imagen_principal" class="form-label">Imagen Principal</label>
                @if($mueble->imagen_principal)
                    <div class="mb-2">
                        <img src="{{ asset('storage/muebles/' . $mueble->imagen_principal) }}" alt="Actual" style="max-width: 100px;">
                    </div>
                @endif
                <input type="file" id="imagen_principal" name="imagen_principal" accept="image/*" class="form-control form-control-lg py-2">
                @error('imagen_principal') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-check mb-2">
                <input type="checkbox" name="destacado" class="form-check-input" id="destacado" {{ old('destacado', $mueble->destacado) ? 'checked' : '' }}>
                <label for="destacado" class="form-check-label">Destacado</label>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="activo" class="form-check-input" id="activo" {{ old('activo', $mueble->activo) ? 'checked' : '' }}>
                <label for="activo" class="form-check-label">Activo</label>
            </div>

            <div class="d-flex justify-content-center gap-2">
                <button type="submit" class="btn btn-primary">Actualizar Mueble</button>
                <a href="{{ route('admin.muebles.index', ['sesionId' => $sesionId]) }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>
</div>

@endsection
