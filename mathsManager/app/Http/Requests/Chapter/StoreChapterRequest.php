<?php

namespace App\Http\Requests\Chapter;

use Illuminate\Foundation\Http\FormRequest;

class StoreChapterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'order' => 'required|integer|min:1',
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
            'order.required' => 'L\'ordre est obligatoire.',
            'order.integer' => 'L\'ordre doit être un nombre entier.',
            'order.min' => 'L\'ordre doit être au minimum 1.',
            'theme.max' => 'Le code couleur ne peut pas dépasser 7 caractères.',
        ];
    }
}
