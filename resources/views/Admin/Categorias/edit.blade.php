@php
    $tema = Cookie::get('tema_visual', 'claro');
    $layout = ($tema === 'oscuro') ? 'layout.oscuro' : 'layout.app';
@endphp

@extends($layout)

@section('title', 'Editar Categoría')

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
    .btn-group-cat { display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem; }
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
                <h2 class="card-title mb-4 text-center"><i class="bi bi-pencil-fill me-2"></i>Editar Categoría</h2>

                @if($errors->any())
                    <div class="alert alert-danger small mb-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.categorias.update', [$categoria->id, 'sesionId' => $sesionId]) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $categoria->nombre) }}" required maxlength="255" class="form-control form-control-lg py-2">
                        @error('nombre') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea id="descripcion" name="descripcion" required class="form-control form-control-lg py-2">{{ old('descripcion', $categoria->descripcion) }}</textarea>
                        @error('descripcion') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="btn-group-cat">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-pencil-fill me-1"></i> Actualizar Categoría
                        </button>
                        <a href="{{ route('admin.categorias.index', ['sesionId' => $sesionId]) }}" class="btn cart-btn">
                            <i class="bi bi-arrow-left me-1"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection