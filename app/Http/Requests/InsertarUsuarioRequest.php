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
        return true;
    }

    public function messages()
    {
       return [];
    }
}
