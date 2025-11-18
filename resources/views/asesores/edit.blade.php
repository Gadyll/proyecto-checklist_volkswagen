@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="m-0">Editar asesor</h3>
  <a href="{{ route('asesores.index') }}" class="btn btn-outline-secondary btn-sm">⬅ Regresar</a>
</div>

<div class="card p-4">
  <form method="post" action="{{ route('asesores.update', $asesor) }}">
    @csrf
    @method('PUT')
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Nombre</label>
        <input name="nombre" class="form-control" value="{{ old('nombre', $asesor->nombre) }}" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Apellido</label>
        <input name="apellido" class="form-control" value="{{ old('apellido', $asesor->apellido) }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Fecha registro</label>
        <input type="date" name="fecha_registro" class="form-control" value="{{ old('fecha_registro', $asesor->fecha_registro) }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Correo</label>
        <input type="email" name="correo" class="form-control" value="{{ old('correo', $asesor->correo) }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Teléfono</label>
        <input name="telefono" class="form-control" value="{{ old('telefono', $asesor->telefono) }}">
      </div>
    </div>
    <div class="mt-3 d-flex gap-2">
      <button class="btn btn-vw">Guardar cambios</button>
      <a href="{{ route('asesores.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
