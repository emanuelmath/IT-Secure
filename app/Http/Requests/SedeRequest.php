<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SedeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->tieneRol('Administrador');
    }

    public function rules(): array
    {
        return [
            'empresa_id' => ['required', 'integer', 'exists:empresas,id'],
            'nombre' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'empresa_id.required' => 'Debe asociar la sede a una empresa.',
            'empresa_id.exists' => 'La empresa seleccionada no es válida.',
            'nombre.required' => 'El nombre de la sede es obligatorio.',
            'nombre.max' => 'El nombre de la sede no puede exceder los 100 caracteres.',
        ];
    }
}
