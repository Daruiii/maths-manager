<?php

namespace App\Http\Requests\Recap;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecapPartBlockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'teacher']);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'theme' => 'nullable|string|max:50',
            'content' => 'required|string',
            'example' => 'nullable|string',
            'demonstration' => 'nullable|string',
            'remarque' => 'nullable|string',
            'recap_part_id' => 'required|exists:recap_parts,id',
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
            'recap_part_id.required' => 'La partie du récapitulatif est obligatoire.',
            'recap_part_id.exists' => 'La partie du récapitulatif sélectionnée n\'existe pas.',
            'subchapter_id.exists' => 'Le sous-chapitre sélectionné n\'existe pas.',
        ];
    }
}
