<?php

namespace App\Http\Requests\WhitelistRequest;

use Illuminate\Foundation\Http\FormRequest;

class RejectWhitelistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'teacher']);
    }

    public function rules(): array
    {
        return [
            'admin_response' => 'required|string|max:500'
        ];
    }

    public function messages(): array
    {
        return [
            'admin_response.required' => 'Une réponse est obligatoire pour rejeter une demande.',
            'admin_response.max' => 'La réponse ne peut pas dépasser 500 caractères.',
        ];
    }
}
