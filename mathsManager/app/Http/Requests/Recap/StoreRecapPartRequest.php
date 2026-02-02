<?php

namespace App\Http\Requests\Recap;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecapPartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'teacher']);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'recap_id' => 'required|exists:recaps,id'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'recap_id.required' => 'Le récapitulatif est obligatoire.',
            'recap_id.exists' => 'Le récapitulatif sélectionné n\'existe pas.',
        ];
    }
}
