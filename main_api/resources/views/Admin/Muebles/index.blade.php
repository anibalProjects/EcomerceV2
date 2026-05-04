
@extends('layout.app')

@section('title', 'Listado de Muebles')

@push('head')
<style>
.table-muebles {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-top: 2rem;
    font-size: 1rem;
}
.table-muebles th, .table-muebles td {
    padding: 1rem 0.7rem;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}
.table-muebles th {
    background: #fafafa;
    font-weight: 700;
    color: #ad8516;
    font-size: 1.05rem;
}
.table-muebles td img {
    width: 90px;
    height: 70px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #ddd;
    background: #fff;
}
.badge-categoria {
    background: #f1f1f1;
    color: #333;
    font-size: 0.85rem;
    border-radius: 6px;
    padding: 0.2rem 0.7rem;
}
@media (max-width: 576px) {
    .table-muebles th, .table-muebles td { padding: 0.5rem 0.3rem; font-size: 0.95rem; }
    .table-muebles td img { width: 60px; height: 45px; }
}
</style>
@endpush

@section('content')
<div class="content-container">
    <div class="nav-wrapper mb-4">
        <div class="nav-centered">
            <span class="lux-brand">LECTONIC</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.muebles.create', ['sesionId' => $sesionId]) }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Mueble
            </a>
            <a href="{{ route('admin.categorias.index', ['sesionId' => $sesionId]) }}" class="btn btn-secondary">
                <i class="bi bi-tags-fill me-1"></i> Categorías
            </a>
            <a href="{{ route('muebles.index', ['sesionId' => $sesionId]) }}" class="btn btn-outline-dark">
                <i class="bi bi-house-door-fill me-1"></i> Ir al Home
            </a>
        </div>
    </div>

    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="lux-brand mb-0">Listado de Muebles</h2>
            <form action="{{ route('admin.muebles.index') }}" method="GET" class="d-flex gap-2">
                <input type="hidden" name="sesionId" value="{{ $sesionId }}">
                <input type="text" name="texto" placeholder="Buscar por nombre o descripción..." value="{{ request('texto') }}" class="form-control w-50">
                <button type="submit" class="btn btn-secondary">Buscar</button>
            </form>
        </div>

        <div class="results-count mb-2">{{ count($muebles) }} resultados</div>

        @if($muebles->isEmpty())
            <div class="alert alert-warning">No hay muebles registrados.</div>
        @else
            <div class="table-responsive">
                <table class="table-muebles">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Categoría</th>
                            <th>Novedad</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($muebles as $mueble)
                            @php
                                $imgUrl = $mueble->imagen_principal
                                    ? asset('storage/muebles/' . $mueble->imagen_principal)
                                    : asset('images/muebles/placeholder.jpg');
                            @endphp
                            <tr>
                                <td>
                                    <img src="{{ $imgUrl }}" alt="{{ $mueble->nombre }}">
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $mueble->nombre }}</div>
                                    <div class="small-muted">{{ Str::limit($mueble->descripcion, 60) }}</div>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">{{ number_format($mueble->precio, 2, ',', '.') }} €</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">Stock: {{ $mueble->stock }}</span>
                                </td>
                                <td>
                                    <span class="badge-categoria">{{ $mueble->categoria ? $mueble->categoria->nombre : 'Sin categoría' }}</span>
                                </td>
                                <td>
                                    @if($mueble->novedad)
                                        <span class="badge bg-danger"><i class="bi bi-star-fill me-1"></i>Novedad</span>
                                    @endif
                                </td>
                                <td class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('admin.muebles.edit', [$mueble->id, 'sesionId' => $sesionId]) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-fill"></i> Editar
                                    </a>
                                    <a href="{{ route('admin.muebles.galeria', [$mueble->id, 'sesionId' => $sesionId]) }}" class="btn btn-secondary btn-sm">
                                        <i class="bi bi-images"></i> Galería
                                    </a>
                                    <form action="{{ route('admin.muebles.destroy', [$mueble->id, 'sesionId' => $sesionId]) }}" method="POST" onsubmit="return confirm('¿Estás seguro?')">
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
        @endif
    </div>
</div>
@endsection
