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
    // FILTRAR ÓRDENES
    // ===========================================================
    public function filtrar(Request $request)
    {
        $query = Orden::query();

        // ------------------------------
        // FILTRO: NÚMERO DE ORDEN
        // ------------------------------
        if (!empty($request->numero_orden)) {
            $query->where('numero_orden', 'LIKE', "%{$request->numero_orden}%");
        }

        // ------------------------------
        // FILTRO: NÚMERO DE CHASIS
        // ------------------------------
        if (!empty($request->numero_chasis)) {
            $query->where('numero_chasis', 'LIKE', "%{$request->numero_chasis}%");
        }

        // ------------------------------
        // FILTRO: ASESOR
        // ------------------------------
        if (!empty($request->asesor_id)) {
            $query->where('asesor_id', $request->asesor_id);
        }

        // ------------------------------
        // FILTROS: FECHAS
        // ------------------------------
        if (!empty($request->fecha_inicio)) {
            $query->whereDate('fecha', '>=', $request->fecha_inicio);
        }

        if (!empty($request->fecha_fin)) {
            $query->whereDate('fecha', '<=', $request->fecha_fin);
        }

        // ------------------------------
        // EJECUTAR CONSULTA
        // ------------------------------
        $ordenes = $query->with(['asesor', 'revisiones'])
            ->orderBy('fecha', 'desc')
            ->get();

        // ------------------------------
        // CALCULAR PROGRESO POR ORDEN
        // MISMO CRITERIO DE OrdenController
        // ------------------------------
        foreach ($ordenes as $orden) {

            $total = $orden->revisiones->count();

            $completadas = $orden->revisiones->filter(function ($rev) {

                $val = $rev->revision_1;

                // Normalizar espacios o valores raros
                if (is_string($val)) {
                    $val = trim($val);
                }

                // Si tiene algo (SI / NO / NA) => completada
                return $val !== null && $val !== '';
            })->count();

            $orden->progreso = $total > 0
                ? round(($completadas / $total) * 100)
                : 0;
        }

        // ------------------------------
        // SI NO HAY RESULTADOS → ALERTA
        // ------------------------------
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


