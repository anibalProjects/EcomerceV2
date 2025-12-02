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
        <div class="col-lg-8">
            <!-- Encabezado de Factura -->
            <div class="card border-0 shadow-lg mb-4">
                <div class="card-header bg-primary text-white p-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="mb-0"><i class="bi bi-receipt me-2"></i>Factura de Compra</h2>
                        </div>
                        <div class="col text-end">
                            <p class="mb-1"><strong>Número:</strong> #{{ str_pad($usuario->id ?? 0, 6, '0', STR_PAD_LEFT) }}</p>
                            <p class="mb-0"><strong>Fecha:</strong> {{ now()->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Datos del Cliente -->
                <div class="card-body p-4">
                    <h5 class="mb-3"><i class="bi bi-person-badge me-2"></i>Datos del Cliente</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Nombre:</strong></p>
                            <p class="text-muted">{{ $usuario->nombre ?? 'Cliente' }} {{ $usuario->apellido ?? '' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Email:</strong></p>
                            <p class="text-muted">{{ $email ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <hr>

                    <!-- Tabla de Productos -->
                    <h5 class="mb-3"><i class="bi bi-bag-check me-2"></i>Productos Comprados</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Producto</th>
                                    <th scope="col" class="text-center">Cantidad</th>
                                    <th scope="col" class="text-end">Precio Unitario</th>
                                    <th scope="col" class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach ($productosDelCarrito as $item)
                                    @php
                                        $subtotal = $item->precio * $item->pivot->cantidad;
                                        $total += $subtotal;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="ms-2">
                                                    <p class="fw-bold mb-0">{{ $item->nombre }}</p>
                                                    <small class="text-muted">ID: #{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ $item->pivot->cantidad }}</span>
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($item->precio, 2, ',', '.') }}€
                                        </td>
                                        <td class="text-end fw-bold text-success">
                                            {{ number_format($subtotal, 2, ',', '.') }}€
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <!-- Resumen de Totales -->
                    <div class="row justify-content-end mb-4">
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>{{ number_format($total, 2, ',', '.') }}€</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>IVA (21%):</span>
                                @php $iva = $total * 0.21; @endphp
                                <span>{{ number_format($iva, 2, ',', '.') }}€</span>
                            </div>
                            <div class="d-flex justify-content-between p-3 bg-light rounded border-top border-bottom">
                                <strong>Total a Pagar:</strong>
                                @php $totalConIva = $total + $iva; @endphp
                                <strong class="text-success fs-5">{{ number_format($totalConIva, 2, ',', '.') }}€</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Mensaje de Confirmación -->
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div>
                            <strong>¡Compra realizada con éxito!</strong>
                            <p class="mb-0 small mt-1">Tu pedido ha sido procesado correctamente. Recibirás un email de confirmación en tu bandeja de entrada.</p>
                        </div>
                    </div>
                </div>

                <!-- Pie de Factura -->
                <div class="card-footer bg-light p-4 text-center text-muted">
                    <p class="mb-2">Gracias por tu compra en <strong>LECTONIC</strong></p>
                    <p class="mb-0 small">Esta factura ha sido generada automáticamente el {{ now()->format('d \d\e F \d\e Y') }}</p>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="d-flex gap-2 justify-content-center mb-4">
                <form action="{{ route('carrito.returnFromBuy', ['sesionId' => $sesionId]) }}" class="btn btn-primary">
                    <input type="hidden" name="sesionId" value="{{ $sesionId }}">
                    <button class="bi bi-shop me-2">Seguir Comprando</button>
                </form>
                <button onclick="window.print()" class="btn btn-outline-secondary">
                    <i class="bi bi-printer me-2"></i>Imprimir Factura
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .nav-wrapper, .btn, .alert {
            display: none !important;
        }
        body {
            background: white;
        }
        .card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
    }
</style>

@endsection
