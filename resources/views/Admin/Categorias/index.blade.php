@php
    $tema = Cookie::get('tema_visual', 'claro');
@endphp

@extends($tema === 'oscuro' ? 'layout.oscuro' : 'layout.app')

@section('title', 'Gestión de Categorías')

@section('content')

<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Gestión de Categorías</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('muebles.index', ['sesionId' => $sesionId]) }}" class="btn btn-secondary">Volver al Inicio</a>
            <form action="{{ route('logout', ['sesionId' => $sesionId]) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
            </form>
        </div>
    </div>
    <p>Administra las categorías de productos de la tienda.</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Tabs Navigation --}}
    <div class="mb-3">
        <a href="{{ route('admin.muebles.index', ['sesionId' => $sesionId]) }}" class="btn btn-secondary me-2">Muebles</a>
        <a href="{{ route('admin.categorias.index', ['sesionId' => $sesionId]) }}" class="btn btn-primary">Categorías</a>
    </div>

    <div class="mb-3">
        <a href="{{ route('admin.categorias.create', ['sesionId' => $sesionId]) }}" class="btn btn-primary">Crear Nueva Categoría</a>
    </div>

    {{-- Buscador --}}
    <form action="{{ route('admin.categorias.index') }}" method="GET" class="d-flex mb-3 gap-2">
        <input type="hidden" name="sesionId" value="{{ $sesionId }}">
        <input type="text" name="texto" placeholder="Buscar por nombre o descripción..." value="{{ request('texto') }}" class="form-control w-50">
        <button type="submit" class="btn btn-secondary">Buscar</button>
    </form>

    <h3>Listado de Categorías</h3>
    <div class="results-count mb-2">{{ count($categorias) }} resultados</div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Productos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach($categorias as $categoria)
                <tr>
                    <td>{{ $categoria->id }}</td>
                    <td>{{ $categoria->nombre }}</td>
                    <td>{{ $categoria->descripcion }}</td>
                    <td>{{ $categoria->muebles->count() }}</td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('admin.categorias.edit', [$categoria->id, 'sesionId' => $sesionId]) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('admin.categorias.destroy', [$categoria->id, 'sesionId' => $sesionId]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
