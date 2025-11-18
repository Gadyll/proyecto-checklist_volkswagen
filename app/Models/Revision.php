<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla
     * (opcional si el nombre coincide con el plural del modelo)
     */
    protected $table = 'revisiones';

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'orden_id',
        'rubro',
        'revision_1',
        'revision_2',
        'revision_3',
        'comentario',
    ];

    /**
     * Relación: una revisión pertenece a una orden.
     */
    public function orden()
    {
        return $this->belongsTo(Orden::class);
    }

    /**
     * Devuelve true si todas las revisiones están completadas (sin nulls).
     */
    public function estaCompleta()
    {
        return !is_null($this->revision_1)
            && !is_null($this->revision_2)
            && !is_null($this->revision_3);
    }

    /**
     * Calcula el número total de revisiones completadas (0 a 3)
     */
    public function totalCompletadas()
    {
        return collect([$this->revision_1, $this->revision_2, $this->revision_3])
            ->filter(fn($v) => !is_null($v))
            ->count();
    }

    /**
     * Devuelve porcentaje de avance individual (por rubro)
     */
    public function progreso()
    {
        return round(($this->totalCompletadas() / 3) * 100);
    }

    /**
     * Scope para filtrar por estado de revisión
     * Ejemplo: Revision::completadas(1)->get()  →  devuelve las que tienen revision_1 llena
     */
    public function scopeCompletadas($query, $nivel = 1)
    {
        return $query->whereNotNull('revision_' . $nivel);
    }
}

