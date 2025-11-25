<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrdenRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Permite que cualquier usuario autenticado use el request
    }

    public function rules()
    {
        return [
            'numero_orden'   => 'required|digits:6',
            'numero_chasis'  => 'required|size:17|regex:/^[A-Z0-9]+$/',
            'fecha'          => 'required|date',
            'asesor_id'      => 'required|exists:asesores,id',
            'observaciones'  => 'nullable|string|max:500'
        ];
    }

    public function messages()
    {
        return [
            'numero_orden.required' => 'El número de orden es obligatorio.',
            'numero_orden.digits'   => 'El número de orden debe tener exactamente 6 dígitos.',

            'numero_chasis.required' => 'El número de chasis es obligatorio.',
            'numero_chasis.size'     => 'El número de chasis debe tener exactamente 17 caracteres.',
            'numero_chasis.regex'    => 'El número de chasis solo puede contener letras MAYÚSCULAS y números.',

            'fecha.required' => 'La fecha es obligatoria.',

            'asesor_id.required' => 'Debe seleccionar un asesor.',
            'asesor_id.exists'   => 'El asesor seleccionado no existe.'
        ];
    }
}
