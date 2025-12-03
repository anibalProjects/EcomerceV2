@php
    $tema = $tema ?? \Illuminate\Support\Facades\Cookie::get('tema_visual', 'claro');
    $layout = ($tema === 'oscuro') ? 'layout.oscuro' : 'layout.app';
@endphp

@extends($layout)

@section('title', $mueble->nombre)

@section('content')

<div class="content-container">

    <div class="nav-wrapper mb-4">
        <a href="{{ route('muebles.index', request()->query()) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>
        <div class="nav-centered d-none d-md-block">
            <span class="lux-brand">LECTONIC</span>
        </div>
        <a href="{{ route('carrito.index', ['sesionId' => $sesionId ?? null]) }}" class="btn btn-primary d-flex align-items-center">
            <i class="bi bi-cart-fill me-1"></i> Ver carrito
        </a>
    </div>

    <div class="row g-4">

        <div class="col-lg-6">
            <div class="producto-image p-3">
                @php
                    $imagen = $mueble->galeria()->where('es_principal', true)->first() ?? $mueble->galeria()->first();
                    $imgUrl = $imagen ? route('imagen.mueble', ['path' => rawurlencode($imagen->ruta)]) : asset('images/muebles/placeholder.jpg');
                @endphp

                <div class="detalle-img-wrapper">
                    <img src="{{ $imgUrl }}" alt="{{ $mueble->nombre }}" class="detalle-img">
                </div>
            </div>
        </div>

        <div class="col-lg-6">

            <div class="mb-3">
                <span class="badge bg-info">{{ $mueble->Categoria->nombre ?? 'Sin categoría' }}</span>
                @if($mueble->novedad)
                    <span class="badge bg-danger ms-2"><i class="bi bi-star-fill me-1"></i>Novedad</span>
                @endif
            </div>

            <h1 class="lux-brand mb-3">{{ $mueble->nombre }}</h1>

            <p class="lead text-muted mb-4">{{ $mueble->descripcion }}</p>

            <div class="precio-section mb-4 p-3 rounded" style="background: rgba(201, 168, 75, 0.1); border-left: 4px solid #c9a84b;">
                <span class="text-muted d-block small mb-2">Precio</span>
                <h2 class="text-success fw-bold">{{ number_format($mueble->precio, 2, ',', '.')}} {{ $moneda }}</h2>
            </div>

            <div class="stock-section mb-4">
                <span class="text-muted d-block mb-2">Disponibilidad:</span>
                @if($mueble->stock > 0)
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        <span class="fw-bold text-success">{{ $mueble->stock }} unidades disponibles</span>
                    </div>
                @else
                    <div class="d-flex align-items-center">
                        <i class="bi bi-x-circle-fill text-danger me-2"></i>
                        <span class="fw-bold text-danger">Sin stock</span>
                    </div>
                @endif
            </div>

            <hr class="my-4">

            <div class="caracteristicas-section mb-4">
                <h4 class="lux-brand mb-3"><i class="bi bi-info-circle-fill me-2"></i>Características</h4>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card-caracteristica p-3 rounded">
                            <span class="text-muted small d-block mb-2"><i class="bi bi-palette-fill me-2"></i>Color</span>
                            <span class="fw-bold">{{ ucfirst($mueble->color) }}</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card-caracteristica p-3 rounded">
                            <span class="text-muted small d-block mb-2"><i class="bi bi-box-fill me-2"></i>Materiales</span>
                            <span class="fw-bold">{{ $mueble->materiales }}</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card-caracteristica p-3 rounded">
                            <span class="text-muted small d-block mb-2"><i class="bi bi-rulers me-2"></i>Dimensiones</span>
                            <span class="fw-bold">{{ $mueble->dimensiones }}</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card-caracteristica p-3 rounded">
                            <span class="text-muted small d-block mb-2"><i class="bi bi-code-square me-2"></i>ID Producto</span>
                            <span class="fw-bold">#{{ str_pad($mueble->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

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
     @if($productosRelacionados->count() > 0)
        <hr class="my-5">
        <div class="productos-relacionados">
            <h3 class="lux-brand mb-4"><i class="bi bi-collection me-2"></i>Productos Relacionados</h3>
            <div class="row g-4">
                @foreach($productosRelacionados as $relacionado)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="producto-card card h-100">
                            <a href="{{ route('muebles.show', $relacionado->id) }}" class="text-decoration-none text-dark">
                                <div class="producto-image p-3">
                                    <img src="{{ asset($relacionado->imagen_ruta ?? 'images/muebles/placeholder.jpg') }}" alt="{{ $relacionado->nombre }}" class="card-img-top">
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title">{{ $relacionado->nombre }}</h6>
                                    <p class="producto-price card-text fw-bold text-success">
                                        {{ number_format($relacionado->precio, 2) }} {{ $moneda }}
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<style>
    .card-caracteristica {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(201, 168, 75, 0.2);
        transition: all 0.3s ease;
    }

    .card-caracteristica:hover {
        border-color: rgba(201, 168, 75, 0.6);
        background: rgba(201, 168, 75, 0.05);
    }

    .producto-detalle-imagen {
        position: sticky;
        top: 20px;
    }

    .detalle-img-wrapper {
        width: 100%;
        aspect-ratio: 4/3;
        min-height: 320px;
        max-height: 480px;
        background: #fafafa;
        border: 1px solid #ddd;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin: 0 auto;
    }
    .detalle-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    }
    @media (max-width: 768px) {
        .detalle-img-wrapper {
            min-height: 180px;
            aspect-ratio: 1/1;
        }
    }
</style>

@endsection
