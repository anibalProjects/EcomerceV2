@php
    $tema = Cookie::get('tema_visual', 'claro');
@endphp

@extends($tema === 'oscuro' ? 'layout.oscuro' : 'layout.app')

@section('title', 'Galería de Imágenes - ' . $mueble->nombre)

@section('content')

<div class="card">
    <h2>Galería de imágenes - {{ $mueble->nombre }}</h2>

    <a href="{{ route('admin.muebles.index', ['sesionId' => $sesionId]) }}" class="btn btn-secondary">
        ⬅ Volver
    </a>

    @if(session('success'))
        <div class="alert alert-success" style="color: green; margin: 10px 0;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Subir Imágenes --}}
    <div class="card" style="margin-top: 20px;">
        <div class="card-header">Subir Imágenes</div>
        <div class="card-body">
            <form action="{{ route('admin.muebles.galeria.upload', [$mueble->id, 'sesionId' => $sesionId]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="imagenes[]" multiple accept="image/*" required>
                <button type="submit" class="btn btn-success">Subir</button>
            </form>
        </div>
    </div>

    {{-- Listado de Imágenes --}}
    <div class="card" style="margin-top: 20px;">
        <div class="card-header">Imágenes de la Galería</div>
        <div class="card-body">

            @if($mueble->galeria && $mueble->galeria->count() > 0)
                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                    @foreach($mueble->galeria as $imagen)
                        @php
                            $imgUrl = route('imagen.mueble', ['path' => rawurlencode($imagen->ruta)]);
                        @endphp

                        <div style="border: 1px solid #ddd; padding: 10px; text-align: center;">

                            <img src="{{ $imgUrl }}" alt="Imagen" style="width: 150px; height: 150px; object-fit: cover;">

                            <div style="margin-top: 10px;">

                                @if($imagen->es_principal)
                                    <span style="color: green; font-weight: bold;">Principal</span>
                                @else
                                    <form action="{{ route('admin.muebles.galeria.principal', [$imagen->id, 'sesionId' => $sesionId]) }}"
                                          method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">Hacer Principal</button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.muebles.galeria.delete', [$imagen->id, 'sesionId' => $sesionId]) }}"
                                      method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar?')">🗑</button>
                                </form>

                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p>No hay imágenes en la galería.</p>
            @endif

        </div>
    </div>

</div>

@endsection
