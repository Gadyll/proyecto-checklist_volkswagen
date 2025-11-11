<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla
     * (solo necesario si no sigue la convención)
     */
    protected $table = 'ordenes';

    /**
     * Campos permitidos para asignación masiva.
     */
    protected $fillable = [
        'asesor_id',
        'numero_orden',
        'numero_chasis',
        'fecha',
        'observaciones',
    ];

    /**
     * Relación: una orden pertenece a un asesor.
     */
    public function asesor()
    {
        return $this->belongsTo(Asesor::class);
    }

    /**
     * Relación: una orden tiene muchas revisiones.
     */
    public function revisiones()
    {
        return $this->hasMany(Revision::class);
    }

    /**
     * Calcula el porcentaje de progreso de una revisión específica (1, 2 o 3)
     * según cuántos rubros estén llenos.
     */
    public function progresoRevision($numero = 1)
    {
        $total = $this->revisiones->count();
        if ($total === 0) return 0;

        $completadas = $this->revisiones->whereNotNull("revision_{$numero}")->count();
        return round(($completadas / $total) * 100, 2);
    }

    /**
     * Calcula el promedio general entre las tres revisiones.
     */
    public function progresoTotal()
    {
        $p1 = $this->progresoRevision(1);
        $p2 = $this->progresoRevision(2);
        $p3 = $this->progresoRevision(3);

        return round(($p1 + $p2 + $p3) / 3, 2);
    }

    /**
     * Devuelve el número de rubros con comentarios.
     */
    public function totalConComentarios()
    {
        return $this->revisiones->whereNotNull('comentario')->count();
    }

    /**
     * Devuelve un resumen rápido de la orden para los reportes o dashboards.
     */
    public function resumen()
    {
        return [
            'numero_orden' => $this->numero_orden,
            'asesor' => $this->asesor?->nombre . ' ' . $this->asesor?->apellido,
            'fecha' => $this->fecha,
            'progreso_rev1' => $this->progresoRevision(1),
            'progreso_rev2' => $this->progresoRevision(2),
            'progreso_rev3' => $this->progresoRevision(3),
            'progreso_total' => $this->progresoTotal(),
            'comentarios' => $this->totalConComentarios(),
        ];
    }
}
