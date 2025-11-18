<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asesor extends Model
{
    protected $table = 'asesores';            
    protected $fillable = ['nombre','apellido','correo','telefono','fecha_registro'];

    public function ordenes()
    {
        return $this->hasMany(Orden::class);
    }
}

