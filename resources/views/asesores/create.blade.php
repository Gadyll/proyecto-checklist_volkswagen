@extends('layouts.app')

@section('content')
<h3 class="mb-3">Nuevo asesor</h3>
<div class="card p-4">
  <form method="post" action="{{ route('asesores.store') }}">
    @csrf
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Nombre</label>
        <input name="nombre" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Apellido</label>
        <input name="apellido" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">Fecha registro</label>
        <input type="date" name="fecha_registro" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label">Correo</label>
        <input type="email" name="correo" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label">Teléfono</label>
        <input name="telefono" class="form-control">
      </div>
    </div>
    <button class="btn btn-vw mt-3">Guardar</button>
  </form>
</div>
@endsection
