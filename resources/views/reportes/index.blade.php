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

                {{-- Filtro: Número de orden --}}
                <div class="col-md-3">
                    <label class="form-label fw-bold">Número de orden</label>
                    <input type="text" name="numero_orden" class="form-control"
                           placeholder="Ej: 140234"
                           value="{{ $filtros['numero_orden'] ?? '' }}">
                </div>

                {{-- Filtro: Número de chasis --}}
                <div class="col-md-3">
                    <label class="form-label fw-bold">Número de chasis</label>
                    <input type="text" name="numero_chasis" class="form-control"
                           placeholder="Ej: MEX12345678901234"
                           value="{{ $filtros['numero_chasis'] ?? '' }}">
                </div>

                {{-- Filtro: Asesor --}}
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

                {{-- Filtro: Fecha inicio --}}
                <div class="col-md-3">
                    <label class="form-label fw-bold">Fecha inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control"
                           value="{{ $filtros['fecha_inicio'] ?? '' }}">
                </div>

                {{-- Filtro: Fecha fin --}}
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
         RESULTADOS DE BÚSQUEDA
    ================================= --}}
    @if(count($ordenes) > 0)
    <div class="card shadow-sm p-4">

        <h4 class="mb-3">
            Resultados encontrados: {{ count($ordenes) }}
        </h4>

        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th># Orden</th>
                    <th>Chasis</th>
                    <th>Fecha</th>
                    <th>Asesor</th>
                    <th style="width: 200px;">Progreso</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach($ordenes as $o)
                <tr>
                    <td class="fw-bold">{{ $o->numero_orden }}</td>

                    <td>{{ $o->numero_chasis }}</td>

                    <td>{{ $o->fecha }}</td>

                    <td>{{ $o->asesor->nombre ?? 'N/A' }}</td>

                    {{-- ================================
                         BARRA DE PROGRESO VW
                    ================================= --}}
                    <td>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success"
                                 style="width: {{ $o->progreso }}%">
                            </div>
                        </div>
                        <small class="text-muted">{{ $o->progreso }}% completado</small>
                    </td>

                    <td>
                        <a href="{{ route('ordenes.show', $o) }}"
                           class="btn btn-primary btn-sm">
                            <i class="bi bi-eye"></i> Ver
                        </a>

                        <a href="{{ route('reportes.pdf', $o) }}"
                           class="btn btn-danger btn-sm">
                            <i class="bi bi-file-earmark-pdf"></i> PDF
                        </a>
                    </td>

                </tr>
                @endforeach
            </tbody>

        </table>

    </div>

    @else

        {{-- ================================
             SIN RESULTADOS
        ================================= --}}
        <div class="alert alert-info shadow-sm">
            <i class="bi bi-info-circle-fill"></i>
            No se encontraron resultados con los filtros ingresados.
        </div>

    @endif

</div>
@endsection
