<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CentroComputoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->tieneRol('Administrador');
    }

    public function rules(): array
    {
        return [
            'sede_id' => ['required', 'integer', 'exists:sedes,id'],
            'codigo' => ['required', 'string', 'max:50'],
            'nombre' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'sede_id.required' => 'Debe seleccionar una sede física.',
            'sede_id.exists' => 'La sede física seleccionada no es válida.',
            'codigo.required' => 'El código identificador es obligatorio.',
            'codigo.max' => 'El código no puede superar 50 caracteres.',
            'nombre.required' => 'El nombre del centro o laboratorio es obligatorio.',
            'nombre.max' => 'El nombre no puede superar 100 caracteres.',
        ];
    }
}
