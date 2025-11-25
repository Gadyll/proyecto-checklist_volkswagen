<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\Revision;
use App\Models\Asesor;
use Illuminate\Http\Request;
use App\Http\Requests\StoreOrdenRequest;

class OrdenController extends Controller
{
    // ===========================================================
    // LISTADO GENERAL
    // ===========================================================
    public function index()
    {
        $ordenes = Orden::with('asesor')
            ->orderBy('id', 'DESC')
            ->paginate(15);

        return view('ordenes.index', compact('ordenes'));
    }

    // ===========================================================
    // FORMULARIO: CREAR ORDEN
    // ===========================================================
    public function create()
    {
        $asesores = Asesor::orderBy('nombre')->get();
        return view('ordenes.create', compact('asesores'));
    }

    // ===========================================================
    // GUARDAR ORDEN NUEVA
    // ===========================================================
    public function store(StoreOrdenRequest $request)
    {
        $orden = Orden::create([
            'numero_orden'   => $request->numero_orden,
            'numero_chasis'  => $request->numero_chasis,
            'fecha'          => $request->fecha,
            'asesor_id'      => $request->asesor_id,
            'observaciones'  => $request->observaciones,
        ]);

        // ======================================================
        // CHECKLIST OFICIAL COMPLETO VW
        // ======================================================
        $rubros = [
            "FACTURA",
            "FORMATO DE INSPECCIÓN",
            "FORMATO DE CONTROLISTA",
            "PROTOCOLO",
            "AVISO DE ADICIONALES Y PRECIO",
            "COINCIDE EN PRECIO INICIAL VS EL PRECIO FINAL",
            "NOMBRE Y FIRMA DEL ASESOR Y EL TÉCNICO EN ORDEN DE REPARACIÓN",
            "RELOJ CHECADOR",
            "FIRMA DEL CONTROLISTA",
            "AVISO DE PRIVACIDAD",
            "CONTRATO DE ADHESIÓN FIRMADO",
            "ORDEN DE REPARACIÓN FIRMADA",
            "TICKET DE BATERÍA Y MENSAJE",
            "FORMATO DE HERRAMIENTAS",
            "FORMATO DE SALIDA DE REFACCIONES",
            "TARJETA VIAJERA LLENA",
            "POSICIONES DE TRABAJO",
            "COINCIDEN LAS UNIDADES DE TIEMPO",
            "PAGO POR JEFE DE TALLER",
            "CAMPAÑAS DE REVISIÓN",
            "PREFACTURA",
            "VALE DE SALIDA"
        ];

        foreach ($rubros as $r) {
            Revision::create([
                'orden_id' => $orden->id,
                'rubro'    => $r
            ]);
        }

        return redirect()
            ->route('ordenes.index')
            ->with('ok', 'La orden fue registrada exitosamente junto con su checklist oficial.');
    }

    // ===========================================================
    // VER DETALLE DE LA ORDEN
    // ===========================================================
    public function show(Orden $orden)
    {
        $revisiones = $orden->revisiones;

        // Progreso basado en si la revisión tiene un valor válido (SI / NO / NA)
        $total = $revisiones->count();

        $completadas = $revisiones->filter(function ($rev) {
            return $rev->revision_1 !== null && $rev->revision_1 !== '';
        })->count();

        $porcentaje = $total > 0 ? round(($completadas / $total) * 100) : 0;

        return view('ordenes.show', compact('orden', 'revisiones', 'porcentaje'));
    }

    // ===========================================================
    // FORMULARIO: EDITAR ORDEN
    // ===========================================================
    public function edit(Orden $orden)
    {
        $asesores = Asesor::orderBy('nombre')->get();
        return view('ordenes.edit', compact('orden', 'asesores'));
    }

    // ===========================================================
    // ACTUALIZAR ORDEN
    // ===========================================================
    public function update(StoreOrdenRequest $request, Orden $orden)
    {
        $orden->update($request->validated());

        return redirect()
            ->route('ordenes.index')
            ->with('ok', 'Los cambios de la orden han sido guardados correctamente.');
    }

    // ===========================================================
    // ELIMINAR ORDEN
    // ===========================================================
    public function destroy(Orden $orden)
    {
        $orden->delete();

        return redirect()
            ->route('ordenes.index')
            ->with('ok', 'La orden fue eliminada de forma exitosa.');
    }

    // ===========================================================
    // ACTUALIZAR CHECKLIST
    // ===========================================================
    public function updateRevisiones(Request $request, Orden $orden)
    {
        if (!$request->has('revision')) {
            return back()->with('error', 'No se recibió información del checklist para actualizar.');
        }

        foreach ($request->revision as $id => $vals) {
            $rev = Revision::find($id);
            if (!$rev) continue;

            $rev->update([
                'revision_1' => $vals['revision_1'] ?? null,
                'revision_2' => $vals['revision_2'] ?? null,
                'revision_3' => $vals['revision_3'] ?? null,
                'comentario' => $vals['comentario'] ?? null
            ]);
        }

        return back()->with('ok', 'El checklist ha sido actualizado correctamente.');
    }
}

