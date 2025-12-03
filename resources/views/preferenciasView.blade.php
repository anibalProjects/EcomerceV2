@php
    // asegurarse de que $tema tiene valor ('claro' por defecto)
    $tema = $tema ?? 'claro';
    $layout = ($tema === 'oscuro') ? 'layout.oscuro' : 'layout.app';
@endphp

@extends($layout)

@section('title', 'Preferencias')

@section('content')
<div class="content-container">
    <div class="nav-wrapper mb-4">
        <div class="nav-centered">
            <span class="lux-brand">LECTONIC</span>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white border-0 p-3">
                    <h4 class="mb-0">Preferencias de Sesión</h4>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success small mb-3">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger small mb-3">
                            <ul class="mb-0">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('preferencias.update', ['userId' => $usuario_id, 'sesionId' => $sesionId]) }}" method="POST" class="row g-3">
                        @csrf

                        <div class="col-md-6">
                            <label for="moneda" class="form-label small text-muted">Moneda</label>
                            @php $monedaCookie = Cookie::get('moneda') ?? 'EUR'; @endphp
                            <select id="moneda" name="moneda" class="form-select form-select-sm">
                                <option value="EUR" {{ $monedaCookie == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                                <option value="USD" {{ $monedaCookie == 'USD' ? 'selected' : '' }}>Dólar ($)</option>
                                <option value="GBP" {{ $monedaCookie == 'GBP' ? 'selected' : '' }}>Libra (£)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="paginacion" class="form-label small text-muted">Productos por página</label>
                            @php $paginacion = Cookie::get('paginacion') ?? '12'; @endphp
                            <select id="paginacion" name="paginacion" class="form-select form-select-sm">
                                <option value="6"  {{ $paginacion == '6'  ? 'selected' : '' }}>6</option>
                                <option value="12" {{ $paginacion == '12' ? 'selected' : '' }}>12</option>
                                <option value="24" {{ $paginacion == '24' ? 'selected' : '' }}>24</option>
                            </select>
                        </div>

                        <div class="col-12 mt-2">
                            <label class="form-label small text-muted d-block mb-2">Tema visual</label>
                            @php $temaCookie = Cookie::get('tema_visual') ?? 'claro'; @endphp
                            <div class="d-flex gap-3 align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tema" id="tema-claro" value="claro" {{ $temaCookie == 'claro' ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="tema-claro">Claro</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tema" id="tema-oscuro" value="oscuro" {{ $temaCookie == 'oscuro' ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="tema-oscuro">Oscuro</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-3 d-flex justify-content-end gap-2">
                            <a href="{{ route('muebles.index', ['sesionId' => $sesionId]) }}" class="btn cart-btn">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Actualizar preferencias</button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="small text-muted">
                        Estas preferencias se guardan en cookies y en las preferencias de usuario si estás identificado.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('head')
<style>
/* Ajustes locales para que encaje con el diseño (responsive y limpio) */
.card { border-radius: 10px; }
.form-label.small { color: var(--light-text, #666); }
.cart-btn { border: 1px solid var(--gold); color: var(--gold); background: transparent; }
.cart-btn:hover { background: rgba(201,168,75,0.06); }
@media (max-width: 576px) {
    .card-body { padding: 1rem; }
}
</style>
@endpush
@endsection
