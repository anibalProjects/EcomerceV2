@php
    $layout = 'layout.app';
@endphp

@extends($layout)

@section('title', 'Mi perfil')

@section('content')
<div class="content-container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h3 class="mb-0">Mi perfil</h3>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small">Nombre</label>
                        <div class="form-control">{{ $usuario->nombre ?? '-' }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small">Apellido</label>
                        <div class="form-control">{{ $usuario->apellido ?? '-' }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small">Correo</label>
                        <div class="form-control">{{ $usuario->email ?? '-' }}</div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('muebles.index', ['sesionId' => $sesionId]) }}" class="btn btn-secondary">Volver</a>
                        @if(!empty($usuario->id))
                            <a href="{{ route('preferencias.index', ['userId' => $usuario->id, 'sesionId' => $sesionId]) }}" class="btn btn-primary">
                                Preferencias
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
