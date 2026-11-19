<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;


class InsertarUsuarioRequest extends FormRequest
{
    public function rules(): array
    {
        return User::validaciones();
    }

    public function authorize(): bool
    {
        return $this->user() && $this->user()->tieneRol('Administrador');
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre completo es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder los 60 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo electrónico ya se encuentra registrado.',
            'contrasenha.required' => 'La contraseña es obligatoria.',
            'contrasenha.min' => 'La contraseña debe contener al menos 8 caracteres.',
            'roles.required' => 'Debe asignar al menos un rol al usuario.',
            'roles.array' => 'Los roles deben ser seleccionados en formato de lista.',
            'centros.*.exists' => 'Uno de los centros de cómputo seleccionados no es válido.',
        ];
    }
}
