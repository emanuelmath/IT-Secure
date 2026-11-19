<?php

namespace App\Http\Requests;

use App\Models\Equipo;
use Illuminate\Foundation\Http\FormRequest;

class InsertarEquipoRequest extends FormRequest
{
    public function rules(): array
    {
        return Equipo::validaciones();
    }

    public function authorize(): bool
    {
        return $this->user() && $this->user()->tieneRol(['Administrador', 'Supervisor']);
    }

    public function messages(): array
    {
        return [
            'centros_de_computo_id.required' => 'Debe asignar un centro de cómputo al equipo.',
            'centros_de_computo_id.exists' => 'El centro de cómputo seleccionado no es válido.',
            'codigo_inventario.required' => 'El código de inventario es obligatorio.',
            'codigo_inventario.unique' => 'Este código de inventario ya está registrado en el sistema.',
            'codigo_inventario.max' => 'El código de inventario no puede exceder 50 caracteres.',
            'tipo.required' => 'Debe seleccionar el tipo de hardware.',
            'tipo.in' => 'El tipo de equipo seleccionado no es válido.',
            'estado.required' => 'Debe indicar el estado operativo del equipo.',
            'estado.in' => 'El estado seleccionado no es válido.',
            'dias_frecuencia_mantenimiento.required' => 'Debe especificar la frecuencia de mantenimiento en días.',
            'dias_frecuencia_mantenimiento.integer' => 'La frecuencia de mantenimiento debe ser un número entero.',
            'dias_frecuencia_mantenimiento.min' => 'La frecuencia debe ser de al menos 1 día.',
        ];
    }
}
