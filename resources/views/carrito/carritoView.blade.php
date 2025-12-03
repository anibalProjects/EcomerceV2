@php
    $tema = $tema ?? \Illuminate\Support\Facades\Cookie::get('tema_visual', 'claro');
    $layout = ($tema === 'oscuro') ? 'layout.oscuro' : 'layout.app';
@endphp

@extends($layout)

@section('title', 'Mi Carrito de Compras')

@push('head')
    <style>
        /* Estilos específicos para la vista de carrito */
        .carrito-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        @media (min-width: 768px) {
            .carrito-grid {
                grid-template-columns: 1fr 320px;
                align-items: start;
            }
        }

        .producto-row {
            display: flex;
            gap: 1rem;
            align-items: center;
            padding: 1rem;
            border: 1px solid var(--accent-border, #e9e9e9);
            border-radius: 8px;
            background: var(--card-bg, #fff);
        }

        .producto-image {
            width: 110px;
            height: 90px;
            flex: 0 0 110px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            overflow: hidden;
            background: #fafafa;
            border: 1px solid rgba(0, 0, 0, 0.04);
        }

        .producto-info {
            flex: 1;
        }

        .producto-nombre {
            font-weight: 700;
            margin-bottom: 6px;
        }

        .producto-meta {
            color: var(--light-text, #666);
            font-size: 0.9rem;
        }

        .cantidad-controls {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .cantidad-badge {
            min-width: 42px;
            text-align: center;
            padding: 6px 8px;
            border-radius: 6px;
            background: #f1f1f1;
            font-weight: 600;
        }

        .total-panel {
            position: relative;
            border: 1px solid var(--accent-border, #e9e9e9);
            padding: 1rem;
            border-radius: 8px;
            background: var(--card-bg, #fff);
            height: fit-content;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.6rem;
        }

        .total-strong {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--gold, #ad8516);
        }

        .actions-row {
            display: flex;
            gap: 8px;
            margin-top: 1rem;
            justify-content: flex-end;
        }

        .small-muted {
            color: var(--light-text, #777);
            font-size: 0.9rem;
        }

        .empty-state {
            padding: 2rem;
            border-radius: 8px;
            background: #fff;
            border: 1px dashed var(--accent-border, #e9e9e9);
        }
    </style>
@endpush

@section('content')
    <div class="content-container">
        <div class="header-actions mb-4 d-flex justify-content-between align-items-center">
            <h1 class="lux-brand mb-0">Mi Carrito</h1>
            <a href="{{ route('muebles.index', ['sesionId' => $sesionId]) }}" class="btn cart-btn">
                <i class="bi bi-arrow-left me-2"></i> Seguir Comprando
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($productosDelCarrito->isNotEmpty())
            <div class="carrito-grid">
                <div>
                    @foreach ($productosDelCarrito as $item)
                        @php
                            $imagen = $item->galeria()->where('es_principal', true)->first() ?? $item->galeria()->first();
                            $imgUrl = $imagen ? route('imagen.mueble', ['path' => rawurlencode($imagen->ruta)]) : asset('images/muebles/placeholder.jpg');
                            $subtotal = $item->precio * $item->pivot->cantidad;
                        @endphp

                        <div class="producto-row mb-3">
                            <div class="producto-image">
                                <img src="{{ $imgUrl }}" alt="{{ $item->nombre }}"
                                    style="width:100%; height:100%; object-fit:cover;">
                            </div>

                            <div class="producto-info">
                                <div class="producto-nombre">{{ $item->nombre }}</div>
                                <div class="producto-meta">
                                    <span>Precio: <strong>{{ number_format($item->precio, 2, ',', '.') }} {{ $moneda }}</strong>
                                    </span>
                                    <span class="mx-2">·</span>
                                    <span>ID: #{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    <div class="small-muted mt-1">{{ Str::limit($item->descripcion, 80) }}</div>
                                </div>
                            </div>

                            <div class="text-end" style="min-width:160px;">
                                <div class="cantidad-controls mb-2 justify-content-end">
                                    <form
                                        action="{{ route('carrito.update', ['carrito' => $item->id, 'sesionId' => $sesionId]) }}"
                                        method="POST" class="d-flex">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" name="decrement" value="-"
                                            class="btn btn-sm btn-outline-warning" title="Disminuir">
                                            <i class="bi bi-dash-lg"></i>
                                        </button>
                                        <div class="cantidad-badge mx-1">{{ $item->pivot->cantidad }}</div>
                                        <button type="submit" name="increment" value="+"
                                            class="btn btn-sm btn-outline-success" title="Aumentar">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="fw-bold mb-2">{{ number_format($subtotal, 2, ',', '.') }} {{ $moneda }}</div>

                                <div class="d-flex justify-content-end gap-2">
                                    <form
                                        action="{{ route('carrito.destroy', ['carrito' => $item->id, 'sesionId' => $sesionId]) }}"
                                        method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('muebles.show', ['mueble' => $item->id, 'sesionId' => $sesionId]) }}"
                                        class="btn btn-sm btn-outline-secondary" title="Ver producto">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <aside class="total-panel">
                    <h5 class="mb-3">Resumen del Pedido</h5>
                    <div class="total-row">
                        <span class="small-muted">Subtotal</span>
                        <span>{{ number_format($total, 2, ',', '.') }} {{ $moneda }}</span>
                    </div>
                    @php $iva = $total * 0.21; @endphp
                    <div class="total-row">
                        <span class="small-muted">IVA (21%)</span>
                        <span>{{ number_format($iva, 2, ',', '.') }} {{ $moneda }}</span>
                    </div>
                    <div class="total-row">
                        <span class="fw-bold">Total</span>
                        <span class="total-strong">{{ number_format($total + $iva, 2, ',', '.') }} {{ $moneda }}</span>
                    </div>

                    <div class="actions-row">
                        <form action="{{ route('carrito.empty', ['sesionId' => $sesionId]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">Vaciar</button>
                        </form>

                        <form action="{{ route('carrito.buy', ['sesionId' => $sesionId]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="sesionId" value="{{ $sesionId }}">
                            <button type="submit" class="btn btn-primary">Procesar Compra</button>
                        </form>
                    </div>

                    <div class="mt-3 small-muted text-center">
                        Envío y métodos de pago se configuran en el siguiente paso.
                    </div>
                </aside>
            </div>
        @else
            <div class="empty-state text-center">
                <h4 class="mb-2">Tu carrito está vacío</h4>
                <p class="small-muted mb-3">Añade muebles a tu carrito y los encontrarás aquí.</p>
                <a href="{{ route('muebles.index', ['sesionId' => $sesionId]) }}" class="btn btn-primary">Ir a la Tienda</a>
            </div>
        @endif
    </div>
@endsection
