<?php

namespace App\Http\Requests\Recap;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecapPartBlockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'theme' => 'nullable|string|max:50',
            'example' => 'nullable|string',
            'latex_example' => 'nullable|string',
            'demonstration' => 'nullable|string',
            'latex_demonstration' => 'nullable|string',
            'remarque' => 'nullable|string',
            'latex_remarque' => 'nullable|string',
            'content' => 'required|string',
            'latex_content' => 'nullable|string',
            'subchapter_id' => 'nullable|exists:subchapters,id'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'content.required' => 'Le contenu est obligatoire.',
            'theme.max' => 'Le thème ne peut pas dépasser 50 caractères.',
            'subchapter_id.exists' => 'Le sous-chapitre sélectionné n\'existe pas.',
        ];
    }
}
