<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ActualizarPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return User::actualizarPasswordRules();
    }

    public function authorize(): bool
    {
        return $this->user() && $this->user()->roles()->exists();
    }

    public function messages(): array
    {
        return [
            'contrasenha.required' => 'La contraseña actual es obligatoria.',
            'contrasenhaNueva.required' => 'La nueva contraseña es obligatoria.',
            'contrasenhaNueva.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'contrasenhaConfirmar.required' => 'Debe confirmar la nueva contraseña.',
            'contrasenhaConfirmar.same' => 'La confirmación no coincide con la nueva contraseña.',
        ];
    }
}
