<?php

namespace App\Http\Requests\ExercisesSheet;

use Illuminate\Foundation\Http\FormRequest;

class StoreExercisesSheetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'exercises' => 'required|array',
            'exercises.*' => 'exists:exercises,id',
            'chapter_id' => 'required|exists:chapters,id',
            'title' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'L\'élève est obligatoire.',
            'user_id.exists' => 'L\'élève sélectionné n\'existe pas.',
            'exercises.required' => 'Au moins un exercice doit être sélectionné.',
            'exercises.array' => 'Le format des exercices est invalide.',
            'exercises.*.exists' => 'Un ou plusieurs exercices sélectionnés n\'existent pas.',
            'chapter_id.required' => 'Le chapitre est obligatoire.',
            'chapter_id.exists' => 'Le chapitre sélectionné n\'existe pas.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
        ];
    }
}
