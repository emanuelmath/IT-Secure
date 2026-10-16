<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return User::loginRules();
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            "email.required" => "El correo del usuario es requerido.",
            'contrasenha.required' => 'La contraseña es obligatoria.'
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'correo del usuario',
            'contrasenha' => 'contraseña'
        ];
    }
}
