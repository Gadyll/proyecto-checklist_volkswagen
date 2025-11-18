@extends('layouts.app')

@section('content')
<h3 class="mb-3">Nueva orden</h3>
<div class="card p-4">
  <form method="post" action="{{ route('ordenes.store') }}">
    @csrf
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Número de orden</label>
        <input name="numero_orden" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Número de chasis</label>
        <input name="numero_chasis" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">Fecha</label>
        <input type="date" name="fecha" class="form-control">
      </div>
      <div class="col-12">
        <label class="form-label">Asesor</label>
        <select name="asesor_id" class="form-select" required>
          <option value="">Selecciona asesor…</option>
          @foreach($asesores as $a)
            <option value="{{ $a->id }}">{{ $a->nombre }} {{ $a->apellido }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-12">
        <label class="form-label">Observaciones</label>
        <textarea name="observaciones" class="form-control" rows="3"></textarea>
      </div>
    </div>
    <button class="btn btn-vw mt-3">Guardar</button>
  </form>
</div>
@endsection
