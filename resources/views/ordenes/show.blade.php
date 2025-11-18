@extends('layouts.app')

@section('content')
<h3 class="mb-4">Orden #{{ $orden->numero_orden }}</h3>

{{-- Información general --}}
<div class="card p-3 mb-4 shadow-sm">
  <div class="row">
    <div class="col-md-4">
      <p><strong>Asesor:</strong> {{ $orden->asesor?->nombre }} {{ $orden->asesor?->apellido }}</p>
    </div>
    <div class="col-md-4">
      <p><strong>Chasis:</strong> {{ $orden->numero_chasis }}</p>
    </div>
    <div class="col-md-4">
      <p><strong>Fecha:</strong> {{ $orden->fecha }}</p>
    </div>
  </div>
  @if($orden->observaciones)
  <p><strong>Observaciones:</strong> {{ $orden->observaciones }}</p>
  @endif
</div>

{{-- Checklist --}}
<div class="card p-3 shadow-sm">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="m-0"><i class="bi bi-list-check"></i> Revisión de checklist</h5>

    <div class="d-flex gap-2">
      <a href="{{ route('reportes.pdf', $orden->id) }}" class="btn btn-outline-danger btn-sm">
        <i class="bi bi-file-earmark-pdf"></i> Descargar PDF
      </a>
      <a href="{{ route('ordenes.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Regresar
      </a>
    </div>
  </div>

  <form method="post" action="{{ route('ordenes.revisiones.update', $orden) }}">
    @csrf
    @method('PUT')

    <table class="table table-bordered align-middle">
      <thead class="table-light text-center">
        <tr>
          <th>Rubro</th>
          <th>
            Revisión 1
            <button type="button" class="btn btn-outline-danger btn-sm ms-2 limpiar-columna" data-columna="revision_1">
              🧹 Limpiar
            </button>
          </th>
          <th>
            Revisión 2
            <button type="button" class="btn btn-outline-danger btn-sm ms-2 limpiar-columna" data-columna="revision_2">
              🧹 Limpiar
            </button>
          </th>
          <th>
            Revisión 3
            <button type="button" class="btn btn-outline-danger btn-sm ms-2 limpiar-columna" data-columna="revision_3">
              🧹 Limpiar
            </button>
          </th>
          <th>Comentario</th>
        </tr>
      </thead>
      <tbody>
        @foreach($orden->revisiones as $r)
        <tr>
          <td>{{ $r->rubro }}</td>

          {{-- Revisión 1 --}}
          <td class="text-center">
            <div class="btn-group btn-group-sm" role="group">
              @foreach(['si'=>'SI', 'no'=>'NO', 'na'=>'NA'] as $val => $label)
              <input type="radio" class="btn-check" name="revision[{{ $r->id }}][revision_1]" id="r1_{{ $r->id }}_{{ $val }}" value="{{ $val }}"
                {{ $r->revision_1 === $val ? 'checked' : '' }}>
              <label class="btn btn-outline-primary" for="r1_{{ $r->id }}_{{ $val }}">{{ $label }}</label>
              @endforeach
            </div>
          </td>

          {{-- Revisión 2 --}}
          <td class="text-center">
            <div class="btn-group btn-group-sm" role="group">
              @foreach(['si'=>'SI', 'no'=>'NO', 'na'=>'NA'] as $val => $label)
              <input type="radio" class="btn-check" name="revision[{{ $r->id }}][revision_2]" id="r2_{{ $r->id }}_{{ $val }}" value="{{ $val }}"
                {{ $r->revision_2 === $val ? 'checked' : '' }}>
              <label class="btn btn-outline-success" for="r2_{{ $r->id }}_{{ $val }}">{{ $label }}</label>
              @endforeach
            </div>
          </td>

          {{-- Revisión 3 --}}
          <td class="text-center">
            <div class="btn-group btn-group-sm" role="group">
              @foreach(['si'=>'SI', 'no'=>'NO', 'na'=>'NA'] as $val => $label)
              <input type="radio" class="btn-check" name="revision[{{ $r->id }}][revision_3]" id="r3_{{ $r->id }}_{{ $val }}" value="{{ $val }}"
                {{ $r->revision_3 === $val ? 'checked' : '' }}>
              <label class="btn btn-outline-info" for="r3_{{ $r->id }}_{{ $val }}">{{ $label }}</label>
              @endforeach
            </div>
          </td>

          {{-- Comentario --}}
          <td>
            <input type="text" name="revision[{{ $r->id }}][comentario]" value="{{ old('revision.'.$r->id.'.comentario', $r->comentario) }}"
              class="form-control form-control-sm" placeholder="Comentario opcional...">
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <div class="d-flex justify-content-end mt-3">
      <button class="btn btn-vw">
        <i class="bi bi-save"></i> Guardar cambios
      </button>
    </div>
  </form>
</div>

{{-- Script para limpiar columnas --}}
<script>
document.querySelectorAll('.limpiar-columna').forEach(btn => {
  btn.addEventListener('click', function () {
    const col = this.dataset.columna;
    document.querySelectorAll(`input[name*="[${col}]"]`).forEach(input => {
      input.checked = false;
    });
  });
});
</script>
@endsection
