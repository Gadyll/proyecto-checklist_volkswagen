@extends('layouts.app')

@section('title', 'Nueva orden de reparación')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="m-0 fw-bold">Nueva orden de reparación</h2>
    <a href="{{ route('ordenes.index') }}" class="btn btn-outline-secondary btn-sm">
        ⬅ Regresar
    </a>
</div>

<div class="card shadow-sm p-4">

    <form id="form-orden-create" method="POST" action="{{ route('ordenes.store') }}">
        @csrf

        <div class="row g-4">

            {{-- ===========================
                NÚMERO DE ORDEN
            ============================ --}}
            <div class="col-md-3">
                <label class="form-label fw-bold">Número de orden</label>

                <input type="text"
                    id="numero_orden"
                    name="numero_orden"
                    class="form-control"
                    maxlength="6"
                    placeholder="Ej. 140234"
                    required>

                <small id="msg_orden" class="text-muted d-block mt-1">
                    Debe tener exactamente <b>6 dígitos numéricos</b>.
                </small>
            </div>

            {{-- ===========================
                NÚMERO DE CHASIS
            ============================ --}}
            <div class="col-md-4">
                <label class="form-label fw-bold">Número de chasis</label>

                <input type="text"
                    id="numero_chasis"
                    name="numero_chasis"
                    class="form-control"
                    maxlength="17"
                    placeholder="Ej. 3VWCP6BU0SM017230"
                    oninput="this.value = this.value.toUpperCase();"
                    required>

                <small id="msg_chasis" class="text-muted d-block mt-1">
                    17 caracteres, solo letras MAYÚSCULAS y números.
                </small>
            </div>

            {{-- ===========================
                FECHA
            ============================ --}}
            <div class="col-md-3">
                <label class="form-label fw-bold">Fecha</label>
                <input type="date"
                       name="fecha"
                       value="{{ now()->format('Y-m-d') }}"
                       class="form-control"
                       required>
            </div>

            {{-- ===========================
                ASESOR
            ============================ --}}
            <div class="col-md-2">
                <label class="form-label fw-bold">Asesor</label>
                <select name="asesor_id" class="form-select" required>
                    <option value="">Selecciona...</option>
                    @foreach($asesores as $asesor)
                        <option value="{{ $asesor->id }}">
                            {{ strtoupper($asesor->nombre.' '.$asesor->apellido) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- ===========================
                OBSERVACIONES
            ============================ --}}
            <div class="col-12">
                <label class="form-label fw-bold">Observaciones</label>
                <textarea name="observaciones" rows="3" class="form-control"
                          placeholder="Comentarios generales de la orden"></textarea>
            </div>

        </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                💾 Guardar orden
            </button>
        </div>

    </form>
</div>

{{-- ===========================
    VALIDACIÓN EN TIEMPO REAL
=========================== --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    const orden = document.getElementById('numero_orden');
    const chasis = document.getElementById('numero_chasis');
    const msgOrden = document.getElementById('msg_orden');
    const msgChasis = document.getElementById('msg_chasis');

    // ---- VALIDAR ORDEN ----
    orden.addEventListener('input', () => {
        const valido = /^\d{6}$/.test(orden.value);

        if (valido) {
            orden.classList.remove('is-invalid');
            orden.classList.add('is-valid');
            msgOrden.textContent = "Correcto ✔";
            msgOrden.classList.remove('text-danger');
            msgOrden.classList.add('text-success');
        } else {
            orden.classList.remove('is-valid');
            orden.classList.add('is-invalid');
            msgOrden.textContent = "Debe contener exactamente 6 números (Ej. 140234)";
            msgOrden.classList.remove('text-success');
            msgOrden.classList.add('text-danger');
        }
    });

    // ---- VALIDAR CHASIS ----
    chasis.addEventListener('input', () => {
        const valido = /^[A-Z0-9]{17}$/.test(chasis.value);

        if (valido) {
            chasis.classList.remove('is-invalid');
            chasis.classList.add('is-valid');
            msgChasis.textContent = "Correcto ✔";
            msgChasis.classList.remove('text-danger');
            msgChasis.classList.add('text-success');
        } else {
            chasis.classList.remove('is-valid');
            chasis.classList.add('is-invalid');
            msgChasis.textContent = "Debe tener 17 caracteres MAYÚSCULAS (A-Z) y números";
            msgChasis.classList.remove('text-success');
            msgChasis.classList.add('text-danger');
        }
    });

});
</script>

@endsection
