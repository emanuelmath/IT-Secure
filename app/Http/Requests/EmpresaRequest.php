<?php

namespace App\Http\Requests;

use App\Models\Empresa;
use Illuminate\Foundation\Http\FormRequest;

class EmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->tieneRol('Administrador');
    }

    public function rules(): array
    {
        $parametro = $this->route('empresa');
        $id = $parametro instanceof Empresa ? $parametro->id : $parametro;

        return [
            'nombre' => ['required', 'string', 'max:150'],
            'nit' => ['required', 'string', 'max:20', 'unique:empresas,nit,' . $id],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'La razón social o nombre de la empresa es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder los 150 caracteres.',
            'nit.required' => 'El número de NIT es obligatorio.',
            'nit.max' => 'El NIT no puede exceder los 20 caracteres.',
            'nit.unique' => 'Este número de NIT ya está registrado para otra empresa.',
        ];
    }
}
