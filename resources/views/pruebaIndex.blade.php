@extends('layouts.app') {{-- Asumiendo que usas un layout base como layouts.app --}}

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1 class="mb-4">Test de Preferencias de Usuario</h1>

                @auth
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3>Preferencias Actuales</h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Tema:</strong> <span
                                    id="current-tema">{{ Auth::user()->getPreference('tema', 'claro') }}</span></p>
                            <p><strong>Moneda:</strong> <span
                                    id="current-moneda">{{ Auth::user()->getPreference('moneda', 'EUR') }}</span></p>
                            <p><strong>Paginación:</strong> <span
                                    id="current-paginacion">{{ Auth::user()->getPreference('paginacion', 12) }}</span></p>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h3>Variables Compartidas (Middleware)</h3>
                        </div>
                        <div class="card-body">
                            <p><strong>$user_tema:</strong> {{ $user_tema ?? 'No definido' }}</p>
                            <p><strong>$user_moneda:</strong> {{ $user_moneda ?? 'No definido' }}</p>
                            <p><strong>Session('user_paginacion'):</strong> {{ session('user_paginacion', 'No definido') }}</p>
                        </div>
                    </div>

                    <div id="response-message" class="alert d-none" role="alert"></div>

                    <div class="card">
                        <div class="card-header">
                            <h3>Cambiar Preferencias</h3>
                        </div>
                        <div class="card-body">
                            {{-- Formulario para el Tema --}}
                            <form id="form-tema" class="mb-4">
                                @csrf
                                <div class="form-group">
                                    <label for="tema-select">Tema</label>
                                    <select name="tema" id="tema-select" class="form-control">
                                        <option value="claro" @selected(Auth::user()->getPreference('tema', 'claro') == 'claro')>
                                            Claro</option>
                                        <option value="oscuro" @selected(Auth::user()->getPreference('tema', 'claro') == 'oscuro')>Oscuro</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mt-2">Guardar Tema</button>
                            </form>

                            {{-- Formulario para la Moneda --}}
                            <form id="form-moneda" class="mb-4">
                                @csrf
                                <div class="form-group">
                                    <label for="moneda-select">Moneda</label>
                                    <select name="moneda" id="moneda-select" class="form-control">
                                        <option value="EUR" @selected(Auth::user()->getPreference('moneda', 'EUR') == 'EUR')>EUR
                                        </option>
                                        <option value="USD" @selected(Auth::user()->getPreference('moneda', 'EUR') == 'USD')>USD
                                        </option>
                                        <option value="GBP" @selected(Auth::user()->getPreference('moneda', 'EUR') == 'GBP')>GBP
                                        </option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mt-2">Guardar Moneda</button>
                            </form>

                            {{-- Formulario para la Paginación --}}
                            <form id="form-paginacion">
                                @csrf
                                <div class="form-group">
                                    <label for="paginacion-select">Paginación</label>
                                    <select name="paginacion" id="paginacion-select" class="form-control">
                                        <option value="6" @selected(Auth::user()->getPreference('paginacion', 12) == 6)>6</option>
                                        <option value="12" @selected(Auth::user()->getPreference('paginacion', 12) == 12)>12
                                        </option>
                                        <option value="24" @selected(Auth::user()->getPreference('paginacion', 12) == 24)>24
                                        </option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mt-2">Guardar Paginación</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning" role="alert">
                        Por favor, <a href="{{ route('login') }}">inicia sesión</a> para gestionar tus preferencias.
                    </div>
                @endauth
            </div>
        </div>
    </div>

    @auth
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

                            if (response.ok) {
                                document.getElementById(`current-${preferenceKey}`).textContent = formData.get(preferenceKey);
                            }
                        } catch (error) {
                            responseMessage.textContent = 'Ocurrió un error al contactar con el servidor.';
                            responseMessage.className = 'alert alert-danger';
                        }
                    });
                };

                // Asumiendo que las rutas se llaman así. ¡Ajusta los nombres si son diferentes!
                setupForm('form-tema', '/guardar-tema', 'tema');
                setupForm('form-moneda', '/guardar-moneda', 'moneda');
                setupForm('form-paginacion', '/guardar-paginacion', 'paginacion');
            });
        </script>
    @endauth
@endsection