<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volkswagen - Panel de Órdenes</title>

    {{-- Bootstrap y estilos base --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }
        .sidebar {
            background-color: #001f3f; /* azul Volkswagen */
            min-height: 100vh;
            color: white;
            width: 260px;
            position: fixed;
            left: 0;
            top: 0;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .sidebar h4 {
            text-align: center;
            color: #ffffff;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 6px 0;
        }
        .sidebar ul li a {
            display: flex;
            align-items: center;
            color: #cfd8e3;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease-in-out;
        }
        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background-color: #005bbb;
            color: white;
        }
        .sidebar i {
            margin-right: 10px;
            font-size: 1.1rem;
        }
        .content {
            margin-left: 260px;
            padding: 30px;
        }
        .btn-vw {
            background-color: #005bbb;
            color: white;
            border: none;
        }
        .btn-vw:hover {
            background-color: #004799;
            color: white;
        }
        footer {
            text-align: center;
            font-size: 0.85rem;
            padding: 15px;
            color: #cfd8e3;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        {{-- Sidebar --}}
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
                            <i class="bi bi-file-earmark-arrow-down"></i> Reportes
                        </a>
                    </li>
                    <li>
                        <a href="#" class="{{ request()->is('configuracion*') ? 'active' : '' }}">
                            <i class="bi bi-gear"></i> Configuración
                        </a>
                    </li>
                </ul>
            </div>

            <footer>
                © 2025 Volkswagen México
            </footer>
        </nav>

        {{-- Contenido principal --}}
        <main class="content flex-fill">
            @yield('content')
        </main>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Alertas temporales --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(a => setTimeout(() => a.classList.remove('show'), 4000));
        });
    </script>
</body>
</html>

