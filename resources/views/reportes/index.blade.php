@extends('layouts.app')

@section('content')
<h3 class="mb-3">📄 Reportes de Órdenes</h3>

{{-- FORMULARIO DE FILTROS --}}
<div class="card p-4 mb-4 shadow-sm">
  <form method="post" action="{{ route('reportes.filtrar') }}" class="row g-3 align-items-end">
    @csrf
    <div class="col-md-4">
      <label class="form-label">Asesor</label>
      <select name="asesor_id" class="form-select">
        <option value="">Todos</option>
        @foreach($asesores as $asesor)
          <option value="{{ $asesor->id }}" {{ (old('asesor_id', $filtros['asesor_id'] ?? '') == $asesor->id) ? 'selected' : '' }}>
            {{ $asesor->nombre }} {{ $asesor->apellido }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="col-md-3">
      <label class="form-label">Desde</label>
      <input type="date" name="fecha_inicio" value="{{ $filtros['fecha_inicio'] ?? '' }}" class="form-control">
    </div>

    <div class="col-md-3">
      <label class="form-label">Hasta</label>
      <input type="date" name="fecha_fin" value="{{ $filtros['fecha_fin'] ?? '' }}" class="form-control">
    </div>

    <div class="col-md-2 d-grid">
      <button class="btn btn-vw"><i class="bi bi-search"></i> Filtrar</button>
    </div>
  </form>
</div>

{{-- RESULTADOS DE BÚSQUEDA --}}
@if(!empty($ordenes) && count($ordenes) > 0)
<div class="card p-4 shadow-sm">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h5 class="m-0">Órdenes encontradas: {{ count($ordenes) }}</h5>
    <p class="text-muted m-0">Selecciona una orden para descargar su PDF completo.</p>
  </div>

  <table class="table align-middle table-bordered">
    <thead class="table-light text-center">
      <tr>
        <th># Orden</th>
        <th>Chasis</th>
        <th>Fecha</th>
        <th>Asesor</th>
        <th>Progreso (Rev. 1)</th>
        <th>Descargar</th>
      </tr>
    </thead>
    <tbody>
      @foreach($ordenes as $orden)
        @php
          $total = $orden->revisiones->count();
          $completadas = $orden->revisiones->whereNotNull('revision_1')->count();
          $percent = $total > 0 ? round(($completadas / $total) * 100) : 0;
        @endphp
        <tr>
          <td>{{ $orden->numero_orden }}</td>
          <td>{{ $orden->numero_chasis }}</td>
          <td>{{ $orden->fecha }}</td>
          <td>{{ $orden->asesor?->nombre }} {{ $orden->asesor?->apellido }}</td>
          <td class="text-center" style="width: 200px;">
            <div class="progress" style="height: 8px;">
              <div class="progress-bar bg-success" style="width: {{ $percent }}%;"></div>
            </div>
            <small class="text-muted">{{ $percent }}%</small>
          </td>
          <td class="text-center">
            <a href="{{ route('reportes.pdf', $orden->id) }}" class="btn btn-outline-danger btn-sm">
              <i class="bi bi-file-earmark-pdf"></i> PDF
            </a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@elseif(!empty($filtros))
<div class="alert alert-warning">No se encontraron órdenes con los filtros aplicados.</div>
@endif
@endsection
