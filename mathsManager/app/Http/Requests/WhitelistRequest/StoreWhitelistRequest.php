<?php

namespace App\Http\Requests\WhitelistRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreWhitelistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => 'nullable|string|max:1000'
        ];
    }

    public function messages(): array
    {
        return [
            'message.max' => 'Le message ne peut pas dépasser 1000 caractères.',
        ];
    }
}
