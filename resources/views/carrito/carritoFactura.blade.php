@php
    $tema = $tema ?? 'claro';
    $layout = ($tema === 'oscuro') ? 'layout.oscuro' : 'layout.app';
@endphp

@extends($layout)

@push('head')
@if ($tema === 'oscuro')
<style>
    /* Fondo general */
    body, .content-container {
        background-color: #121212 !important;
        color: #e4e4e4 !important;
    }

    /* Eliminación de blancos forzados por Bootstrap */
    .bg-white, .bg-light, .table-light, .card-header.bg-white, .card-footer.bg-white {
        background-color: #1b1b1b !important;
        color: #e4e4e4 !important;
        border-color: #333 !important;
    }

    /* Card */
    .card {
        background-color: #1e1e1e !important;
        border: 1px solid #333 !important;
        color: #e4e4e4 !important;
    }

    .card-header, .card-footer {
        background-color: #1b1b1b !important;
        border-color: #333 !important;
        color: #ddd !important;
    }

    /* Tablas */
    table {
        color: #e4e4e4 !important;
    }

    thead.table-light {
        background-color: #2a2a2a !important;
        color: #ddd !important;
        border-bottom: 1px solid #444 !important;
    }

    tbody tr {
        background-color: #1e1e1e !important;
        border-color: #333 !important;
    }

    tbody tr:hover {
        background-color: #252525 !important;
    }

    /* Textos */
    .text-muted {
        color: #aaaaaa !important;
    }

    small.text-muted {
        color: #bbbbbb !important;
    }

    /* Tonos de texto forzados */
    .text-success {
        color: #6fcf97 !important;
    }

    strong {
        color: #e4e4e4 !important;
    }

    /* Total box */
    .bg-light {
        background-color: #2a2a2a !important;
        color: #fff !important;
        border-color: #444 !important;
    }

    /* Alertas */
    .alert-success {
        background-color: #143d19 !important;
        border-color: #1f5e28 !important;
        color: #c7ffcf !important;
    }

    /* Botones */
    .btn-primary {
        background-color: #ff8c00 !important;
        border-color: #ff8c00 !important;
        color: #fff !important;
    }

    .btn-primary:hover {
        background-color: #e67a00 !important;
        border-color: #e67a00 !important;
    }

    .btn-outline-secondary {
        border-color: #aaa !important;
        color: #e4e4e4 !important;
        background-color: transparent !important;
    }

    .btn-outline-secondary:hover {
        background-color: #555 !important;
        border-color: #aaa !important;
    }

    hr, .border-top, .border-bottom {
        border-color: #333 !important;
    }

    input, select, textarea {
        background-color: #1e1e1e !important;
        color: #fff !important;
        border: 1px solid #444 !important;
    }

    input:focus, select:focus, textarea:focus {
        border-color: #888 !important;
    }

    /* Iconos */
    .bi {
        color: #e4e4e4 !important;
    }

</style>
@endif
@endpush

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

                <!-- HEADER -->
                <div class="card-header p-3 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0"><i class="bi bi-receipt me-2"></i>Factura</h3>
                        <div class="text-end small text-muted">
                            <div><strong>Factura #</strong> {{ str_pad($usuario->id ?? 0, 6, '0', STR_PAD_LEFT) }}</div>
                            <div>{{ now()->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>

                <!-- BODY -->
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

                    <!-- TABLA -->
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
                                        $imgUrl = $imagen
                                            ? route('imagen.mueble', ['path' => rawurlencode($imagen->ruta)])
                                            : asset('images/muebles/placeholder.jpg');
                                    @endphp

                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $imgUrl }}" alt="{{ $item->nombre }}" style="width:70px;height:60px;object-fit:cover;border-radius:6px;border:1px solid rgba(255,255,255,0.08);">
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

                    <!-- TOTAL -->
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

                    <!-- ALERTA -->
                    <div class="alert alert-success mt-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <strong>Compra realizada con éxito.</strong> Se ha enviado un email de confirmación.
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <small class="text-muted">Gracias por comprar en <strong>LECTONIC</strong></small>

                    <div class="d-flex gap-2">
                        <form action="{{ route('carrito.returnFromBuy', ['sesionId' => $sesionId]) }}" method="GET">
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
    @media print {
        .nav-wrapper, .btn, .alert { display: none !important; }
        body { background: #fff; }
        .card { box-shadow: none !important; border: 1px solid #ddd !important; }
    }

    @media (max-width: 576px) {
        .table img { width: 60px; height: 50px; }
    }
</style>
@endsection
