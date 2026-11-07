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

        ];
    }
}
