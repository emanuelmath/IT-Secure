<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsertarTrasladoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->tieneRol(['Administrador', 'Supervisor']);
    }

    public function rules(): array
    {
        return [
            'equipo_id' => ['required', 'integer', 'exists:equipos,id'],
            'cc_origen_id' => ['required', 'integer', 'exists:centros_de_computo,id'],
            'cc_destino_id' => ['required', 'integer', 'exists:centros_de_computo,id', 'different:cc_origen_id'],
            'fecha_traslado' => ['required', 'date'],
            'motivo' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'equipo_id.required' => 'Debe seleccionar un equipo.',
            'equipo_id.exists' => 'El equipo seleccionado no es válido o no existe.',
            'cc_origen_id.required' => 'El centro de cómputo de origen es obligatorio.',
            'cc_destino_id.required' => 'Debe seleccionar un centro de cómputo de destino.',
            'cc_destino_id.different' => 'El centro de destino debe ser diferente al centro de origen.',
            'fecha_traslado.required' => 'La fecha de traslado es obligatoria.',
            'fecha_traslado.date' => 'La fecha de traslado debe tener un formato válido.',
            'motivo.required' => 'Debe ingresar el motivo del traslado.',
        ];
    }
}
