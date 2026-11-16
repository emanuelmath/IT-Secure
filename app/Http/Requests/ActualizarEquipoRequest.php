<?php

namespace App\Http\Requests;

use App\Models\Equipo;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ActualizarEquipoRequest extends FormRequest
{
    public function rules(): array
    {
        $parametro = $this->route('equipo');
        $id = $parametro instanceof Equipo ? $parametro->id : $parametro;
        return Equipo::validaciones($id);
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
