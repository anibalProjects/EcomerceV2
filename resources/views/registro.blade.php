@extends('layout.registerAndLogin')

@section('title', 'Crear Nuevo Usuario')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <h1 class="mb-4">Registro de Nuevo Usuario Cliente</h1>
            <p class="lead">Completa el siguiente formulario para crear una nueva cuenta.</p>
            <hr>

            <form action="{{ route('usuarios.store') }}" method="POST" class="p-4 border rounded shadow-sm bg-white">
                @csrf

                <div class="row">
                    {{-- Nombre --}}
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Apellido --}}
                    <div class="col-md-6 mb-3">
                        <label for="apellido" class="form-label">Apellidos:</label>
                        <input type="text" class="form-control @error('apellido') is-invalid @enderror" id="apellido" name="apellido" value="{{ old('apellido') }}" required>
                        @error('apellido')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    {{-- Email --}}
                    <div class="col-md-12 mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    {{-- Contraseña --}}
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Contraseña:</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Confirmar Contraseña --}}
                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Contraseña:</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>

                <div class="d-flex justify-content-start gap-3 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-person-plus-fill me-2"></i> Crear Usuario Cliente
                    </button>
                    <a href="{{ route('welcome') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-circle-fill me-2"></i> Volver
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
