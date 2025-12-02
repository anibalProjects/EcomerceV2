@extends('layout.registerAndLogin')

@section('title', 'Inicio de Sesión')

@section('auth-content')

    <div class="mb-5">
        <ul class="nav nav-pills nav-justified" id="authTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="login-tab" data-bs-toggle="pill" href="#login-content" role="tab" aria-selected="true">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('usuarios.create') }}" role="tab" aria-selected="false">
                    <i class="bi bi-person-plus-fill me-1"></i> Registrarse
                </a>
            </li>
        </ul>
    </div>

    @if (session('error'))
        <div class="alert alert-danger p-2 mb-3" role="alert">
            <i class="bi bi-exclamation-octagon-fill me-1"></i> {{ session('error') }}
        </div>
    @endif

    @if (session('mensaje'))
        <div class="alert alert-info p-2 mb-3" role="alert">
            <i class="bi bi-info-circle-fill me-1"></i> {{ session('mensaje') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label visually-hidden">Correo Electrónico</label>
            <div class="input-group input-group-lg">
                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Correo Electrónico" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label visually-hidden">Contraseña</label>
            <div class="input-group input-group-lg">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Contraseña" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-3 btn-lg submit-btn">
            <i class="bi bi-key-fill me-1"></i> Iniciar Sesión
        </button>

        <hr class="my-4">


        <div class="mt-4">
            <h6 class="text-center text-muted mb-3">Preferencias de Sesión</h6>

            <div class="row g-3">

                {{-- Moneda --}}
                <div class="col-sm-6">
                    <label for="moneda" class="form-label small mb-1 text-muted">Moneda</label>
                    <select name="moneda" id="moneda" class="form-select form-select-sm">
                        <option value="EUR" {{ (Cookie::get('moneda') ?? 'EUR') == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                        <option value="USD" {{ (Cookie::get('moneda') ?? 'EUR') == 'USD' ? 'selected' : '' }}>Dólar ($)</option>
                        <option value="GBP" {{ (Cookie::get('moneda') ?? 'EUR') == 'GBP' ? 'selected' : '' }}>Libra (£)</option>
                    </select>
                </div>

                {{-- Paginación --}}
                <div class="col-sm-6">
                    <label for="paginacion" class="form-label small mb-1 text-muted">Prod. por página</label>
                    @php $paginacion = Cookie::get('paginacion') ?? '12'; @endphp
                    <select name="paginacion" id="paginacion" class="form-select form-select-sm">
                        <option value="6" {{ $paginacion == '6' ? 'selected' : '' }}>6</option>
                        <option value="12" {{ $paginacion == '12' ? 'selected' : '' }}>12</option>
                        <option value="24" {{ $paginacion == '24' ? 'selected' : '' }}>24</option>
                    </select>
                </div>

                {{-- Tema Visual --}}
                <div class="col-sm-12 mt-3">
                    <label class="form-label small mb-2 text-muted d-block">Tema Visual</label>
                    @php $tema = Cookie::get('tema_visual') ?? 'claro'; @endphp
                    <div class="d-flex justify-content-center gap-4">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tema" id="tema-claro" value="claro" {{ $tema == 'claro' ? 'checked' : '' }}>
                            <label class="form-check-label small" for="tema-claro">Claro</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tema" id="tema-oscuro" value="oscuro" {{ $tema == 'oscuro' ? 'checked' : '' }}>
                            <label class="form-check-label small" for="tema-oscuro">Oscuro</label>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
@endsection


