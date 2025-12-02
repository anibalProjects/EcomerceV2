@extends('layout.app')

@section('title', 'Filtrar y Ordenar Muebles')

@section('content')

<div class="content-container">

    <div class="d-flex justify-content-between align-items-center pt-4 pb-4 nav-centered-brand"
         style="border-bottom: 1px solid var(--nav-border); position: relative;">

        <div class="d-flex align-items-center">
            <a href="#" class="menu-toggle d-md-none me-3">
                <i class="bi bi-list"></i>
            </a>

            <nav class="nav-links-desktop d-none d-md-flex align-items-center">
                <a href="{{ route('muebles.index') }}" class="me-3">
                    <img src="{{ asset('img/Logo png.png') }}" alt="LECTONIC" style="height:70px; object-fit:contain;">
                </a>
            </nav>
        </div>

        <div class="lux-brand centered d-none d-md-block">LECTONIC</div>

        <div class="d-flex align-items-center">

            <div class="d-flex align-items-center">

    <div class="dropdown">

        <button class="btn btn-primary d-flex align-items-center dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                style="letter-spacing: normal; padding-right: 1.5rem;"
        >
            <i class="bi bi-person-circle me-1"></i> Mi Cuenta
        </button>

        <ul class="dropdown-menu dropdown-menu-end">

            <li>
                <a class="dropdown-item d-flex justify-content-between align-items-center"
                   href="{{ route('carrito.index', ['sesionId' => $sesionId ?? null]) }}"
                >
                    <span class="fw-bold">Ver carrito</span>
                    <i class="bi bi-cart-fill ms-3"></i>
                </a>
            </li>

            <li><hr class="dropdown-divider"></li>

            <li>
                <a class="dropdown-item" href="#">
                    <i class="bi bi-gear-fill me-2"></i> Preferencias
                </a>
            </li>

            <li>
                <a class="dropdown-item text-danger" href="#">
                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                </a>
            </li>
        </ul>
    </div>

</div>
            @auth
                @if(auth()->user()->rol_id === 1)
                    <a href="{{ route('admin.muebles.index', ['sesionId' => $sesionId ?? null]) }}" class="btn btn-secondary d-flex align-items-center ms-2">
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

    <form action="{{ route('mueble.filtrar', ['sesionId' => $sesionId]) }}" method="GET" class="filter-form mb-4 p-3 border rounded">
        <div class="row">
            <input type="hidden" name="sesionId" value="{{ $sesionId }}">
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
                    @foreach(['black','white','red','blue','green','grey','brown'] as $c)
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

    <hr>

    @if($muebles->isEmpty())
        <div class="alert alert-warning">No se encontraron muebles.</div>
    @else
        <div class="product-grid row">
            @foreach($muebles as $mueble)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="producto-card card h-100">
                        <a href="{{ route('muebles.show', ['mueble' => $mueble->id, 'sesionId' => $sesionId]) }}" class="text-decoration-none text-dark">
                            <div class="producto-image p-3">
                                @php
                                    $imagenRuta = $mueble->imagen_ruta ?? 'images/muebles/placeholder.jpg';
                                @endphp
                                <img src="{{ asset($imagenRuta) }}" alt="{{ $mueble->nombre }}" class="card-img-top">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $mueble->nombre }}</h5>
                                <p class="producto-price card-text fw-bold text-success">
                                    {{ number_format($mueble->precio, 2) }} {{ Cookie::get('moneda') ?? 'USD' }}
                                </p>
                            </div>
                        </a>
                        <div class="card-footer bg-white border-0">
                            <form method="POST" action="{{ route('carrito.store') }}" class="add-cart-form">
                                @csrf
                                <input type="hidden" name="producto_id" value="{{ $mueble->id }}">
                                <input type="hidden" name="sesionId" value="{{ $sesionId }}">
                                <div class="mb-2">
                                    <label for="cantidad_{{ $mueble->id }}" class="form-label d-block text-center small">Cantidad</label>
                                    <input type="number" id="cantidad_{{ $mueble->id }}" name="cantidad" value="1" min="1" max="{{ $mueble->stock }}" class="form-control form-control-sm text-center">
                                </div>
                                <button type="submit" class="btn add-cart-btn w-100 {{ $mueble->stock==0?'btn-outline-danger':'btn-primary' }}" {{ $mueble->stock==0?'disabled':'' }}>
                                    {{ $mueble->stock==0?'Sin stock':'Añadir al carrito' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $muebles->withQueryString()->links('pagination::bootstrap-4') }}
        </div>

        @if($muebles->total() > 0)
            <div class="text-center text-muted mt-2">
                Mostrando {{ $muebles->firstItem() }} a {{ $muebles->lastItem() }} de {{ $muebles->total() }} resultados
            </div>
        @endif
    @endif
</div>

@endsection
