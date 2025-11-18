<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\Asesor;
use App\Models\Revision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdenController extends Controller
{
    /** RUBROS DEL CHECKLIST (tu hoja original) */
    private array $rubros = [
        'FACTURA',
        'FORMATO DE INSPECCIÓN',
        'FORMATO DE CONTROLISTA',
        'PROTOCOLO',
        'AVISO DE ADICIONALES Y PRECIO',
        'COINCIDE EN PRECIO INICIAL VS EL PRECIO FINAL',
        'NOMBRE Y FIRMA DEL ASESOR Y EL TÉCNICO EN ORDEN DE REPARACIÓN',
        'RELOJ CHECADOR',
        'FIRMA DEL CONTROLISTA',
        'AVISO DE PRIVACIDAD',
        'CONTRATO DE ADHESION FIRMADO',
        'ORDEN DE REPARACIÓN FIRMADA',
        'TICKET DE BATERIA Y MENSAJE',
        'FORMATO DE HERRAMIENTAS',
        'FORMATO DE SALIDA DE REFACCIONES',
        'TARJETA VIAJERA LLENA',
        'POSICIONES DE TRABAJO',
        'COINCIDEN LAS UNIDADES DE TIEMPO',
        'PAGO POR JEFE DE TALLER',
        'CAMPAÑAS DE REVISIÓN',
        'PREFACTURA',
        'VALE DE SALIDA',
    ];

    /** LISTAR ÓRDENES */
    public function index()
    {
        $ordenes = Orden::with(['asesor', 'revisiones'])->orderBy('id', 'DESC')->get();
        return view('ordenes.index', compact('ordenes'));
    }

    /** FORMULARIO CREAR */
    public function create()
    {
        $asesores = Asesor::orderBy('nombre')->get();
        return view('ordenes.create', compact('asesores'));
    }

    /** GUARDAR ORDEN + CHECKLIST */
    public function store(Request $request)
    {
        $data = $request->validate([
            'numero_orden'      => 'required|string|max:6|unique:ordenes,numero_orden',
            'numero_chasis'     => 'nullable|string|max:17',
            'fecha'             => 'nullable|date',
            'observaciones'     => 'nullable|string',
            'asesor_id'         => 'required|exists:asesores,id',
        ]);

        // Si no manda fecha, guardar fecha actual
        if (!$data['fecha']) {
            $data['fecha'] = now()->format('Y-m-d');
        }

        DB::transaction(function () use ($data) {
            $orden = Orden::create($data);

            foreach ($this->rubros as $rubro) {
                Revision::create([
                    'orden_id'   => $orden->id,
                    'rubro'      => $rubro,
                    'revision_1' => null,
                    'revision_2' => null,
                    'revision_3' => null,
                    'comentario' => null,
                ]);
            }
        });

        return redirect()->route('ordenes.index')->with('ok', 'Orden creada correctamente.');
    }

    /** VER ORDEN */
    public function show(Orden $orden)
    {
        $orden->load(['asesor', 'revisiones']);
        return view('ordenes.show', compact('orden'));
    }

    /** EDITAR ORDEN */
    public function edit(Orden $orden)
    {
        $asesores = Asesor::orderBy('nombre')->get();
        return view('ordenes.edit', compact('orden', 'asesores'));
    }

    /** ACTUALIZAR ORDEN */
    public function update(Request $request, Orden $orden)
    {
        $data = $request->validate([
            'numero_orden'  => 'required|string|max:50|unique:ordenes,numero_orden,' . $orden->id,
            'numero_chasis' => 'nullable|string|max:150',
            'fecha'         => 'nullable|date',
            'observaciones' => 'nullable|string',
            'asesor_id'     => 'required|exists:asesores,id',
        ]);

        $orden->update($data);

        return redirect()->route('ordenes.index')->with('ok', 'Orden actualizada correctamente.');
    }

    /** ELIMINAR ORDEN */
    public function destroy(Orden $orden)
    {
        $orden->revisiones()->delete();
        $orden->delete();

        return redirect()->route('ordenes.index')->with('ok', 'Orden eliminada.');
    }

    /** GUARDAR CHECKLIST Y COMENTARIOS */
    public function updateRevisiones(Request $request, Orden $orden)
    {
        $data = $request->input('revision', []);

        foreach ($data as $revisionId => $vals) {

            $revision = $orden->revisiones()->where('id', $revisionId)->first();
            if (!$revision) continue;

            // Revisiones
            $r1 = $vals['revision_1'] ?? null;
            $r2 = $vals['revision_2'] ?? null;
            $r3 = $vals['revision_3'] ?? null;

            // Comentario
            $comentario = $vals['comentario'] ?? null;

            // Guardar
            $revision->update([
                'revision_1' => $r1 !== '' ? $r1 : null,
                'revision_2' => $r2 !== '' ? $r2 : null,
                'revision_3' => $r3 !== '' ? $r3 : null,
                'comentario' => $comentario !== '' ? $comentario : null,
            ]);
        }

        return redirect()
            ->route('ordenes.show', $orden)
            ->with('ok', 'Checklist actualizado correctamente.');
    }
}
