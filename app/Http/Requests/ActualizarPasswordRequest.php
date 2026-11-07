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
}
