<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden {{ $orden->numero_orden }} - Volkswagen</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #005bbb;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 0;
            color: #005bbb;
        }
        .info {
            margin-bottom: 15px;
        }
        .info td {
            padding: 4px 6px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table th {
            background: #005bbb;
            color: white;
            padding: 6px;
            text-align: center;
            font-size: 12px;
        }
        .table td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: center;
            font-size: 11px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 11px;
            color: #555;
        }
        .blue {
            color: #005bbb;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>Volkswagen Pasteur S.A de C.V</h2>
    <p><strong>Reporte de Orden de Reparación</strong></p>
</div>

<table class="info" width="100%">
    <tr>
        <td><strong>Número de Orden:</strong> {{ $orden->numero_orden }}</td>
        <td><strong>Fecha:</strong> {{ $orden->fecha }}</td>
    </tr>
    <tr>
        <td><strong>Asesor:</strong> {{ $orden->asesor?->nombre }} {{ $orden->asesor?->apellido }}</td>
        <td><strong>Chasis:</strong> {{ $orden->numero_chasis }}</td>
    </tr>
</table>

<table class="table">
    <thead>
        <tr>
            <th>Rubro</th>
            <th>Revisión 1</th>
            <th>Revisión 2</th>
            <th>Revisión 3</th>
            <th>Comentario</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orden->revisiones as $rev)
            <tr>
                <td style="text-align:left">{{ $rev->rubro }}</td>
                <td>{{ strtoupper($rev->revision_1 ?? '-') }}</td>
                <td>{{ strtoupper($rev->revision_2 ?? '-') }}</td>
                <td>{{ strtoupper($rev->revision_3 ?? '-') }}</td>
                <td style="text-align:left">{{ $rev->comentario ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    <p>© 2025 Volkswagen México — Sistema de Control de Órdenes</p>
</div>

</body>
</html>
