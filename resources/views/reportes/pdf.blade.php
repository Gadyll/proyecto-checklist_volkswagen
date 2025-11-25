<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Orden {{ $orden->numero_orden }}</title>

    <style>
        @page {
            margin: 18px 22px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        /* ===== Encabezado ===== */
        .header {
            background: #001E50;
            color: white;
            text-align: center;
            padding: 10px 5px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .header p {
            margin: 2px 0 0;
            font-size: 10px;
        }

        /* ===== Secciones ===== */
        .section {
            padding: 8px 5px;
            page-break-inside: avoid;
        }

        h2 {
            color: #001E50;
            margin: 0 0 4px;
            font-size: 13px;
            border-bottom: 1px solid #ccc;
        }

        .info p {
            margin: 1px 0;
            font-size: 10.5px;
        }

        /* ===== Tabla ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            page-break-inside: avoid;
        }

        th {
            background: #E6ECF5;
            border: 1px solid #CBD3DF;
            padding: 4px;
            text-align: center;
            color: #001E50;
            font-size: 10px;
        }

        td {
            border: 1px solid #CBD3DF;
            padding: 3px 4px;
            font-size: 9.5px;
        }

        /* Evitar saltos */
        tr, td, th {
            page-break-inside: avoid !important;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 6px;
            font-size: 9px;
            color: #777;
        }
    </style>
</head>

<body>

    <!-- ENCABEZADO -->
    <div class="header">
        <h1>Volkswagen Pasteur Querétaro</h1>
        <p>Reporte del Checklist de Orden • Documento generado automáticamente</p>
    </div>

    <!-- INFORMACIÓN DE LA ORDEN -->
    <div class="section">
        <h2>Información de la Orden</h2>
        <div class="info">
            <p><strong>Número de orden:</strong> {{ $orden->numero_orden }}</p>
            <p><strong>Chasis:</strong> {{ $orden->numero_chasis }}</p>
            <p><strong>Fecha:</strong> {{ $orden->fecha }}</p>
            <p><strong>Asesor:</strong> {{ $orden->asesor->nombre }} {{ $orden->asesor->apellido }}</p>
            <p><strong>Observaciones:</strong> {{ $orden->observaciones ?? 'N/A' }}</p>
        </div>
    </div>

    <!-- CHECKLIST -->
    <div class="section">
        <h2>Checklist de Revisión</h2>

        <table>
            <thead>
                <tr>
                    <th style="width: 32%;">Rubro</th>
                    <th style="width: 8%;">R1</th>
                    <th style="width: 8%;">R2</th>
                    <th style="width: 8%;">R3</th>
                    <th>Comentario</th>
                </tr>
            </thead>

            <tbody>
                @foreach($orden->revisiones as $r)
                <tr>
                    <td>{{ $r->rubro }}</td>
                    <td style="text-align:center;">{{ strtoupper($r->revision_1 ?? '-') }}</td>
                    <td style="text-align:center;">{{ strtoupper($r->revision_2 ?? '-') }}</td>
                    <td style="text-align:center;">{{ strtoupper($r->revision_3 ?? '-') }}</td>
                    <td>{{ $r->comentario ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        © {{ date('Y') }} Volkswagen Pasteur Querétaro — Sistema Interno de Control de Órdenes
    </div>

</body>
</html>
