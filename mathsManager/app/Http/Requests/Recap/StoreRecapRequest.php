<?php

namespace App\Http\Requests\Recap;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecapRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'chapter_id' => 'required|exists:chapters,id'
        ];
    }

    public function messages(): array
    {
        return [
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'chapter_id.required' => 'Le chapitre est obligatoire.',
            'chapter_id.exists' => 'Le chapitre sélectionné n\'existe pas.',
        ];
    }
}
