@php
    $tema = Cookie::get('tema_visual', 'claro');
@endphp

@extends($tema === 'oscuro' ? 'layout.oscuro' : 'layout.app')

@section('title', 'Gestión de Categorías')

@push('head')
<style>
    .card { border-radius: 10px; }
    .results-count { color: var(--light-text, #888); font-size: 0.95rem; }
    .table th, .table td { vertical-align: middle !important; }
    .btn-sm { padding: 0.25rem 0.7rem; font-size: 0.95rem; }
    .categoria-desc { max-width: 320px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .categoria-nombre { font-weight: 600; font-size: 1.1rem; }
    .categoria-row:hover { background: rgba(201,168,75,0.07); }
    @media (max-width: 576px) {
        .table-responsive { font-size: 0.95rem; }
        .categoria-desc { max-width: 120px; }
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

    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="lux-brand mb-0">Gestión de Categorías</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('muebles.index', ['sesionId' => $sesionId]) }}" class="btn btn-secondary">
                    <i class="bi bi-house-door-fill me-1"></i> Ir al Home
                </a>
                <form action="{{ route('logout', ['sesionId' => $sesionId]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
                </form>
            </div>
        </div>
        <p class="mb-3 small-muted">Administra las categorías de productos de la tienda.</p>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="mb-3 d-flex gap-2">
            <a href="{{ route('admin.categorias.create', ['sesionId' => $sesionId]) }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Nueva Categoría
            </a>
            <form action="{{ route('admin.categorias.index') }}" method="GET" class="d-flex gap-2">
                <input type="hidden" name="sesionId" value="{{ $sesionId }}">
                <input type="text" name="texto" placeholder="Buscar por nombre o descripción..." value="{{ request('texto') }}" class="form-control w-50">
                <button type="submit" class="btn btn-secondary">Buscar</button>
            </form>
        </div>

        <div class="results-count mb-2">{{ count($categorias) }} resultados</div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Productos</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($categorias as $categoria)
                    <tr class="categoria-row">
                        <td class="text-muted">#{{ str_pad($categoria->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="categoria-nombre">{{ $categoria->nombre }}</td>
                        <td class="categoria-desc">{{ Str::limit($categoria->descripcion, 60) }}</td>
                        <td>
                            <span class="badge bg-info">{{ $categoria->muebles->count() }}</span>
                        </td>
                        <td class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('admin.categorias.edit', [$categoria->id, 'sesionId' => $sesionId]) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-fill"></i> Editar
                            </a>
                            <form action="{{ route('admin.categorias.destroy', [$categoria->id, 'sesionId' => $sesionId]) }}" method="POST" onsubmit="return confirm('¿Estás seguro?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash-fill"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
