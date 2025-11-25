<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\Asesor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    // ==============================
    // MOSTRAR VISTA DE REPORTES
    // ==============================
    public function index()
    {
        $asesores = Asesor::orderBy('nombre')->get();

        // Variables vacias por defecto
        $ordenes = [];
        $filtros = [];

        return view('reportes.index', compact('asesores', 'ordenes', 'filtros'));
    }

    // ==============================
    // FILTRAR ÓRDENES
    // ==============================
    public function filtrar(Request $request)
    {
        $query = Orden::query();

        // FILTRO POR NuMERO DE ORDEN
        if ($request->numero_orden) {
            $query->where('numero_orden', 'LIKE', "%{$request->numero_orden}%");
        }

        // FILTRO POR NuMERO DE CHASIS
        if ($request->numero_chasis) {
            $query->where('numero_chasis', 'LIKE', "%{$request->numero_chasis}%");
        }

        // FILTRO POR ASESOR
        if ($request->asesor_id) {
            $query->where('asesor_id', $request->asesor_id);
        }

        // FILTROS POR FECHAS
        if ($request->fecha_inicio) {
            $query->whereDate('fecha', '>=', $request->fecha_inicio);
        }

        if ($request->fecha_fin) {
            $query->whereDate('fecha', '<=', $request->fecha_fin);
        }

        // Ejecutar busqueda con relaciones
        $ordenes = $query->with(['asesor', 'revisiones'])
            ->orderBy('fecha', 'desc')
            ->get();

        
        // CALCULAR PROGRESO POR ORDEN
        
        foreach ($ordenes as $orden) {
            $total = $orden->revisiones->count();

            // Una revisión cuenta como completada si revision_1 NO está vacía
            $completadas = $orden->revisiones
                ->filter(function ($rev) {
                    $val = $rev->revision_1;

                    // Normalizamos: quitamos espacios
                    if (is_string($val)) {
                        $val = trim($val);
                    }

                    // Completada si tiene cualquier valor: SI, NO o NA
                    return $val !== null && $val !== '';
                })
                ->count();

            $orden->progreso = $total > 0
                ? round(($completadas / $total) * 100)
                : 0;
        }

        return view('reportes.index', [
            'asesores' => Asesor::orderBy('nombre')->get(),
            'ordenes'  => $ordenes,
            'filtros'  => $request->all(),
        ]);
    }

   
    // GENERAR PDF INDIVIDUAL
    
    public function pdf(Orden $orden)
    {
        $orden->load('asesor', 'revisiones');

        $pdf = Pdf::loadView('reportes.pdf', compact('orden'))
            ->setPaper('letter', 'portrait');

        return $pdf->download("Orden-{$orden->numero_orden}.pdf");
    }
}

