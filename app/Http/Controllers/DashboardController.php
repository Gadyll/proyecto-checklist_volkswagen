<?php

namespace App\Http\Controllers;

use App\Models\Asesor;
use App\Models\Orden;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Totales
        $totalAsesores = Asesor::count();
        $totalOrdenes = Orden::count();

        // Órdenes recientes
        $ultimasOrdenes = Orden::with('asesor')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Gráfico de órdenes por asesor
        $ordenesPorAsesor = Asesor::withCount('ordenes')->get(['nombre', 'ordenes_count']);

        return view('dashboard', compact(
            'totalAsesores',
            'totalOrdenes',
            'ultimasOrdenes',
            'ordenesPorAsesor'
        ));
    }
}
