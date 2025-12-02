<div class="mt-4">
    <form action="{{ route('preferencias.update', ['userId' => $usuario_id, 'sesionId' => $sesionId])}}" method="POST">
        @csrf
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
            <button type="submit">Actualizar preferencias</button>
    </form>

        </div>
