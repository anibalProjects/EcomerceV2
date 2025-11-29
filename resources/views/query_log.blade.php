<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Query Log & Preferences</title>
    <!-- Bootstrap CSS for styling (matching pruebaIndex) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/' . ($user_tema ?? 'claro') . '.css') }}">
    <style>
        body {
            padding: 20px;
        }

        .query-list {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .query-item {
            background: white;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 3px;
            border: 1px solid #ddd;
        }
    </style>
</head>

<body class="{{ $user_tema ?? 'claro' }}">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Dashboard</h1>
            <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
        </div>

        <div class="row">
            <!-- Query Log Section -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Executed Queries</h3>
                    </div>
                    <div class="card-body">
                        @if(empty($queries))
                            <p class="text-muted">No queries captured (or session expired).</p>
                        @else
                            <div class="query-list">
                                @foreach($queries as $query)
                                    <div class="query-item">
                                        <strong>Query:</strong> {{ $query['query'] }} <br>
                                        <strong>Bindings:</strong> {{ implode(', ', $query['bindings']) }} <br>
                                        <strong>Time:</strong> {{ $query['time'] }}ms
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Preferences Section -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>User Preferences</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Tema:</strong> <span
                                id="current-tema">{{ Auth::user()->getPreference('tema', 'claro') }}</span></p>
                        <p><strong>Moneda:</strong> <span
                                id="current-moneda">{{ Auth::user()->getPreference('moneda', 'EUR') }}</span></p>
                        <p><strong>Paginación:</strong> <span
                                id="current-paginacion">{{ Auth::user()->getPreference('paginacion', 12) }}</span></p>

                        <hr>

                        <div id="response-message" class="alert d-none" role="alert"></div>

                        {{-- Formulario para el Tema --}}
                        <form id="form-tema" class="mb-3">
                            @csrf
                            <div class="form-group mb-2">
                                <label for="tema-select">Tema</label>
                                <select name="tema" id="tema-select" class="form-control">
                                    <option value="claro" @selected(Auth::user()->getPreference('tema', 'claro') == 'claro')>Claro</option>
                                    <option value="oscuro" @selected(Auth::user()->getPreference('tema', 'claro') == 'oscuro')>Oscuro</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Guardar Tema</button>
                        </form>

                        {{-- Formulario para la Moneda --}}
                        <form id="form-moneda" class="mb-3">
                            @csrf
                            <div class="form-group mb-2">
                                <label for="moneda-select">Moneda</label>
                                <select name="moneda" id="moneda-select" class="form-control">
                                    <option value="EUR" @selected(Auth::user()->getPreference('moneda', 'EUR') == 'EUR')>
                                        EUR</option>
                                    <option value="USD" @selected(Auth::user()->getPreference('moneda', 'EUR') == 'USD')>
                                        USD</option>
                                    <option value="GBP" @selected(Auth::user()->getPreference('moneda', 'EUR') == 'GBP')>
                                        GBP</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Guardar Moneda</button>
                        </form>

                        {{-- Formulario para la Paginación --}}
                        <form id="form-paginacion">
                            @csrf
                            <div class="form-group mb-2">
                                <label for="paginacion-select">Paginación</label>
                                <select name="paginacion" id="paginacion-select" class="form-control">
                                    <option value="6" @selected(Auth::user()->getPreference('paginacion', 12) == 6)>6
                                    </option>
                                    <option value="12" @selected(Auth::user()->getPreference('paginacion', 12) == 12)>12
                                    </option>
                                    <option value="24" @selected(Auth::user()->getPreference('paginacion', 12) == 24)>24
                                    </option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Guardar Paginación</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const setupForm = (formId, url, preferenceKey) => {
                const form = document.getElementById(formId);
                if (!form) return;

                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    const formData = new FormData(form);
                    const responseMessage = document.getElementById('response-message');

                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': formData.get('_token'),
                                'Accept': 'application/json',
                            },
                        });

                        const result = await response.json();
                        responseMessage.textContent = result.mensaje || 'Error desconocido.';
                        responseMessage.className = `alert ${response.ok ? 'alert-success' : 'alert-danger'}`;
                        responseMessage.classList.remove('d-none');

                        if (response.ok) {
                            document.getElementById(`current-${preferenceKey}`).textContent = formData.get(preferenceKey);
                        }
                    } catch (error) {
                        responseMessage.textContent = 'Ocurrió un error al contactar con el servidor.';
                        responseMessage.className = 'alert alert-danger';
                        responseMessage.classList.remove('d-none');
                    }
                });
            };

            setupForm('form-tema', '{{ route("preferencias.tema.guardar") }}', 'tema');
            setupForm('form-moneda', '{{ route("preferencias.moneda.guardar") }}', 'moneda');
            setupForm('form-paginacion', '{{ route("preferencias.paginacion.guardar") }}', 'paginacion');
        });
    </script>
</body>

</html>