<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\Asesor;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    /**
     * Vista principal de reportes
     */
    public function index()
    {
        $asesores = Asesor::orderBy('nombre')->get();
        return view('reportes.index', [
            'asesores' => $asesores,
            'ordenes' => [],
            'filtros' => [],
        ]);
    }

    /**
     * Filtrar órdenes
     */
    public function filtrar(Request $request)
    {
        $request->validate([
            'asesor_id' => 'nullable|exists:asesores,id',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date',
        ]);

        $ordenes = Orden::with('asesor', 'revisiones')
            ->when($request->asesor_id, fn($q) => $q->where('asesor_id', $request->asesor_id))
            ->when($request->fecha_inicio, fn($q) => $q->whereDate('fecha', '>=', $request->fecha_inicio))
            ->when($request->fecha_fin, fn($q) => $q->whereDate('fecha', '<=', $request->fecha_fin))
            ->orderBy('fecha', 'desc')
            ->get();

        return view('reportes.index', [
            'asesores' => Asesor::orderBy('nombre')->get(),
            'ordenes' => $ordenes,
            'filtros' => $request->only('asesor_id', 'fecha_inicio', 'fecha_fin'),
        ]);
    }

    /**
     * Exportar una orden completa en PDF con formato Volkswagen
     */
    public function exportarPDF($id)
{
    $orden = Orden::with('asesor', 'revisiones')->findOrFail($id);
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reportes.pdf', compact('orden'))->setPaper('a4', 'portrait');
    return $pdf->download("orden_{$orden->numero_orden}.pdf");
}
}

