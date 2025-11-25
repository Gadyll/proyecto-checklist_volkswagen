<?php

namespace App\Http\Controllers;

use App\Models\Asesor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AsesorController extends Controller
{
    // ==========================================================
    // LISTADO DE ASESORES CON RESUMEN DE DESEMPEÑO
    // ==========================================================
    public function index()
    {
        // Cargamos órdenes y revisiones para poder calcular métricas
        $asesores = Asesor::with(['ordenes.revisiones'])
            ->orderBy('nombre')
            ->paginate(10);

        // Calculamos métricas básicas para cada asesor (estrellas, errores, etc.)
        foreach ($asesores as $asesor) {
            $asesor->metricas = $this->calcularResumen($asesor);
        }

        return view('asesores.index', compact('asesores'));
    }

    // ==========================================================
    // VISTA PARA CREAR ASESOR
    // ==========================================================
    public function create()
    {
        return view('asesores.create');
    }

    // ==========================================================
    // GUARDAR NUEVO ASESOR
    // ==========================================================
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'         => 'required|string|max:100',
            'apellido'       => 'nullable|string|max:100',
            'correo'         => 'nullable|email|max:150|unique:asesores,correo',
            'telefono'       => 'nullable|string|max:20',
            'fecha_registro' => 'nullable|date',
        ]);

        Asesor::create($data);

        return redirect()
            ->route('asesores.index')
            ->with('ok', 'El asesor se registró correctamente.');
    }

    // ==========================================================
    // EDITAR ASESOR
    // ==========================================================
    public function edit(Asesor $asesor)
    {
        return view('asesores.edit', compact('asesor'));
    }

    // ==========================================================
    // ACTUALIZAR ASESOR
    // ==========================================================
    public function update(Request $request, Asesor $asesor)
    {
        $data = $request->validate([
            'nombre'         => 'required|string|max:100',
            'apellido'       => 'nullable|string|max:100',
            'correo'         => [
                'nullable',
                'email',
                'max:150',
                Rule::unique('asesores', 'correo')->ignore($asesor->id),
            ],
            'telefono'       => 'nullable|string|max:20',
            'fecha_registro' => 'nullable|date',
        ]);

        $asesor->update($data);

        return redirect()
            ->route('asesores.index')
            ->with('ok', 'La información del asesor se actualizó correctamente.');
    }

    // ==========================================================
    // ELIMINAR ASESOR
    // ==========================================================
    public function destroy(Asesor $asesor)
    {
        if ($asesor->ordenes()->count() > 0) {
            return back()->with('error', 'No es posible eliminar un asesor que tiene órdenes registradas.');
        }

        $asesor->delete();

        return redirect()
            ->route('asesores.index')
            ->with('ok', 'El asesor fue eliminado correctamente del sistema.');
    }

    // ==========================================================
    // VISTA DE DESEMPEÑO DETALLADO (MÉTRICAS + ERRORES)
    // ==========================================================
    public function desempeno(Asesor $asesor)
    {
        // Cargamos todas sus órdenes con las revisiones
        $asesor->load(['ordenes.revisiones']);

        // Resumen general (estrellas, efectividad, etc.)
        $metricas = $this->calcularResumen($asesor);

        // Listado de órdenes con errores y rubros con más "NO"
        $ordenesConErrores = [];
        $erroresPorRubro   = [];

        foreach ($asesor->ordenes as $orden) {

            $erroresOrden  = 0;
            $totalChecks   = 0;
            $rubrosConNo   = [];

            foreach ($orden->revisiones as $rev) {

                foreach ([1, 2, 3] as $nivel) {
                    $campo = "revision_{$nivel}";
                    $val   = $rev->$campo;

                    if ($val === null) {
                        continue;
                    }

                    $totalChecks++;

                    if (is_string($val) && strtoupper(trim($val)) === 'NO') {
                        $erroresOrden++;

                        // Contamos rubros más conflictivos
                        $rubrosConNo[$rev->rubro] = true;

                        if (!isset($erroresPorRubro[$rev->rubro])) {
                            $erroresPorRubro[$rev->rubro] = 0;
                        }
                        $erroresPorRubro[$rev->rubro]++;
                    }
                }
            }

            if ($erroresOrden > 0) {
                $efectividad = $totalChecks > 0
                    ? round((($totalChecks - $erroresOrden) / $totalChecks) * 100, 1)
                    : 0;

                $ordenesConErrores[] = [
                    'orden'           => $orden,
                    'errores'         => $erroresOrden,
                    'total_checks'    => $totalChecks,
                    'efectividad'     => $efectividad,
                    'rubros_con_error'=> array_keys($rubrosConNo),
                ];
            }
        }

        // Ordenamos órdenes por cantidad de errores (de mayor a menor)
        usort($ordenesConErrores, function ($a, $b) {
            return $b['errores'] <=> $a['errores'];
        });

        // Ordenamos rubros por errores (de mayor a menor) y tomamos top 5
        arsort($erroresPorRubro);
        $topRubros = array_slice($erroresPorRubro, 0, 5, true);

        return view('asesores.desempeno', [
            'asesor'            => $asesor,
            'metricas'          => $metricas,
            'ordenesConErrores'=> $ordenesConErrores,
            'topRubros'         => $topRubros,
        ]);
    }

    // ==========================================================
    // FUNCIÓN PRIVADA PARA CALCULAR RESUMEN + ESTRELLAS
    // ==========================================================
    private function calcularResumen(Asesor $asesor): array
    {
        $ordenes = $asesor->ordenes ?? collect();

        $totalChecks = 0;
        $errores     = 0;

        foreach ($ordenes as $orden) {
            foreach ($orden->revisiones as $rev) {

                foreach ([1, 2, 3] as $nivel) {
                    $campo = "revision_{$nivel}";
                    $val   = $rev->$campo;

                    if ($val === null) {
                        continue;
                    }

                    $totalChecks++;

                    if (is_string($val) && strtoupper(trim($val)) === 'NO') {
                        $errores++;
                    }
                }
            }
        }

        $aciertos = max($totalChecks - $errores, 0);

        $efectividad = $totalChecks > 0
            ? round(($aciertos / $totalChecks) * 100, 1)
            : 100.0;

        // Conversión a estrellas (0 a 5)
        if ($efectividad >= 90) {
            $estrellas = 5;
        } elseif ($efectividad >= 80) {
            $estrellas = 4;
        } elseif ($efectividad >= 65) {
            $estrellas = 3;
        } elseif ($efectividad >= 50) {
            $estrellas = 2;
        } elseif ($efectividad > 0) {
            $estrellas = 1;
        } else {
            $estrellas = 0;
        }

        return [
            'total_ordenes' => $ordenes->count(),
            'total_checks'  => $totalChecks,
            'errores'       => $errores,
            'aciertos'      => $aciertos,
            'efectividad'   => $efectividad,
            'estrellas'     => $estrellas,
        ];
    }
}

