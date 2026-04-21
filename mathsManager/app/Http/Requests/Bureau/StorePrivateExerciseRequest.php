<?php

namespace App\Http\Requests\Bureau;

use Illuminate\Foundation\Http\FormRequest;

class StorePrivateExerciseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization déléguée à PrivateExercisePolicy via le controller.
    }

    public function rules(): array
    {
        return [
            'type'             => 'required|in:basic,problem',
            'name'             => 'required|string|max:255',
            'notes'            => 'nullable|string|max:500',
            'latex_statement'  => 'required|string',
            'latex_solution'   => 'nullable|string',
            'latex_clue'       => 'nullable|string',
            'difficulty'       => 'nullable|integer|min:1|max:5',
            'time'             => 'nullable|integer|min:1|max:300',
            'classe_id'        => 'nullable|exists:classes,id',
            'chapter_id'       => 'nullable|exists:chapters,id',
            'subchapter_id'    => 'nullable|exists:subchapters,id',
            'tag_ids'          => 'nullable|array',
            'tag_ids.*'        => 'exists:teacher_tags,id',
            'pending_images'   => 'nullable|array',
            'pending_images.*' => 'image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required'  => 'Le type est obligatoire.',
            'name.required'  => 'Le nom est obligatoire.',
            'name.max'            => 'Le nom ne doit pas dépasser 255 caractères.',
            'latex_statement.required' => "L'énoncé est obligatoire.",
            'difficulty.min' => 'La difficulté doit être entre 1 et 5.',
            'difficulty.max' => 'La difficulté doit être entre 1 et 5.',
            'time.min'       => 'La durée doit être d\'au moins 1 minute.',
            'time.max'       => 'La durée ne doit pas dépasser 300 minutes.',
        ];
    }
}
