<?php

namespace App\Http\Requests\MultipleChapter;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMultipleChapterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'theme' => 'nullable|string|max:7'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'theme.max' => 'Le code couleur ne peut pas dépasser 7 caractères.',
        ];
    }
}
