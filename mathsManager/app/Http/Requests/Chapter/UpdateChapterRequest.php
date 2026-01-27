<?php

namespace App\Http\Requests\Chapter;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChapterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'teacher']);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'theme' => 'nullable|string|max:7'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'class_id.required' => 'La classe est obligatoire.',
            'class_id.exists' => 'La classe sélectionnée n\'existe pas.',
            'theme.max' => 'Le code couleur ne peut pas dépasser 7 caractères.',
        ];
    }
}
