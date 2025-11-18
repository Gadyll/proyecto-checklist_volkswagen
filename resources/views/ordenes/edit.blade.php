@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="m-0">Editar orden #{{ $orden->numero_orden }}</h3>
  <a href="{{ route('ordenes.index') }}" class="btn btn-outline-secondary btn-sm">⬅ Regresar</a>
</div>

<div class="card p-4">
  <form method="post" action="{{ route('ordenes.update', $orden) }}">
    @csrf
    @method('PUT')
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Número de orden</label>
        <input name="numero_orden" class="form-control" value="{{ old('numero_orden', $orden->numero_orden) }}" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Número de chasis</label>
        <input name="numero_chasis" class="form-control" value="{{ old('numero_chasis', $orden->numero_chasis) }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Fecha</label>
        <input type="date" name="fecha" class="form-control" value="{{ old('fecha', $orden->fecha) }}">
      </div>
      <div class="col-12">
        <label class="form-label">Asesor</label>
        <select name="asesor_id" class="form-select" required>
          @foreach($asesores as $a)
            <option value="{{ $a->id }}" {{ old('asesor_id', $orden->asesor_id) == $a->id ? 'selected' : '' }}>
              {{ $a->nombre }} {{ $a->apellido }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-12">
        <label class="form-label">Observaciones</label>
        <textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones', $orden->observaciones) }}</textarea>
      </div>
    </div>
    <div class="mt-3 d-flex gap-2">
      <button class="btn btn-vw">Guardar cambios</button>
      <a href="{{ route('ordenes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
