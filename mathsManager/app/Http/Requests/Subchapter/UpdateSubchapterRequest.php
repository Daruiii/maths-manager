<?php

namespace App\Http\Requests\Subchapter;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubchapterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'teacher']);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'chapter_id' => 'required|exists:chapters,id',
            'description' => 'nullable|string'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'chapter_id.required' => 'Le chapitre est obligatoire.',
            'chapter_id.exists' => 'Le chapitre sélectionné n\'existe pas.',
        ];
    }
}
