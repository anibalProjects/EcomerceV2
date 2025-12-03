@extends('layout.app')

@section('title', 'Dashboard Administrativo')

@section('content')

<div class="card">

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Dashboard Administrativo</h2>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('muebles.index', ['sesionId' => $sesionId]) }}" class="btn btn-secondary">
                <i class="bi bi-house-door-fill me-1"></i> Ir al Home
            </a>
            <form action="{{ route('logout', ['sesionId' => $sesionId]) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
            </form>
        </div>
    </div>

    <p>Bienvenido, desde aquí puedes gestionar los muebles de la tienda.</p>

    @if(session('success'))
        <div class="alert alert-success" style="color: green; margin-bottom: 10px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.muebles.create', ['sesionId' => $sesionId]) }}" class="btn btn-primary">
            Crear Nuevo Mueble
        </a>
    </div>

    {{-- Buscador --}}
    <form action="{{ route('admin.muebles.index') }}" method="GET" style="margin-bottom: 20px;">
        <input type="hidden" name="sesionId" value="{{ $sesionId }}">
        <input type="text" name="texto" placeholder="Buscar por nombre o descripción..." value="{{ request('texto') }}">
        <button type="submit" class="btn btn-secondary">Buscar</button>
    </form>

    <div>
        <h3>Listado de Muebles</h3>
        <div class="results-count">{{ count($muebles) }} resultados</div>

        <div class="table-container">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="border: 1px solid #ddd; padding: 8px;">ID</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Imagen</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Nombre</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Categoría</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Precio</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($muebles as $mueble)
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $mueble->id }}</td>

                        <td style="border: 1px solid #ddd; padding: 8px;">
                            @if($mueble->imagen_principal)
                                <img src="{{ asset('storage/muebles/' . $mueble->imagen_principal) }}" 
                                     alt="{{ $mueble->nombre }}" 
                                     style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <span>Sin imagen</span>
                            @endif
                        </td>

                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $mueble->nombre }}</td>

                        <td style="border: 1px solid #ddd; padding: 8px;">
                            {{ $mueble->categoria ? $mueble->categoria->nombre : 'Sin categoría' }}
                        </td>

                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $mueble->precio }}€</td>

                        <td class="actions-cell" style="border: 1px solid #ddd; padding: 8px;">
                            <a href="{{ route('admin.muebles.edit', [$mueble->id, 'sesionId' => $sesionId]) }}" class="btn btn-edit">
                                Editar
                            </a>

                            <a href="{{ route('admin.muebles.galeria', [$mueble->id, 'sesionId' => $sesionId]) }}" class="btn btn-secondary">
                                Galería
                            </a>

                            <form action="{{ route('admin.muebles.destroy', [$mueble->id, 'sesionId' => $sesionId]) }}" 
                                  method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete" onclick="return confirm('¿Estás seguro?')">
                                    Eliminar
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
