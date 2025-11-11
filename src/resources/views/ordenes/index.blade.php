@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="m-0">Órdenes de reparación</h3>
  <a href="{{ route('ordenes.create') }}" class="btn btn-vw">➕ Nueva orden</a>
</div>

@if(session('ok'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('ok') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<div class="card p-3">
  <table class="table align-middle table-bordered">
    <thead class="table-light text-center">
      <tr>
        <th># Orden</th>
        <th>Chasis</th>
        <th>Fecha</th>
        <th>Asesor</th>
        <th>Progreso (Revisión 1)</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($ordenes as $orden)
        @php
          // Total de rubros (una sola revisión por rubro)
          $total = $orden->revisiones->count();
          // Cuántas revisiones 1 están completadas (si/no/na no null)
          $completadas = $orden->revisiones->whereNotNull('revision_1')->count();
          $percent = $total > 0 ? round(($completadas / $total) * 100) : 0;
        @endphp
        <tr>
          <td>{{ $orden->numero_orden }}</td>
          <td>{{ $orden->numero_chasis }}</td>
          <td>{{ $orden->fecha }}</td>
          <td>{{ $orden->asesor?->nombre }} {{ $orden->asesor?->apellido }}</td>
          <td>
            <div class="progress" style="height: 8px;">
              <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%;"></div>
            </div>
            <small class="text-muted">{{ $percent }}%</small>
          </td>
          <td class="text-center">
            <a href="{{ route('ordenes.show', $orden) }}" class="btn btn-sm btn-outline-primary">Abrir</a>
            <a href="{{ route('ordenes.edit', $orden) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
            <form method="post" action="{{ route('ordenes.destroy', $orden) }}" class="d-inline">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger"
                      onclick="return confirm('¿Seguro que deseas eliminar esta orden?')">
                Eliminar
              </button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
