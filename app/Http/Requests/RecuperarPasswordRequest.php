<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class RecuperarPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return User::recuperarRules();
    }

    public function authorize(): bool
    {
        return true;
    }
}
