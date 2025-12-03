@extends('layout.app')

@section('title', 'Factura de Compra')

@section('content')
<div class="content-container">
    <div class="nav-wrapper mb-4">
        <div class="nav-centered">
            <span class="lux-brand">LECTONIC</span>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow-sm mb-4">
                <div class="card-header p-3 bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0"><i class="bi bi-receipt me-2"></i>Factura</h3>
                        <div class="text-end small text-muted">
                            <div><strong>Factura #</strong> {{ str_pad($usuario->id ?? 0, 6, '0', STR_PAD_LEFT) }}</div>
                            <div>{{ now()->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Datos del Cliente</h6>
                            <div class="text-muted">
                                {{ $usuario->nombre ?? 'Cliente' }} {{ $usuario->apellido ?? '' }}<br>
                                {{ $email ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="fw-bold">Resumen</h6>
                            <div class="text-muted">Método: Pago procesado</div>
                        </div>
                    </div>

                    <hr>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Cant.</th>
                                    <th class="text-end">P. Unit.</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach($productosDelCarrito as $item)
                                    @php
                                        $cantidad = $item->pivot->cantidad;
                                        $subtotal = $item->precio * $cantidad;
                                        $total += $subtotal;
                                        $imagen = $item->galeria()->where('es_principal', true)->first() ?? $item->galeria()->first();
                                        $imgUrl = $imagen ? route('imagen.mueble', ['path' => rawurlencode($imagen->ruta)]) : asset('images/muebles/placeholder.jpg');
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $imgUrl }}" alt="{{ $item->nombre }}" style="width:70px;height:60px;object-fit:cover;border-radius:6px;border:1px solid rgba(0,0,0,0.06);">
                                                <div class="ms-3">
                                                    <div class="fw-bold">{{ $item->nombre }}</div>
                                                    <small class="text-muted">ID #{{ str_pad($item->id,5,'0',STR_PAD_LEFT) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $cantidad }}</td>
                                        <td class="text-end">{{ number_format($item->precio, 2, ',', '.') }} {{ $moneda }}</td>
                                        <td class="text-end fw-bold text-success">{{ number_format($subtotal, 2, ',', '.') }} {{ $moneda }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <div class="row justify-content-end">
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span>{{ number_format($total, 2, ',', '.') }} {{ $moneda }}</span>
                            </div>
                            @php $iva = $total * 0.21; @endphp
                            <div class="d-flex justify-content-between mb-3">
                                <span>IVA (21%)</span>
                                <span>{{ number_format($iva, 2, ',', '.') }} {{ $moneda }}</span>
                            </div>
                            <div class="d-flex justify-content-between p-3 bg-light rounded border-top border-bottom">
                                <strong>Total</strong>
                                <strong class="text-success">{{ number_format($total + $iva, 2, ',', '.') }} {{ $moneda }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-success mt-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <strong>Compra realizada con éxito.</strong> Se ha enviado un email de confirmación.
                    </div>
                </div>

                <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                    <small class="text-muted">Gracias por comprar en <strong>LECTONIC</strong></small>
                    <div class="d-flex gap-2">
                        <form action="{{ route('carrito.returnFromBuy', ['sesionId' => $sesionId]) }}" method="GET" class="d-inline">
                            <button type="submit" class="btn btn-primary">
                                <input type="hidden" name="sesionId" value="{{ $sesionId }}">
                                <i class="bi bi-shop me-1"></i> Seguir Comprando
                            </button>
                        </form>
                        <button onclick="window.print()" class="btn btn-outline-secondary">
                            <i class="bi bi-printer me-1"></i> Imprimir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Ajustes específicos para imprimir / factura */
    @media print {
        .nav-wrapper, .btn, .alert { display: none !important; }
        body { background: #fff; }
        .card { box-shadow: none !important; border: 1px solid #ddd !important; }
    }

    /* Thumbnail responsive */
    @media (max-width: 576px) {
        .table img { width: 60px; height: 50px; }
    }
</style>
@endsection
