<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\Asesor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    // ===========================================================
    // VISTA PRINCIPAL DE REPORTES
    // ===========================================================
    public function index()
    {
        return view('reportes.index', [
            'asesores' => Asesor::orderBy('nombre')->get(),
            'ordenes'  => [],
            'filtros'  => [],
        ]);
    }

    // ===========================================================
    // FILTRAR ÓRDENES (CON PRIORIDAD CORRECTA)
    // ===========================================================
    public function filtrar(Request $request)
    {
        $query = Orden::query();

        // ========================================================
        // IDENTIFICAR FILTRO PRINCIPAL QUE SE DEBE APLICAR
        // ========================================================
        $buscarOrden  = !empty($request->numero_orden);
        $buscarChasis = !empty($request->numero_chasis);
        $buscarAsesor = !empty($request->asesor_id) || !empty($request->fecha_inicio) || !empty($request->fecha_fin);

        // ========================================================
        // PRIORIDAD #1 → BÚSQUEDA POR NÚMERO DE ORDEN (EXCLUSIVA)
        // ========================================================
        if ($buscarOrden) 
        {
            $query->where('numero_orden', 'LIKE', "%{$request->numero_orden}%");
        }
        // ========================================================
        // PRIORIDAD #2 → BÚSQUEDA POR CHASIS (EXCLUSIVA)
        // ========================================================
        elseif ($buscarChasis) 
        {
            $query->where('numero_chasis', 'LIKE', "%{$request->numero_chasis}%");
        }
        // ========================================================
        // PRIORIDAD #3 → ASESOR + FECHAS (IGNORA ORDEN/CHASIS)
        // ========================================================
        elseif ($buscarAsesor) 
        {
            if (!empty($request->asesor_id)) {
                $query->where('asesor_id', $request->asesor_id);
            }

            if (!empty($request->fecha_inicio)) {
                $query->whereDate('fecha', '>=', $request->fecha_inicio);
            }

            if (!empty($request->fecha_fin)) {
                $query->whereDate('fecha', '<=', $request->fecha_fin);
            }
        }

        // ========================================================
        // EJECUTAR CONSULTA
        // ========================================================
        $ordenes = $query->with(['asesor', 'revisiones'])
            ->orderBy('fecha', 'desc')
            ->get();

        // ========================================================
        // CALCULAR PROGRESO EXACTO (MISMA LÓGICA DE ORDENES)
        // ========================================================
        foreach ($ordenes as $orden) {

            $total = $orden->revisiones->count();

            $completadas = $orden->revisiones->filter(function ($rev) {

                $val = $rev->revision_1;

                if (is_string($val)) {
                    $val = trim($val);
                }

                return $val !== null && $val !== '';
            })->count();

            $orden->progreso = $total > 0
                ? round(($completadas / $total) * 100)
                : 0;
        }

        // ========================================================
        // ALERTA SI NO SE ENCONTRARON RESULTADOS
        // ========================================================
        if ($ordenes->isEmpty()) {
            return back()->with('info', 'No se encontraron órdenes que coincidan con los criterios de búsqueda.');
        }

        return view('reportes.index', [
            'asesores' => Asesor::orderBy('nombre')->get(),
            'ordenes'  => $ordenes,
            'filtros'  => $request->all(),
        ]);
    }

    // ===========================================================
    // GENERAR PDF INDIVIDUAL
    // ===========================================================
    public function pdf(Orden $orden)
    {
        $orden->load('asesor', 'revisiones');

        $pdf = Pdf::loadView('reportes.pdf', compact('orden'))
            ->setPaper('letter', 'portrait');

        return $pdf->download("Orden-{$orden->numero_orden}.pdf");
    }
}



