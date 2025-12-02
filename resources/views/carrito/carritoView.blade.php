@extends('layout.app')

@section('title', 'Mi Carrito de Compras')

@section('content')
    <div class="header-actions mb-4 d-flex justify-content-between align-items-center">
        <h1>Mi Carrito de Compras</h1>
        <a href="{{ route('muebles.index', ['sesionId' => $sesionId]) }}" class="btn cart-btn">
            <i class="bi bi-arrow-left me-2"></i> Seguir Comprando
        </a>
    </div>

    <hr>

    {{-- Mensajes de Sesión --}}
    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <i class="bi bi-x-octagon-fill me-2"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    @if ($productosDelCarrito->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-4">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Producto</th>
                        <th scope="col" class="text-end">Precio Unitario</th>
                        <th scope="col" class="text-center">Cantidad</th>
                        <th scope="col" class="text-end">Subtotal</th>
                        <th scope="col" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productosDelCarrito as $item)
                        <tr>
                            <td>
                                <span class="fw-bold">{{ $item->nombre }}</span>
                            </td>
                            <td class="text-end">{{ number_format($item->precio, 2, ',', '.') }}€</td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $item->pivot->cantidad }}</span>
                            </td>
                            <td class="text-end fw-bold text-success">
                                {{ number_format($item->precio * $item->pivot->cantidad, 2, ',', '.') }}€
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    {{-- Formulario para Modificar Cantidad --}}
                                    <form
                                        action="{{ route('carrito.update', ['carrito' => $item->id, 'sesionId' => $sesionId]) }}"
                                        method="POST" class="d-flex gap-1">
                                        @csrf
                                        @method('PUT')

                                        <button type="submit" name="increment" value="+"
                                            class="btn btn-sm btn-outline-success" title="Aumentar cantidad">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                        <button type="submit" name="decrement" value="-"
                                            class="btn btn-sm btn-outline-warning" title="Disminuir cantidad">
                                            <i class="bi bi-dash-lg"></i>
                                        </button>
                                    </form>

                                    {{-- Formulario para Eliminar Item --}}
                                    <form
                                        action="{{ route('carrito.destroy', ['carrito' => $item->id, 'sesionId' => $sesionId]) }}"
                                        method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            title="Eliminar producto">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 p-3 bg-light rounded shadow-sm">
            <h2 class="h4 mb-0">Total del Carrito: <span
                    class="text-primary fw-bold">{{ number_format($total, 2, ',', '.') }}€</span></h2>

            <div class="d-flex gap-2">
                <form action="{{ route('carrito.empty', ['sesionId' => $sesionId]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-trash3-fill me-2"></i>Vaciar Carrito
                    </button>
                </form>
                <form action="{{ route('carrito.buy',  ['sesionId' => $sesionId]) }}" method="POST">
                    @csrf
                    <button class="btn btn-primary">
                        <input type="hidden" name="sesionId" value="{{ $sesionId }}">
                        <i class="bi bi-box-seam-fill me-2"></i> Procesar Compra
                    </button>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-info text-center py-4" role="alert">
            <h4 class="alert-heading"><i class="bi bi-bag-x-fill me-2"></i> Tu Carrito Está Vacío</h4>
            <p>Parece que aún no has añadido ningún mueble. ¡Explora nuestro catálogo y encuentra algo que te guste!</p>
            <hr>
            <a href="{{ route('muebles.index', ['sesionId' => $sesionId]) }}" class="btn btn-primary">
                Ir a la Tienda
            </a>
        </div>
    @endif

@endsection
