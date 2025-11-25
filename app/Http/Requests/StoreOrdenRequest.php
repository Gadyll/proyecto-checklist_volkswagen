<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrdenRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Permite que el usuario utilice el Request
    }

    public function rules()
    {
        // Detectar si es edición para permitir el mismo número de orden
        $ordenId = $this->route('orden') ? $this->route('orden')->id : null;

        return [
            'numero_orden'   => "required|digits:6|numeric|unique:ordenes,numero_orden,$ordenId",
            'numero_chasis'  => 'required|alpha_num|size:17',
            'fecha'          => 'required|date',
            'asesor_id'      => 'required|exists:asesores,id',
            'observaciones'  => 'nullable|string|max:500'
        ];
    }

    public function messages()
    {
        return [
            // ============================
            // NÚMERO DE ORDEN
            // ============================
            'numero_orden.required' => 'El número de orden es obligatorio.',
            'numero_orden.digits'   => 'El número de orden debe contener exactamente 6 dígitos.',
            'numero_orden.numeric'  => 'El número de orden solo puede contener números.',
            'numero_orden.unique'   => 'Este número de orden ya está registrado. Por favor ingresa un número diferente.',

            // ============================
            // NÚMERO DE CHASIS
            // ============================
            'numero_chasis.required' => 'El número de chasis es obligatorio.',
            'numero_chasis.alpha_num'=> 'El número de chasis solo puede contener letras y números.',
            'numero_chasis.size'     => 'El número de chasis debe tener exactamente 17 caracteres.',

            // ============================
            // FECHA
            // ============================
            'fecha.required' => 'La fecha de la orden es obligatoria.',
            'fecha.date'     => 'La fecha ingresada no es válida.',

            // ============================
            // ASESOR
            // ============================
            'asesor_id.required' => 'Debe seleccionar un asesor responsable.',
            'asesor_id.exists'   => 'El asesor seleccionado no existe en el sistema.',

            // ============================
            // OBSERVACIONES
            // ============================
            'observaciones.max' => 'Las observaciones no pueden exceder los 500 caracteres.'
        ];
    }
}

