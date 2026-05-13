@php
    // asegurarse de que $tema tiene valor ('claro' por defecto)
    $tema = $tema ?? 'claro';
    $layout = ($tema === 'oscuro') ? 'layout.oscuro' : 'layout.app';
@endphp

@extends($layout)

@section('title', 'Filtrar y Ordenar Muebles')

@section('content')

<style>
    .active-filters-container .filter-badge {
        font-weight: 500;
        letter-spacing: 0.3px;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        display: inline-flex;
        align-items: center;
        font-size: 0.85rem;

        @if($tema === 'oscuro')
            background-color: var(--dark-card);
            color: var(--light-text);
            border: 1px solid var(--dark-border);
        @else
            background-color: #ffffff;
            color: var(--dark-text);
            border: 1px solid #ddd;
        @endif
    }

    .active-filters-container .filter-badge:hover {
        @if($tema === 'oscuro')
            background-color: var(--dark-surface);
            border-color: var(--gold);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        @else
            background-color: #f8f9fa;
            border-color: var(--gold);
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        @endif
    }

    .active-filters-container .badge-novedad {
        background-color: @if($tema === 'oscuro') rgba(173, 133, 22, 0.2) @else #fff3cd @endif !important;
        color: @if($tema === 'oscuro') var(--gold-light) @else #856404 @endif !important;
        border: 1px solid var(--gold) !important;
    }

    .active-filters-container .badge-orden {
        background-color: @if($tema === 'oscuro') rgba(13, 110, 253, 0.1) @else #cfe2ff @endif !important;
        color: @if($tema === 'oscuro') #6ea8fe @else #084298 @endif !important;
        border: 1px solid @if($tema === 'oscuro') #0d6efd @else #b6d4fe @endif !important;
    }

    .hover-scale:hover {
        transform: scale(1.05);
    }
    .transition-all {
        transition: all 0.3s ease;
    }
    .color-dot {
        box-shadow: 0 0 2px rgba(0,0,0,0.2);
    }
</style>

<div class="content-container">
    <div class="d-flex justify-content-between align-items-center pt-4 pb-4 nav-centered-brand"
         style="border-bottom: 1px solid var(--nav-border); position: relative;">

        <div class="d-flex align-items-center">
            <nav class="nav-links-desktop d-none d-md-flex align-items-center">
                <a href="{{ route('muebles.index') }}" class="me-3">
                    <img src="{{ asset('img/Logo png.png') }}" alt="LECTONIC" style="height:70px; object-fit:contain;">
                </a>
            </nav>
        </div>

        <div class="lux-brand centered d-none d-md-block">LECTONIC</div>

        <div class="d-flex align-items-center">
            <div class="d-flex align-items-center">
                @if ($usuario)
    <!-- Botón de carrito a la izquierda del usuario -->
    <a href="{{ route('carrito.index') }}"
       class="btn btn-primary d-flex align-items-center me-2">
        <i class="bi bi-cart-fill me-1"></i> Ver carrito
    </a>
    <!-- Botón de usuario -->
    <button class="btn btn-primary d-flex align-items-center dropdown-toggle"
            type="button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            style="letter-spacing: normal; padding-right: 1.5rem;">
        <i class="bi bi-person-circle me-1"></i> {{ $usuario->nombre }}
    </button>
    <!-- Menú desplegable solo con preferencias y logout -->
    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <a class="dropdown-item" href="{{ route('perfil.show') }}">
                <i class="bi bi-person-lines-fill me-2"></i> Mi perfil
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('preferencias.index', ['userId' => $usuario->id]) }}">
                <i class="bi bi-gear-fill me-2"></i> Preferencias
            </a>
        </li>
        @if($usuario->rol === 1)
        <li>
            <a class="dropdown-item" href="{{ route('admin.muebles.index') }}">
                <i class="bi bi-gear-fill me-2"></i> Ir a administración
            </a>
        </li>
        @endif
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                </button>
            </form>
        </li>
    </ul>
@endif

@if (!$usuario)
    <a href="{{ route('login.mostrar') }}" class="btn btn-primary">Iniciar Sesión</a>
@endif
            </div>
            @auth
                @if(auth()->user()->rol_id === 1)
                    <a href="{{ route('admin.muebles.index') }}" class="btn btn-secondary d-flex align-items-center ms-2">
                        <i class="bi bi-gear-fill me-1"></i> Panel Admin
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <div class="page-header-text">
        Productos Essentials.
    </div>

    <h2>Filtro:</h2>
    <hr>

    <form action="{{ route('mueble.filtrar') }}" method="GET" class="filter-form mb-4 p-3 border rounded">
        <div class="row">
            <div class="col-md-3 mb-3">
                <label for="nombre">Nombre:</label>
                <input type="text" name="filtro[nombre]" id="nombre" class="form-control" value="{{ $filtro['nombre'] ?? '' }}">
            </div>
            <div class="col-md-2 mb-3">
                <label for="precio_min">Precio mín:</label>
                <input type="number" step="0.01" name="filtro[precio_min]" id="precio_min" class="form-control" value="{{ $filtro['precio_min'] ?? '' }}">
            </div>
            <div class="col-md-2 mb-3">
                <label for="precio_max">Precio máx:</label>
                <input type="number" step="1" name="filtro[precio_max]" id="precio_max" class="form-control" value="{{ $filtro['precio_max'] ?? '' }}">
            </div>
            <div class="col-md-3 mb-3">
                <label for="color">Color:</label>
                <select name="filtro[color]" id="color" class="form-select">
                    <option value="">-- Selecciona --</option>
                    @foreach(['black','white','red','blue','green','grey','brown','oak','walnut','cherry'] as $c)
                        <option value="{{ $c }}" {{ ($filtro['color'] ?? '') === $c ? 'selected' : '' }}>{{ ucfirst($c) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 mb-3">
                <label for="categoria_id">Categoría:</label>
                <select name="filtro[categoria_id]" id="categoria_id" class="form-select">
                    <option value="">-- Selecciona --</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ ($filtro['categoria_id'] ?? '') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="d-block">Novedades:</label>
                <input type="checkbox" name="filtro[novedad]" value="1" {{ !empty($filtro['novedad']) ? 'checked' : '' }}>
                <span>Mostrar solo novedades</span>
            </div>
            <div class="col-md-3 mb-3">
                <label for="orden">Ordenar por:</label>
                <select name="orden" id="orden" class="form-select">
                    <option value="">-- Sin orden --</option>
                    <option value="precio_asc"  {{ ($orden ?? '')==='precio_asc'?'selected':'' }}>Precio ↑</option>
                    <option value="precio_desc" {{ ($orden ?? '')==='precio_desc'?'selected':'' }}>Precio ↓</option>
                    <option value="nombre_asc"  {{ ($orden ?? '')==='nombre_asc'?'selected':'' }}>Nombre ↑</option>
                    <option value="nombre_desc" {{ ($orden ?? '')==='nombre_desc'?'selected':'' }}>Nombre ↓</option>
                    <option value="fecha_desc" {{ ($orden ?? '')==='fecha_desc'?'selected':'' }}>Fecha ↓</option>
                    <option value="fecha_asc" {{ ($orden ?? '')==='fecha_asc'?'selected':'' }}>Fecha ↑</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end mb-3">
                <button type="submit" class="btn btn-primary w-100">Aplicar filtros</button>
            </div>
        </div>
    </form>

    {{-- Filtros activos --}}
    @php
        $hasActiveFilters = !empty(array_filter($filtro ?? [], fn($v) => !empty($v))) || !empty($orden);
    @endphp

    @if($hasActiveFilters)
        <div class="active-filters-container mb-4 d-flex align-items-center flex-wrap gap-2">
            <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Filtros:</span>
            
            @if(!empty($filtro['nombre']))
                <span class="filter-badge">
                    <i class="bi bi-search me-2 opacity-50"></i>
                    "{{ $filtro['nombre'] }}"
                </span>
            @endif

            @if(!empty($filtro['precio_min']) || !empty($filtro['precio_max']))
                <span class="filter-badge">
                    <i class="bi bi-tag me-2 opacity-50"></i>
                    @if(!empty($filtro['precio_min']) && !empty($filtro['precio_max']))
                        {{ $filtro['precio_min'] }} - {{ $filtro['precio_max'] }} {{ $moneda }}
                    @elseif(!empty($filtro['precio_min']))
                        Min: {{ $filtro['precio_min'] }} {{ $moneda }}
                    @else
                        Max: {{ $filtro['precio_max'] }} {{ $moneda }}
                    @endif
                </span>
            @endif

            @if(!empty($filtro['color']))
                <span class="filter-badge">
                    <span class="color-dot me-2" style="width: 10px; height: 10px; border-radius: 50%; background-color: {{ $filtro['color'] }}; border: 1px solid #ddd;"></span>
                    {{ ucfirst($filtro['color']) }}
                </span>
            @endif

            @if(!empty($filtro['categoria_id']))
                <span class="filter-badge">
                    <i class="bi bi-grid me-2 opacity-50"></i>
                    {{ $categorias->firstWhere('id', $filtro['categoria_id'])->nombre ?? 'Categoría' }}
                </span>
            @endif

            @if(!empty($filtro['novedad']))
                <span class="filter-badge badge-novedad">
                    <i class="bi bi-stars me-2"></i>
                    Novedades
                </span>
            @endif

            @if(!empty($orden))
                <span class="filter-badge badge-orden">
                    <i class="bi bi-sort-down me-2"></i>
                    @switch($orden)
                        @case('precio_asc') Precio ↑ @break
                        @case('precio_desc') Precio ↓ @break
                        @case('nombre_asc') Nombre A-Z @break
                        @case('nombre_desc') Nombre Z-A @break
                        @case('fecha_asc') Más antiguos @break
                        @case('fecha_desc') Más recientes @break
                        @default {{ $orden }}
                    @endswitch
                </span>
            @endif

            <a href="{{ route('muebles.index') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3 d-flex align-items-center transition-all hover-scale" title="Borrar todos los filtros">
                <i class="bi bi-x-lg me-1"></i> Borrar todo
            </a>
        </div>
    @endif

    <hr>

    @if($muebles->isEmpty())
        <div class="alert alert-warning">No se encontraron muebles.</div>
    @else
        <div class="product-grid row">
            @foreach($muebles as $mueble)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="producto-card card h-100">
                        <a href="{{ route('muebles.show', ['mueble' => $mueble->id]) }}" class="text-decoration-none text-dark">
                            <div class="producto-image p-3">
                                 @php
                                    // La API devuelve ->imagenes como array de URLs
                                    $imgUrl = !empty($mueble->imagenes)
                                        ? $mueble->imagenes[0]
                                        : asset('images/muebles/placeholder.jpg');
                                @endphp

                                <div style="border: 1px solid #ddd; padding: 10px; text-align: center;">
                                    <img src="{{ $imgUrl }}" alt="{{ $mueble->nombre_producto }}" style="width: 150px; height: 150px; object-fit: cover;">
                                </div>
                            </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $mueble->nombre_producto }}</h5>

                                    @if (($mueble->stock_disponible ?? 0) == 0)
                                        <div class="text-danger small fw-bold mb-1">
                                            <i class="bi bi-x-circle-fill me-1"></i> Sin stock
                                        </div>
                                    @elseif ($mueble->stock_disponible < 5)
                                        <div class="text-warning small fw-bold mb-1">
                                            <i class="bi bi-exclamation-circle-fill me-1"></i> ¡Últimas {{ $mueble->stock_disponible }} unidades!
                                        </div>
                                    @endif

                                    <p class="producto-price card-text fw-bold text-success">
                                        {{ number_format($mueble->precio_venta, 2) }} {{ $moneda }}
                                    </p>
                                </div>
                        </a>
                        <div class="card-footer bg-white border-0">
                            <form method="POST" action="{{ route('carrito.store') }}" class="add-cart-form">
                                @csrf
                                <input type="hidden" name="producto_id" value="{{ $mueble->id }}">
                                <div class="mb-2">
                                    <label for="cantidad_{{ $mueble->id }}" class="form-label d-block text-center small">Cantidad</label>
                                    <input type="number" id="cantidad_{{ $mueble->id }}" name="cantidad" value="1" min="1" max="{{ $mueble->stock_disponible ?? 99 }}" class="form-control form-control-sm text-center">
                                </div>
                                <button type="submit" class="btn add-cart-btn w-100 {{ ($mueble->stock_disponible ?? 1)==0 ? 'btn-outline-danger' : 'btn-primary' }}" {{ ($mueble->stock_disponible ?? 1)==0 ? 'disabled' : '' }}>
                                    {{ ($mueble->stock_disponible ?? 1)==0 ? 'Sin stock' : 'Añadir al carrito' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if(method_exists($muebles, 'links'))
            <div class="mt-4 d-flex justify-content-center">
                {{ $muebles->withQueryString()->links('pagination::bootstrap-4') }}
            </div>

            @if($muebles->total() > 0)
                <div class="text-center text-muted mt-2">
                    Mostrando {{ $muebles->firstItem() }} a {{ $muebles->lastItem() }} de {{ $muebles->total() }} resultados
                </div>
            @endif
        @endif

    @endif
</div>

@endsection
