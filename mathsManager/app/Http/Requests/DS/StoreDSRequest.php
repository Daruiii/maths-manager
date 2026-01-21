<?php

namespace App\Http\Requests\DS;

use Illuminate\Foundation\Http\FormRequest;

class StoreDSRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Tous les utilisateurs authentifiés peuvent créer un DS
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type_bac' => 'boolean',
            'exercises_number' => 'required|integer|min:1|max:4',
            'harder_exercises' => 'boolean',
            'multiple_chapters' => 'required|array',
            'multiple_chapters.*' => 'exists:multiple_chapters,id',
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
            'exercises_number.required' => 'Le nombre d\'exercices est obligatoire.',
            'exercises_number.integer' => 'Le nombre d\'exercices doit être un nombre entier.',
            'exercises_number.min' => 'Le nombre d\'exercices doit être au minimum 1.',
            'exercises_number.max' => 'Le nombre d\'exercices doit être au maximum 4.',
            'multiple_chapters.required' => 'Vous devez sélectionner au moins un chapitre.',
            'multiple_chapters.array' => 'Les chapitres doivent être un tableau.',
            'multiple_chapters.*.exists' => 'Un des chapitres sélectionnés n\'existe pas.',
        ];
    }
}
