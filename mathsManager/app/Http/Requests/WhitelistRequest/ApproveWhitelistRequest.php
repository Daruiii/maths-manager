<?php

namespace App\Http\Requests\WhitelistRequest;

use Illuminate\Foundation\Http\FormRequest;

class ApproveWhitelistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'admin_response' => 'nullable|string|max:500'
        ];
    }

    public function messages(): array
    {
        return [
            'admin_response.max' => 'La réponse de l\'administrateur ne peut pas dépasser 500 caractères.',
        ];
    }
}
