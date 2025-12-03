@extends('layout.app')

@section('title', 'Dashboard Administrativo')

@section('content')

<div class="card p-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Dashboard Administrativo</h2>
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

    <p>Bienvenido, desde aquí puedes gestionar los muebles de la tienda.</p>

    {{-- Tabs Navigation --}}
    <div class="mb-3">
        <a href="{{ route('admin.muebles.index', ['sesionId' => $sesionId]) }}" class="btn btn-primary me-2">Muebles</a>
        <a href="{{ route('admin.categorias.index', ['sesionId' => $sesionId]) }}" class="btn btn-secondary">Categorías</a>
    </div>

    <div class="mb-3">
        <a href="{{ route('admin.muebles.create', ['sesionId' => $sesionId]) }}" class="btn btn-primary">Crear Nuevo Mueble</a>
    </div>

    {{-- Buscador --}}
    <form action="{{ route('admin.muebles.index') }}" method="GET" class="d-flex mb-3 gap-2">
        <input type="hidden" name="sesionId" value="{{ $sesionId }}">
        <input type="text" name="texto" placeholder="Buscar por nombre o descripción..." value="{{ request('texto') }}" class="form-control w-50">
        <button type="submit" class="btn btn-secondary">Buscar</button>
    </form>

    <h3>Listado de Muebles</h3>
    <div class="results-count mb-2">{{ count($muebles) }} resultados</div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach($muebles as $mueble)
                <tr>
                    <td>{{ $mueble->id }}</td>
                    <td>
                        @if($mueble->imagen_principal)
                            <img src="{{ asset('storage/muebles/' . $mueble->imagen_principal) }}" 
                                 alt="{{ $mueble->nombre }}" 
                                 style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            <span>Sin imagen</span>
                        @endif
                    </td>
                    <td>{{ $mueble->nombre }}</td>
                    <td>{{ $mueble->categoria ? $mueble->categoria->nombre : 'Sin categoría' }}</td>
                    <td>{{ $mueble->precio }}€</td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('admin.muebles.edit', [$mueble->id, 'sesionId' => $sesionId]) }}" class="btn btn-warning btn-sm">Editar</a>
                        <a href="{{ route('admin.muebles.galeria', [$mueble->id, 'sesionId' => $sesionId]) }}" class="btn btn-secondary btn-sm">Galería</a>
                        <form action="{{ route('admin.muebles.destroy', [$mueble->id, 'sesionId' => $sesionId]) }}" method="POST">
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
