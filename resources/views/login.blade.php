@extends('layout.registerAndLogin')

@section('title', 'Inicio de Sesión')

@section('auth-content')
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
        <div class="card p-4 shadow-sm">
            <div class="text-center mb-3">
                <h3 class="lux-brand mb-0">Iniciar Sesión</h3>
                <p class="small text-muted">Accede a tu cuenta para gestionar pedidos y preferencias</p>
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
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Correo electrónico" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label visually-hidden">Contraseña</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Contraseña" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small" for="remember">Recordarme</label>
                    </div>
                    <a href="#" class="small text-muted">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">
                    <i class="bi bi-key-fill me-1"></i> Iniciar Sesión
                </button>

                <div class="text-center small text-muted mb-2">¿No tienes cuenta?</div>
                <a href="{{ route('usuarios.create') }}" class="btn cart-btn w-100">
                    <i class="bi bi-person-plus-fill me-1"></i> Crear cuenta
                </a>
            </form>
        </div>

        <div class="text-center mt-3 small text-muted">
            <span>Inicia sesión para acceder a tus preferencias y carrito.</span>
        </div>
    </div>
</div>
@endsection

@push('head')
<style>
/* Ajustes locales para el formulario de login */
.card { border-radius: 12px; }
.input-group-text { background: transparent; border-right: 0; }
.form-control { border-left: 0; }
.btn-primary { background: var(--gold); border: 1px solid var(--dark-gold-border); }
.cart-btn { border: 1px solid var(--gold); color: var(--gold); background: transparent; display:inline-block; padding: .5rem .75rem; border-radius:8px; text-align:center; }
@media (max-width: 576px) {
    .card { padding: 1rem; }
}
</style>
@endpush


