<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ActualizarUserRequest extends FormRequest
{
    public function rules(): array
    {
        $parametro = $this->route('usuario');
        $id = $parametro instanceof User ? $parametro->id : $parametro;

        return User::validaciones($id);
    }

    public function messages(): array
    {
        return (new InsertarUsuarioRequest)->messages();
    }

    public function attributes(): array
    {
        return (new InsertarUsuarioRequest)->attributes();
    }

    public function authorize(): bool
    {
        return true;
    }
}
