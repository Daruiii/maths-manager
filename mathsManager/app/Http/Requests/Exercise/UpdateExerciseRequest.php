<?php

namespace App\Http\Requests\Exercise;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExerciseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Seuls les admins et teachers peuvent modifier des exercices
        return $this->user() && in_array($this->user()->role, ['admin', 'teacher']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'subchapter_id' => 'required|exists:subchapters,id',
            'statement' => 'required|string',
            'clue' => 'nullable|string',
            'solution' => 'nullable|string',
            'name' => 'nullable|string|max:255',
            'difficulty' => 'required|integer|min:1|max:5',
            'images_statement' => 'nullable|array',
            'images_statement.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images_solution' => 'nullable|array',
            'images_solution.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'delete_images_statement' => 'nullable|array',
            'delete_images_statement.*' => 'string',
            'delete_images_solution' => 'nullable|array',
            'delete_images_solution.*' => 'string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'subchapter_id.required' => 'Le sous-chapitre est obligatoire.',
            'subchapter_id.exists' => 'Le sous-chapitre sélectionné n\'existe pas.',
            'statement.required' => 'L\'énoncé est obligatoire.',
            'difficulty.required' => 'La difficulté est obligatoire.',
            'difficulty.integer' => 'La difficulté doit être un nombre entier.',
            'difficulty.min' => 'La difficulté doit être au minimum 1.',
            'difficulty.max' => 'La difficulté doit être au maximum 5.',
            'images_statement.*.image' => 'Chaque fichier de l\'énoncé doit être une image.',
            'images_statement.*.mimes' => 'Les images de l\'énoncé doivent être au format jpeg, png, jpg, gif ou svg.',
            'images_statement.*.max' => 'Chaque image de l\'énoncé ne doit pas dépasser 2 MB.',
            'images_solution.*.image' => 'Chaque fichier de la solution doit être une image.',
            'images_solution.*.mimes' => 'Les images de la solution doivent être au format jpeg, png, jpg, gif ou svg.',
            'images_solution.*.max' => 'Chaque image de la solution ne doit pas dépasser 2 MB.',
        ];
    }
}
