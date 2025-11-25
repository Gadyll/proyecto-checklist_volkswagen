@extends('layouts.app')

@section('content')

<div class="container py-4">

    <h1 class="mb-4 fw-bold">
        <i class="bi bi-speedometer2"></i> Dashboard
    </h1>

    <div class="row g-4">

        {{-- ============================
                TARJETA ÓRDENES
        ============================ --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-4">
                    <i class="bi bi-card-checklist fs-1 text-primary"></i>
                    <h4 class="mt-3">Órdenes</h4>
                    <p class="text-muted">Gestiona todas las órdenes de reparación.</p>

                    <a href="{{ route('ordenes.index') }}" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-list-ul"></i> Ver órdenes
                    </a>

                    <a href="{{ route('ordenes.create') }}" class="btn btn-success w-100">
                        <i class="bi bi-plus-circle"></i> Nueva orden
                    </a>
                </div>
            </div>
        </div>

        {{-- ============================
                 TARJETA ASESORES
        ============================ --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-4">
                    <i class="bi bi-people fs-1 text-info"></i>
                    <h4 class="mt-3">Asesores</h4>
                    <p class="text-muted">Administra a los asesores registrados.</p>

                    <a href="{{ route('asesores.index') }}" class="btn btn-info text-white w-100">
                        <i class="bi bi-person-lines-fill"></i> Ver asesores
                    </a>
                </div>
            </div>
        </div>

        {{-- ============================
                 TARJETA REPORTES
        ============================ --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-4">
                    <i class="bi bi-file-earmark-bar-graph fs-1 text-warning"></i>
                    <h4 class="mt-3">Reportes</h4>
                    <p class="text-muted">Genera reportes y estadísticas.</p>

                    <a href="{{ route('reportes.index') }}" class="btn btn-warning w-100">
                        <i class="bi bi-file-earmark-arrow-down"></i> Ver reportes
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
