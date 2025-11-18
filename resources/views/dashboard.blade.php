@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h3 class="m-0">🏠 Panel Volkswagen</h3>
</div>

<div class="row g-4 mb-4">
  <div class="col-md-6 col-lg-3">
    <div class="card text-center p-3">
      <div class="text-muted">Total Asesores</div>
      <div class="fs-2 fw-bold text-primary">{{ $totalAsesores }}</div>
    </div>
  </div>

  <div class="col-md-6 col-lg-3">
    <div class="card text-center p-3">
      <div class="text-muted">Total Órdenes</div>
      <div class="fs-2 fw-bold text-success">{{ $totalOrdenes }}</div>
    </div>
  </div>

  <div class="col-md-6 col-lg-6">
    <div class="card p-3">
      <h6 class="fw-bold mb-3">📊 Órdenes por Asesor</h6>
      <canvas id="chartAsesores" height="120"></canvas>
    </div>
  </div>
</div>

<div class="card p-3">
  <h6 class="fw-bold mb-3">📋 Últimas Órdenes Registradas</h6>
  <table class="table table-hover align-middle">
    <thead class="table-light">
      <tr>
        <th># Orden</th>
        <th>Asesor</th>
        <th>Fecha</th>
        <th>Observaciones</th>
      </tr>
    </thead>
    <tbody>
      @forelse($ultimasOrdenes as $o)
        <tr>
          <td>{{ $o->numero_orden }}</td>
          <td>{{ $o->asesor?->nombre }} {{ $o->asesor?->apellido }}</td>
          <td>{{ $o->fecha }}</td>
          <td>{{ $o->observaciones ?: '—' }}</td>
        </tr>
      @empty
        <tr><td colspan="4" class="text-center text-muted">No hay registros recientes.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const ctx = document.getElementById('chartAsesores').getContext('2d');
  const data = {
    labels: @json($ordenesPorAsesor->pluck('nombre')),
    datasets: [{
      label: 'Órdenes por Asesor',
      data: @json($ordenesPorAsesor->pluck('ordenes_count')),
      backgroundColor: '#0055a5'
    }]
  };
  new Chart(ctx, {
    type: 'bar',
    data: data,
    options: {
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true } }
    }
  });
});
</script>
@endsection
