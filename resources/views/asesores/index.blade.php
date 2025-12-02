@extends('layouts.app')

@section('content')
<div class="container">

    {{-- ================================ --}}
    {{--      TITULO + RESUMEN GLOBAL     --}}
    {{-- ================================ --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            <i class="bi bi-people-fill text-primary"></i> Asesores
        </h2>

        <a href="{{ route('asesores.create') }}" class="btn btn-vw shadow-sm">
            <i class="bi bi-person-plus-fill"></i> Nuevo asesor
        </a>
    </div>

    {{-- ================================ --}}
    {{--    TARJETA DE METRICAS GLOBALES  --}}
    {{-- ================================ --}}
    @php
        $totalGlobalOrdenes = 0;
        $totalGlobalErrores = 0;

        foreach ($asesores as $ax) {
            $totalGlobalOrdenes += $ax->metricas['total_ordenes'];
            $totalGlobalErrores += $ax->metricas['errores'];
        }
    @endphp

    <div class="card shadow-sm mb-4 p-4 border-0" style="border-radius:14px;">
        <div class="row text-center">

            <div class="col-md-4">
                <h5 class="fw-bold text-primary m-0">Total de órdenes</h5>
                <p class="fs-3 fw-bold m-0">{{ $totalGlobalOrdenes }}</p>
            </div>

            <div class="col-md-4">
                <h5 class="fw-bold text-primary m-0">Errores detectados</h5>
                <p class="fs-3 fw-bold text-danger m-0">{{ $totalGlobalErrores }}</p>
            </div>

            <div class="col-md-4">
                <h5 class="fw-bold text-primary m-0">Promedio general</h5>
                @php
                    $promedioGeneral = ($totalGlobalOrdenes > 0)
                        ? round(100 - (($totalGlobalErrores / ($totalGlobalOrdenes * 21)) * 100), 1)
                        : 100;
                @endphp
                <p class="fs-3 fw-bold text-success m-0">{{ $promedioGeneral }}%</p>
            </div>

        </div>
    </div>

    {{-- ================================ --}}
    {{--      LISTA DE ASESORES (CARDS)   --}}
    {{-- ================================ --}}
    <div class="row g-4">

        @foreach($asesores as $a)
        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 14px;">

                {{-- Encabezado --}}
                <div class="card-header bg-light border-0 py-3" style="border-radius: 14px 14px 0 0;">
                    <h5 class="m-0 fw-bold text-dark">
                        <i class="bi bi-person-circle me-1 text-secondary"></i>
                        {{ $a->nombre }} {{ $a->apellido }}
                    </h5>
                </div>

                <div class="card-body">

                    {{-- Rendimiento --}}
                    <div class="mb-3">
                        <label class="text-muted fw-bold small">Rendimiento</label>

                        {{-- Estrellas --}}
                        <div class="mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $a->metricas['estrellas'])
                                    <i class="bi bi-star-fill text-warning fs-5"></i>
                                @else
                                    <i class="bi bi-star text-secondary fs-5"></i>
                                @endif
                            @endfor
                        </div>

                        {{-- Porcentaje --}}
                        <div class="text-muted small">
                            <strong>{{ $a->metricas['efectividad'] }}% efectivo</strong> ·
                            <span class="text-danger fw-bold">{{ $a->metricas['errores'] }} errores</span>
                        </div>
                    </div>

                    <hr>

                    {{-- Total de órdenes --}}
                    <p class="m-0">
                        <strong>Total de órdenes: </strong>
                        <span class="fw-bold text-dark">{{ $a->metricas['total_ordenes'] }}</span>
                    </p>

                    {{-- Correo --}}
                    <p class="m-0"><strong>Correo: </strong>
                        <span class="text-muted">{{ $a->correo ?? '—' }}</span>
                    </p>

                    {{-- Teléfono --}}
                    <p class="m-0"><strong>Teléfono: </strong>
                        <span class="text-muted">{{ $a->telefono ?? '—' }}</span>
                    </p>

                    {{-- Registro --}}
                    <p class="m-0"><strong>Registro: </strong>
                        <span class="text-muted">{{ $a->fecha_registro }}</span>
                    </p>

                </div>

                {{-- Footer con acciones --}}
                <div class="card-footer bg-white border-0 d-flex justify-content-between">

                    <a href="{{ route('asesores.edit', $a) }}"
                       class="btn btn-outline-primary btn-sm px-3">
                        <i class="bi bi-pencil-square"></i> Editar
                    </a>

                    <a href="{{ route('asesores.desempeno', $a) }}"
                        class="btn btn-outline-secondary btn-sm px-3">
                        <i class="bi bi-graph-up-arrow"></i> Desempeño
                    </a>

                    <form action="{{ route('asesores.destroy', $a) }}"
                          method="post"
                          onsubmit="return confirm('¿Eliminar este asesor?');"
                          class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm px-3">
                            <i class="bi bi-trash"></i> Eliminar
                        </button>
                    </form>
                </div>

            </div>
        </div>
        @endforeach

    </div>

    {{-- Paginación --}}
    <div class="mt-4">
        {{ $asesores->links() }}
    </div>

</div>
@endsection
