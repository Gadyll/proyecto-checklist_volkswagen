<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volkswagen - Panel de Control</title>

    {{-- Bootstrap y estilos base --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }

        /* ================================
           SIDEBAR
        ================================= */
        .sidebar {
            background-color: #001f3f; /* Azul Volkswagen */
            min-height: 100vh;
            width: 260px;
            position: fixed;
            left: 0;
            top: 0;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: white;
        }

        .sidebar h4 {
            text-align: center;
            font-weight: bold;
            color: #fff;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li a {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: #cfd8e3;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.2s;
        }

        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background-color: #005bbb; /* Azul VW brillante */
            color: #fff;
        }

        .sidebar i {
            margin-right: 10px;
        }

        /* ================================
           CONTENIDO
        ================================= */
        .content {
            margin-left: 260px;
            padding: 30px;
        }

        /* Botón VW */
        .btn-vw {
            background-color: #005bbb;
            color: white;
        }
        .btn-vw:hover {
            background-color: #004799;
            color: #fff;
        }

        /* Footer */
        footer {
            text-align: center;
            font-size: 0.85rem;
            padding: 15px;
            color: #cfd8e3;
        }

        /* Alertas con animación */
        .alert {
            opacity: 1;
            transition: opacity .5s ease-out;
        }
        .alert.hide {
            opacity: 0;
        }
    </style>
</head>

<body>

<div class="d-flex">

    {{-- ================================
        SIDEBAR
    ================================= --}}
    <nav class="sidebar">

        <div>
            <h4>Volkswagen</h4>

            <ul>
                <li>
                    <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>

                <li>
                    <a href="{{ route('ordenes.index') }}" class="{{ request()->is('ordenes*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i> Órdenes
                    </a>
                </li>

                <li>
                    <a href="{{ route('asesores.index') }}" class="{{ request()->is('asesores*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Asesores
                    </a>
                </li>

                <li>
                    <a href="{{ route('reportes.index') }}" class="{{ request()->is('reportes*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-bar-graph"></i> Reportes
                    </a>
                </li>
            </ul>
        </div>

        <footer>© 2025 Volkswagen México</footer>

    </nav>

    {{-- ================================
        CONTENIDO PRINCIPAL
    ================================= --}}
    <main class="content flex-fill">

        {{-- ALERTAS PROFESIONALES --}}
        <div class="container">

            @if(session('ok'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill"></i> {{ session('ok') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-x-circle-fill"></i> {{ session('error') }}
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-info-circle-fill"></i> {{ session('info') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('warning') }}
                </div>
            @endif

        </div>

        @yield('content')

    </main>

</div>


{{-- ======================================================
     MODAL PROFESIONAL ELIMINAR ORDEN
========================================================= --}}
<div class="modal fade" id="modalEliminarOrden" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle-fill"></i> Confirmar eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="mb-0">
                    ¿Deseas eliminar esta orden de forma permanente?<br>
                    <strong>Esta acción no se puede deshacer.</strong>
                </p>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                <form id="formEliminarOrden" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        Eliminar definitivamente
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>


{{-- ================================
    JS
================================ --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Cerrar alertas automáticas
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.classList.add('hide');
        });
    }, 3000);


    // Función para abrir modal de confirmación
    function abrirModalEliminar(url) {
        const form = document.getElementById('formEliminarOrden');
        form.action = url;

        const modal = new bootstrap.Modal(document.getElementById('modalEliminarOrden'));
        modal.show();
    }
</script>

</body>
</html>
