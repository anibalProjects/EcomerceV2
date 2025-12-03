<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categorías</title>
    @php
        $tema = Cookie::get('tema_visual', 'claro');
    @endphp
    <link rel="stylesheet" href="{{ asset("css/{$tema}.css") }}">
</head>
<body>
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2>Gestión de Categorías</h2>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('muebles.index', ['sesionId' => $sesionId]) }}" class="btn btn-secondary">Volver al Inicio</a>
                <form action="{{ route('logout', ['sesionId' => $sesionId]) }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn cart-btn">Cerrar Sesión</button>
                </form>
            </div>
        </div>
        <p>Administra las categorías de productos de la tienda.</p>

        @if(session('success'))
            <div class="alert alert-success" style="color: green; margin-bottom: 10px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" style="color: red; margin-bottom: 10px;">
                {{ session('error') }}
            </div>
        @endif

        {{-- Tabs Navigation --}}
        <div style="margin-bottom: 20px; border-bottom: 2px solid #ddd;">
            <a href="{{ route('admin.muebles.index', ['sesionId' => $sesionId]) }}" class="btn btn-secondary" style="margin-right: 10px;">
                Muebles
            </a>
            <a href="{{ route('admin.categorias.index', ['sesionId' => $sesionId]) }}" class="btn btn-primary">
                Categorías
            </a>
        </div>

        <div style="margin-bottom: 20px;">
            <a href="{{ route('admin.categorias.create', ['sesionId' => $sesionId]) }}" class="btn btn-primary">
                Crear Nueva Categoría
            </a>
        </div>

        {{-- Buscador --}}
        <form action="{{ route('admin.categorias.index') }}" method="GET" style="margin-bottom: 20px;">
            <input type="hidden" name="sesionId" value="{{ $sesionId }}">
            <input type="text" name="texto" placeholder="Buscar por nombre o descripción..." value="{{ request('texto') }}">
            <button type="submit" class="btn btn-secondary">Buscar</button>
        </form>

        <div>
            <h3>Listado de Categorías</h3>
            <div class="results-count">{{ count($categorias) }} resultados</div>

            <div class="table-container">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid #ddd; padding: 8px;">ID</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">Nombre</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">Descripción</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">Productos</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($categorias as $categoria)
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $categoria->id }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $categoria->nombre }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $categoria->descripcion }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $categoria->muebles->count() }}</td>
                            <td class="actions-cell" style="border: 1px solid #ddd; padding: 8px;">
                                <a href="{{ route('admin.categorias.edit', [$categoria->id, 'sesionId' => $sesionId]) }}" class="btn btn-edit">Editar</a>
                                <form action="{{ route('admin.categorias.destroy', [$categoria->id, 'sesionId' => $sesionId]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
