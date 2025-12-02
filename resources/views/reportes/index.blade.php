@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-3">
        <i class="bi bi-file-earmark-bar-graph"></i> Reportes
    </h3>

    {{-- ================================
         FORMULARIO DE FILTROS
    ================================= --}}
    <div class="card p-4 shadow-sm mb-4">

        <form method="POST" action="{{ route('reportes.filtrar') }}">
            @csrf

            <div class="row g-3">

                <div class="col-md-3">
                    <label class="form-label fw-bold">Número de orden</label>
                    <input type="text" name="numero_orden" class="form-control"
                           placeholder="Ej: 140234"
                           value="{{ $filtros['numero_orden'] ?? '' }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Número de chasis</label>
                    <input type="text" name="numero_chasis" class="form-control"
                           placeholder="Ej: MEX12345678901234"
                           value="{{ $filtros['numero_chasis'] ?? '' }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Asesor</label>
                    <select name="asesor_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($asesores as $a)
                            <option value="{{ $a->id }}"
                                {{ isset($filtros['asesor_id']) && $filtros['asesor_id'] == $a->id ? 'selected' : '' }}>
                                {{ $a->nombre }} {{ $a->apellido }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Fecha inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control"
                           value="{{ $filtros['fecha_inicio'] ?? '' }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Fecha fin</label>
                    <input type="date" name="fecha_fin" class="form-control"
                           value="{{ $filtros['fecha_fin'] ?? '' }}">
                </div>

            </div>

            <div class="mt-3">
                <button class="btn btn-vw">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>

        </form>

    </div>

    {{-- ================================
         RESULTADOS
    ================================= --}}
    @if(count($ordenes) > 0)

    <div class="card shadow-sm p-4">
        <h4 class="mb-3">Resultados encontrados: {{ count($ordenes) }}</h4>

        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th># Orden</th>
                    <th>Chasis</th>
                    <th>Fecha</th>
                    <th>Asesor</th>
                    <th style="width: 200px;">Progreso</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach($ordenes as $o)
                <tr>
                    <td class="fw-bold">{{ $o->numero_orden }}</td>
                    <td>{{ $o->numero_chasis }}</td>
                    <td>{{ $o->fecha }}</td>
                    <td>{{ $o->asesor->nombre ?? 'N/A' }}</td>

                    <td>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" style="width: {{ $o->progreso }}%;"></div>
                        </div>
                        <small class="text-muted">{{ $o->progreso }}% completado</small>
                    </td>

                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">

                            <a href="{{ route('ordenes.show', $o) }}"
                               class="btn btn-sm btn-primary px-3">
                                <i class="bi bi-eye"></i> Ver
                            </a>

                            <a href="{{ route('reportes.pdf', $o) }}"
                               class="btn btn-sm btn-danger px-3">
                                <i class="bi bi-file-earmark-pdf"></i> PDF
                            </a>

                            {{-- BOTÓN QUE ABRE MODAL --}}
                            <button type="button"
                                class="btn btn-sm btn-outline-danger px-3"
                                onclick="openDeleteModal({{ $o->id }}, '{{ $o->numero_orden }}')">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>

                        </div>
                    </td>

                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    @else
        <div class="alert alert-info shadow-sm">
            <i class="bi bi-info-circle-fill"></i>
            No se encontraron resultados con los filtros ingresados.
        </div>
    @endif

</div>
@endsection


{{-- ===================================================== --}}
{{--               MODAL ELEGANTE DE ELIMINAR              --}}
{{-- ===================================================== --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-trash"></i> Confirmar eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="fs-5">
                    ¿Deseas eliminar la orden <b id="ordenSeleccionada"></b> de forma permanente?
                </p>
                <p class="text-muted">Esta acción no se puede deshacer.</p>
            </div>

            <div class="modal-footer">

                <form method="POST" id="deleteFormReportes">
                    @csrf
                    @method('DELETE')

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-danger">
                        Eliminar definitivamente
                    </button>
                </form>

            </div>

        </div>
    </div>
</div>


{{-- ===================================================== --}}
{{--  SCRIPT PARA CONTROLAR MODAL DE ELIMINACION           --}}
{{-- ===================================================== --}}
<script>
function openDeleteModal(id, numero) {

    document.getElementById('ordenSeleccionada').innerText = numero;

    // Cambiar acción del formulario (mismo controlador que Órdenes)
    document.getElementById('deleteFormReportes').action = "/ordenes/" + id;

    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>


{{-- ===================================================== --}}
{{--   CONTROL DE DESHABILITACIoN DE FILTROS              --}}
{{-- ===================================================== --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    const inputOrden  = document.querySelector('input[name="numero_orden"]');
    const inputChasis = document.querySelector('input[name="numero_chasis"]');
    const selAsesor   = document.querySelector('select[name="asesor_id"]');
    const fechaIni    = document.querySelector('input[name="fecha_inicio"]');
    const fechaFin    = document.querySelector('input[name="fecha_fin"]');

    function actualizarEstado() {

        const tieneOrden  = inputOrden.value.trim() !== "";
        const tieneChasis = inputChasis.value.trim() !== "";
        const tieneAsesorOFecha =
            selAsesor.value !== "" ||
            fechaIni.value.trim() !== "" ||
            fechaFin.value.trim() !== "";

        if (tieneOrden) {
            inputChasis.disabled = true;
            selAsesor.disabled   = true;
            fechaIni.disabled    = true;
            fechaFin.disabled    = true;
            return;
        }

        if (tieneChasis) {
            inputOrden.disabled  = true;
            selAsesor.disabled   = true;
            fechaIni.disabled    = true;
            fechaFin.disabled    = true;
            return;
        }

        if (tieneAsesorOFecha) {
            inputOrden.disabled  = true;
            inputChasis.disabled = true;
            return;
        }

        inputOrden.disabled  = false;
        inputChasis.disabled = false;
        selAsesor.disabled   = false;
        fechaIni.disabled    = false;
        fechaFin.disabled    = false;
    }

    inputOrden.addEventListener("input", actualizarEstado);
    inputChasis.addEventListener("input", actualizarEstado);
    selAsesor.addEventListener("change", actualizarEstado);
    fechaIni.addEventListener("change", actualizarEstado);
    fechaFin.addEventListener("change", actualizarEstado);

    actualizarEstado();
});
</script>
