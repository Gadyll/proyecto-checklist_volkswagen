@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">
        <i class="bi bi-people-fill"></i> Asesores
    </h3>
    <a href="{{ route('asesores.create') }}" class="btn btn-vw">
        <i class="bi bi-person-plus-fill"></i> Nuevo asesor
    </a>
</div>

<div class="card p-3 shadow-sm">

    <table class="table table-hover m-0 align-middle">
        <thead class="table-light">
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Registro</th>
                <th>Rendimiento</th>
                <th class="text-end">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($asesores as $a)
                @php
                    $m = $a->metricas;
                @endphp
                <tr>
                    <td>{{ $a->nombre }} {{ $a->apellido }}</td>
                    <td>{{ $a->correo ?? '—' }}</td>
                    <td>{{ $a->telefono ?? '—' }}</td>
                    <td>{{ $a->fecha_registro ?? '—' }}</td>

                    {{-- RENDIMIENTO CON ESTRELLAS --}}
                    <td>
                        <div>
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $m['estrellas'])
                                    <i class="bi bi-star-fill text-warning"></i>
                                @else
                                    <i class="bi bi-star text-muted"></i>
                                @endif
                            @endfor
                        </div>
                        <small class="text-muted">
                            {{ $m['efectividad'] }}% efectivo ·
                            {{ $m['errores'] }} errores
                        </small>
                    </td>

                    <td class="text-end">
                        <div class="btn-group" role="group">
                            <a class="btn btn-sm btn-outline-primary"
                               href="{{ route('asesores.edit', $a) }}">
                                Editar
                            </a>

                            <a class="btn btn-sm btn-outline-secondary"
                               href="{{ route('asesores.desempeno', $a) }}">
                                Desempeño
                            </a>

                            <form action="{{ route('asesores.destroy', $a) }}"
                                  method="post"
                                  onsubmit="return confirm('¿Deseas eliminar este asesor?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $asesores->links() }}
    </div>
</div>
@endsection
