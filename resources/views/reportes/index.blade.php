@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-3"><i class="bi bi-file-earmark-text"></i> Reportes</h3>

    {{-- FILTROS --}}
    <div class="card p-4 shadow-sm mb-4">
        <form method="POST" action="{{ route('reportes.filtrar') }}">
            @csrf

            <div class="row g-3">

                <div class="col-md-3">
                    <label class="form-label">Número de orden</label>
                    <input type="text" name="numero_orden" class="form-control"
                        value="{{ $filtros['numero_orden'] ?? '' }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Número de chasis</label>
                    <input type="text" name="numero_chasis" class="form-control"
                        value="{{ $filtros['numero_chasis'] ?? '' }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Asesor</label>
                    <select name="asesor_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($asesores as $a)
                            <option value="{{ $a->id }}"
                                {{ (isset($filtros['asesor_id']) && $filtros['asesor_id'] == $a->id) ? 'selected' : '' }}>
                                {{ $a->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fecha inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control"
                        value="{{ $filtros['fecha_inicio'] ?? '' }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fecha fin</label>
                    <input type="date" name="fecha_fin" class="form-control"
                        value="{{ $filtros['fecha_fin'] ?? '' }}">
                </div>

            </div>

            <div class="mt-3">
                <button class="btn btn-primary"><i class="bi bi-search"></i> Buscar</button>
            </div>
        </form>
    </div>

    {{-- RESULTADOS --}}
    @if(count($ordenes) > 0)
    <div class="card shadow-sm p-4">

        <h4 class="mb-3">Resultados encontrados: {{ count($ordenes) }}</h4>

        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th># Orden</th>
                    <th>Chasis</th>
                    <th>Fecha</th>
                    <th>Asesor</th>
                    <th>Progreso (Rev. 1)</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach($ordenes as $o)
                <tr>
                    <td>{{ $o->numero_orden }}</td>
                    <td>{{ $o->numero_chasis }}</td>
                    <td>{{ $o->fecha }}</td>
                    <td>{{ $o->asesor->nombre ?? 'N/A' }}</td>

                    {{-- PROGRESO IGUAL QUE ÓRDENES --}}
                    <td style="width: 180px;">
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success"
                                 style="width: {{ $o->progreso }}%;">
                            </div>
                        </div>
                        <small>{{ $o->progreso }}%</small>
                    </td>

                    <td>
                        <a href="{{ route('ordenes.show', $o) }}" class="btn btn-primary btn-sm">
                            Ver
                        </a>

                        <a href="{{ route('reportes.pdf', $o) }}" class="btn btn-danger btn-sm">
                            PDF
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    @endif

</div>
@endsection
