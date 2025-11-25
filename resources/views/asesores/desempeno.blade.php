@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- ENCABEZADO --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">
                <i class="bi bi-graph-up-arrow"></i>
                Desempeño del asesor
            </h3>
            <p class="text-muted mb-0">
                {{ $asesor->nombre }} {{ $asesor->apellido }}
            </p>
        </div>

        <a href="{{ route('asesores.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver a asesores
        </a>
    </div>

    {{-- RESUMEN GENERAL --}}
    <div class="row g-4 mb-4">

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Resumen de desempeño</h5>

                    {{-- Estrellas grandes --}}
                    <div class="mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $metricas['estrellas'])
                                <i class="bi bi-star-fill text-warning fs-4"></i>
                            @else
                                <i class="bi bi-star text-muted fs-4"></i>
                            @endif
                        @endfor
                    </div>

                    <p class="mb-2">
                        <strong>{{ $metricas['efectividad'] }}% de efectividad</strong>
                    </p>

                    <div class="progress mb-2" style="height: 12px;">
                        <div class="progress-bar bg-success"
                             style="width: {{ $metricas['efectividad'] }}%;">
                        </div>
                    </div>

                    <small class="text-muted">
                        {{ $metricas['total_ordenes'] }} orden(es) revisadas ·
                        {{ $metricas['total_checks'] }} puntos evaluados ·
                        {{ $metricas['errores'] }} respuestas "NO"
                    </small>
                </div>
            </div>
        </div>

        {{-- Rubros con más errores --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        Rubros con más errores (NO)
                    </h5>

                    @if(count($topRubros) === 0)
                        <p class="text-muted mb-0">
                            No se han encontrado errores para este asesor.
                        </p>
                    @else
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Rubro</th>
                                    <th class="text-end">Veces con "NO"</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topRubros as $rubro => $cantidad)
                                    <tr>
                                        <td>{{ $rubro }}</td>
                                        <td class="text-end">
                                            <span class="badge bg-danger-subtle text-danger">
                                                {{ $cantidad }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                </div>
            </div>
        </div>

    </div>

    {{-- ÓRDENES CON ERRORES --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <h5 class="card-title mb-3">
                Órdenes con errores registrados
            </h5>

            @if(count($ordenesConErrores) === 0)
                <p class="text-muted mb-0">
                    No se encontraron órdenes con respuestas "NO" para este asesor.
                </p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th># Orden</th>
                                <th>Chasis</th>
                                <th>Fecha</th>
                                <th>Errores (NO)</th>
                                <th>Progreso</th>
                                <th>Rubros con error</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ordenesConErrores as $item)
                                @php
                                    $orden   = $item['orden'];
                                    $errores = $item['errores'];
                                @endphp
                                <tr>
                                    <td class="fw-bold">{{ $orden->numero_orden }}</td>
                                    <td>{{ $orden->numero_chasis }}</td>
                                    <td>{{ $orden->fecha }}</td>

                                    <td>
                                        <span class="badge bg-danger">
                                            {{ $errores }} error(es)
                                        </span>
                                    </td>

                                    <td style="width: 200px;">
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-success"
                                                 style="width: {{ $item['efectividad'] }}%;">
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            {{ $item['efectividad'] }}% de aciertos
                                        </small>
                                    </td>

                                    <td>
                                        @if(count($item['rubros_con_error']) === 0)
                                            <span class="text-muted">—</span>
                                        @else
                                            <ul class="mb-0 small">
                                                @foreach($item['rubros_con_error'] as $r)
                                                    <li>{{ $r }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('ordenes.show', $orden) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            Ver orden
                                        </a>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>

</div>
@endsection
