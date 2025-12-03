@php
    $tema = Cookie::get('tema_visual', 'claro');
    $layout = ($tema === 'oscuro') ? 'layout.oscuro' : 'layout.app';
@endphp

@extends($layout)

@section('title', 'Crear Nuevo Mueble')

@push('head')
<style>
    .form-section {
        background: var(--card-bg, #fff);
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(201,168,75,0.08);
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid var(--accent-border, #e9e9e9);
    }
    .form-label { font-weight: 500; color: var(--light-text, #666); }
    .form-control, .form-select { border-radius: 6px; }
    .btn-group-mueble { display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem; }
    .card-title { font-family: "Playfair Display", serif; font-size: 2rem; color: var(--gold, #ad8516); }
    .input-group-text { background: #fafafa; border-radius: 6px 0 0 6px; }
    .form-check-label { font-weight: 400; }
    .form-check-input:checked { background-color: var(--gold); border-color: var(--gold); }
    @media (max-width: 576px) {
        .form-section { padding: 1rem; }
        .card-title { font-size: 1.3rem; }
    }
</style>
@endpush

@section('content')
<div class="content-container">
    <div class="nav-wrapper mb-4">
        <div class="nav-centered">
            <span class="lux-brand">LECTONIC</span>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-section">
                <h2 class="card-title mb-4 text-center"><i class="bi bi-plus-circle me-2"></i>Crear Nuevo Mueble</h2>

                @if($errors->any())
                    <div class="alert alert-danger small mb-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.muebles.store', ['sesionId' => $sesionId]) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required maxlength="255" class="form-control form-control-lg py-2">
                        @error('nombre') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="categoria_id" class="form-label">Categoría</label>
                        <select id="categoria_id" name="categoria_id" required class="form-select form-select-lg py-2">
                            <option value="">Seleccione una categoría</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('categoria_id') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea id="descripcion" name="descripcion" required class="form-control form-control-lg py-2">{{ old('descripcion') }}</textarea>
                        @error('descripcion') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input type="number" id="precio" name="precio" value="{{ old('precio') }}" step="0.01" min="0" required class="form-control form-control-lg py-2">
                            </div>
                            @error('precio') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" id="stock" name="stock" value="{{ old('stock') }}" min="0" required class="form-control form-control-lg py-2">
                            @error('stock') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label for="color_principal" class="form-label">Color Principal</label>
                            <input type="text" id="color_principal" name="color_principal" value="{{ old('color_principal') }}" required class="form-control form-control-lg py-2">
                            @error('color_principal') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="materiales" class="form-label">Materiales</label>
                            <textarea id="materiales" name="materiales" required class="form-control form-control-lg py-2">{{ old('materiales') }}</textarea>
                            @error('materiales') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="dimensiones" class="form-label">Dimensiones</label>
                        <textarea id="dimensiones" name="dimensiones" required class="form-control form-control-lg py-2">{{ old('dimensiones') }}</textarea>
                        @error('dimensiones') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="imagen_principal" class="form-label">Imagen Principal</label>
                        <input type="file" id="imagen_principal" name="imagen_principal" accept="image/*" class="form-control form-control-lg py-2">
                        @error('imagen_principal') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4 form-check mb-2">
                            <input type="checkbox" name="destacado" class="form-check-input" id="destacado" {{ old('destacado') ? 'checked' : '' }}>
                            <label for="destacado" class="form-check-label">Destacado</label>
                        </div>
                        <div class="col-md-4 form-check mb-2">
                            <input type="checkbox" name="novedad" class="form-check-input" id="novedad" {{ old('novedad') ? 'checked' : '' }}>
                            <label for="novedad" class="form-check-label">Novedad</label>
                        </div>
                        <div class="col-md-4 form-check mb-3">
                            <input type="checkbox" name="activo" class="form-check-input" id="activo" {{ old('activo', true) ? 'checked' : '' }}>
                            <label for="activo" class="form-check-label">Activo</label>
                        </div>
                    </div>

                    <div class="btn-group-mueble">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Crear Mueble
                        </button>
                        <a href="{{ route('admin.muebles.index', ['sesionId' => $sesionId]) }}" class="btn cart-btn">
                            <i class="bi bi-arrow-left me-1"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
