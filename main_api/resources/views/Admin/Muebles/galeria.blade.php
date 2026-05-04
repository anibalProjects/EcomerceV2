@php
    $tema = Cookie::get('tema_visual', 'claro');
@endphp

@extends($tema === 'oscuro' ? 'layout.oscuro' : 'layout.app')

@section('title', 'Galería de Imágenes - ' . $mueble->nombre)

@push('head')
<style>
.galeria-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}
.galeria-card {
    background: var(--card-bg, #fff);
    border: 1px solid var(--accent-border, #e9e9e9);
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    padding: 1rem;
    text-align: center;
    position: relative;
    transition: box-shadow 0.2s;
}
.galeria-card:hover {
    box-shadow: 0 8px 24px rgba(201,168,75,0.08);
}
.galeria-img {
    width: 100%;
    max-width: 160px;
    height: 120px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #ddd;
    margin-bottom: 0.5rem;
}
.galeria-actions {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
}
.galeria-principal {
    color: #4CAF50;
    font-weight: bold;
    font-size: 0.95rem;
}
@media (max-width: 576px) {
    .galeria-img { max-width: 100px; height: 70px; }
    .galeria-card { padding: 0.5rem; }
}
</style>
@endpush

@section('content')
<div class="content-container">
    <div class="nav-wrapper mb-4">
        <a href="{{ route('admin.muebles.index', ['sesionId' => $sesionId]) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>
        <div class="nav-centered">
            <span class="lux-brand">LECTONIC</span>
        </div>
    </div>

    <div class="card p-4 mb-4">
        <h2 class="mb-3 text-center">Galería de imágenes - {{ $mueble->nombre }}</h2>

        @if(session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.muebles.galeria.upload', [$mueble->id, 'sesionId' => $sesionId]) }}" method="POST" enctype="multipart/form-data" class="mb-4">
            @csrf
            <div class="row g-2 align-items-center">
                <div class="col-md-8">
                    <input type="file" name="imagenes[]" multiple accept="image/*" required class="form-control">
                </div>
                <div class="col-md-4 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-1"></i> Subir Imágenes
                    </button>
                </div>
            </div>
        </form>

        @if($mueble->galeria && $mueble->galeria->count() > 0)
            <div class="galeria-grid">
                @foreach($mueble->galeria as $imagen)
                    @php
                        $imgUrl = route('imagen.mueble', ['path' => rawurlencode($imagen->ruta)]);
                    @endphp
                    <div class="galeria-card">
                        <img src="{{ $imgUrl }}" alt="Imagen" class="galeria-img">
                        @if($imagen->es_principal)
                            <div class="galeria-principal"><i class="bi bi-star-fill me-1"></i> Principal</div>
                        @endif
                        <div class="galeria-actions">
                            @if(!$imagen->es_principal)
                                <form action="{{ route('admin.muebles.galeria.principal', [$imagen->id, 'sesionId' => $sesionId]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning" title="Hacer principal">
                                        <i class="bi bi-star"></i>
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.muebles.galeria.delete', [$imagen->id, 'sesionId' => $sesionId]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Eliminar esta imagen?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info mt-3">No hay imágenes en la galería.</div>
        @endif
    </div>
</div>
@endsection
