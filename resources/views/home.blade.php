@extends('layout.app')

@section('title', 'Filtrar y Ordenar Muebles')



{{-- Se omite @section('head') --}}

@section('content')
    <div class="container">
        <h1>Filtrar y Ordenar Muebles</h1>

        <hr>

        <div class="header-actions mb-4">
            <a href="{{ route('carrito.index', ['sesionId' => $sesionId]) }}" class="btn btn-primary cart-btn me-2">
                Ver Carrito 🛒
            </a>
        </div>

        <hr>


        <form action="{{ route('mueble.filtrar', ['categorias' => $categorias, 'sesionId' => $sesionId]) }}" method="POST" class="filter-form mb-4 p-3 border rounded">
            @csrf
            <div class="row">
                {{-- Nombre --}}
                <div class="col-md-3 mb-3">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="filtro[nombre]" id="nombre" class="form-control" value="{{ $filtro['nombre'] ?? '' }}">
                </div>

                {{-- Precio mínimo --}}
                <div class="col-md-2 mb-3">
                    <label for="precio_min">Precio mín:</label>
                    <input type="number" step="0.01" name="filtro[precio_min]" id="precio_min" class="form-control" value="{{ $filtro['precio_min'] ?? '' }}">
                </div>

                {{-- Precio máximo --}}
                <div class="col-md-2 mb-3">
                    <label for="precio_max">Precio máx:</label>
                    <input type="number" step="1" name="filtro[precio_max]" id="precio_max" class="form-control" value="{{ $filtro['precio_max'] ?? '' }}">
                </div>

                {{-- Color --}}
                <div class="col-md-3 mb-3">
                    <label for="color">Color:</label>
                    <select name="filtro[color]" id="color" class="form-select">
                        <option value="">-- Selecciona --</option>
                        @foreach(['black', 'white', 'red', 'blue', 'green', 'grey', 'brown'] as $c)
                            <option value="{{ $c }}" {{ ($filtro['color'] ?? '') === $c ? 'selected' : '' }}>
                                {{ ucfirst($c) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div> {{-- Fin .row 1 --}}

            <div class="row">
                {{-- Categoría --}}
                <div class="col-md-3 mb-3">
                    <label for="categoria_id">Categoría:</label>
                    <select name="filtro[categoria_id]" id="categoria_id" class="form-select">
                        <option value="">-- Selecciona --</option>
                        {{-- Itera sobre la colección de categorías --}}
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ ($filtro['categoria_id'] ?? '') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="d-block">Novedades:</label>
                    <input
                        type="checkbox"
                        name="filtro[novedad]"
                        value="1"
                        {{ !empty($filtro['novedad']) ? 'checked' : '' }}
                    >
                    <span>Mostrar solo novedades</span>
                </div>

                {{-- Ordenar por --}}
                <div class="col-md-3 mb-3">
                    <label for="orden">Ordenar por:</label>
                    <select name="orden" id="orden" class="form-select">
                        <option value="">-- Sin orden --</option>
                        <option value="precio_asc"  {{ ($orden ?? '') === 'precio_asc' ? 'selected' : '' }}>Precio ↑</option>
                        <option value="precio_desc" {{ ($orden ?? '') === 'precio_desc' ? 'selected' : '' }}>Precio ↓</option>
                        <option value="nombre_asc"  {{ ($orden ?? '') === 'nombre_asc' ? 'selected' : '' }}>Nombre ↑</option>
                        <option value="nombre_desc" {{ ($orden ?? '') === 'nombre_desc' ? 'selected' : '' }}>Nombre ↓</option>
                        <option value="fecha_desc" {{ ($orden ?? '') === 'fecha_desc' ? 'selected' : '' }}>Fecha de Creación ↓</option>
                        <option value="fecha_asc" {{ ($orden ?? '') === 'fecha_asc' ? 'selected' : '' }}>Fecha de Creación ↑</option>
                    </select>
                </div>

                {{-- Botón de Aplicar --}}
                <div class="col-md-3 d-flex align-items-end mb-3">
                    <button type="submit" class="btn btn-primary w-100">Aplicar filtros</button>
                </div>
            </div> {{-- Fin .row 2 --}}
        </form>

        <hr>

        {{-- =================================== --}}
        {{-- 📦 LISTADO DE PRODUCTOS --}}
        {{-- =================================== --}}

        @if($muebles->isEmpty())
            <div class="alert alert-warning" role="alert">
                No se encontraron muebles que coincidan con el filtro o el inventario está vacío.
            </div>
        @else
            <h2>Mostrando {{ $muebles->count() }} resultados</h2>

            <div class="product-grid row">

                @foreach($muebles as $mueble)

                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="producto-card card h-100">

                            {{-- Enlace a la vista detallada del producto con ruta vacía --}}
                            <a href="" class="text-decoration-none text-dark">
                                <div class="producto-image p-3">
                                    @php
                                        // Asumiendo que el modelo Mueble tiene una propiedad de imagen
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

                            {{-- Formulario para Añadir al Carrito con ruta de acción vacía --}}
                            <div class="card-footer bg-white border-0">
                                <form method="POST" action="{{ route('carrito.store') }}" class="add-cart-form">
                                    @csrf
                                    <input type="hidden" name="producto_id" value="{{ $mueble->id }}">
                                    <input type="hidden" name="sesionId" value="{{ $sesionId }}">

                                    <div class="mb-2">
                                        <label for="cantidad_{{ $mueble->id }}" class="form-label d-block text-center small">Cantidad</label>
                                        <input
                                            type="number"
                                            id="cantidad_{{ $mueble->id }}"
                                            name="cantidad"
                                            value="1"
                                            min="1"
                                            max="{{ $mueble->stock }}"
                                            class="form-control form-control-sm text-center"
                                        >
                                    </div>

                                    <button type="submit" class="btn add-cart-btn w-100 {{ $mueble->stock == 0 ? 'btn-outline-danger' : 'btn-primary' }}" {{ $mueble->stock == 0 ? 'disabled' : '' }}>
                                        {{ $mueble->stock == 0 ? 'Sin stock' : 'Añadir al carrito' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div> {{-- Fin .product-grid --}}

        @endif {{-- Fin del @if($muebles->isEmpty()) --}}

    </div> {{-- Fin .container --}}
@endsection
