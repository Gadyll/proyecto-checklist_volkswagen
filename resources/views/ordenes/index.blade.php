@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Órdenes de reparación</h2>

    <a href="{{ route('ordenes.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-circle"></i> Nueva orden
    </a>
</div>

<div class="card shadow-sm p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th style="width: 120px"># Orden</th>
                    <th style="width: 180px">Chasis</th>
                    <th style="width: 140px">Fecha</th>
                    <th style="width: 200px">Asesor</th>
                    <th style="width: 200px">Progreso (Rev. 1)</th>
                    <th style="width: 250px" class="text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($ordenes as $orden)
                    <tr>
                        <td class="fw-bold">{{ $orden->numero_orden }}</td>
                        <td>{{ $orden->numero_chasis }}</td>
                        <td>{{ $orden->fecha }}</td>
                        <td>{{ $orden->asesor->nombre }} {{ $orden->asesor->apellido }}</td>

                        <td>
                            @php
                                $total = $orden->revisiones->count();
                                $completados = $orden->revisiones->where('revision_1','!=',null)->count();
                                $porcentaje = $total > 0 ? round(($completados * 100)/$total) : 0;
                            @endphp

                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" style="width: {{ $porcentaje }}%;"></div>
                            </div>
                            <small class="text-muted">{{ $porcentaje }}%</small>
                        </td>

                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">

                                <a href="{{ route('ordenes.show', $orden) }}"
                                   class="btn btn-sm btn-outline-primary px-3">Abrir</a>

                                <a href="{{ route('ordenes.edit', $orden) }}"
                                   class="btn btn-sm btn-outline-secondary px-3">Editar</a>

                                {{-- BOTÓN ABRE MODAL --}}
                                <button type="button"
                                    class="btn btn-sm btn-outline-danger px-3"
                                    onclick="openDeleteModal({{ $orden->id }}, '{{ $orden->numero_orden }}')">
                                    Eliminar
                                </button>

                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>

@endsection

{{-- ===================================================== --}}
{{--                       MODAL                   --}}
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
                <p class="fs-5">¿Deseas eliminar la orden <b id="ordenSeleccionada"></b>?</p>
                <p class="text-muted">Esta acción no puede deshacerse.</p>
            </div>

            <div class="modal-footer">
                <form method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-danger">
                        Eliminar orden
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- ===================================================== --}}
{{--             SCRIPT CONTROLADOR DEL MODAL              --}}
{{-- ===================================================== --}}
<script>
    function openDeleteModal(id, numero) {
        document.getElementById('ordenSeleccionada').innerText = numero;
        document.getElementById('deleteForm').action = "/ordenes/" + id;

        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
</script>
