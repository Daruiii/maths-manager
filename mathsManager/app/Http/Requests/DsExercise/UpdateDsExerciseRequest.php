<?php

namespace App\Http\Requests\DsExercise;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDsExerciseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'teacher']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'multiple_chapter_id' => 'required|exists:multiple_chapters,id',
            'difficulty' => 'required|integer|min:1|max:5',
            'time' => 'required|integer',
            'name' => 'nullable|max:255',
            'statement' => 'required',
            'latex_statement' => 'nullable',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'string',
            'filter' => 'nullable|string',
            'image_order' => 'nullable|string',
            'correction_pdf' => 'nullable|file|mimes:pdf|max:2048',
            'delete_correction_pdf' => 'nullable|boolean',
            'type' => 'nullable|string',
            'year' => 'nullable|integer',
            'academy' => 'nullable|string',
            'date_data' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'multiple_chapter_id.required' => 'Le multi-chapitre est obligatoire.',
            'multiple_chapter_id.exists' => 'Le multi-chapitre sélectionné n\'existe pas.',
            'difficulty.required' => 'La difficulté est obligatoire.',
            'difficulty.integer' => 'La difficulté doit être un nombre entier.',
            'difficulty.min' => 'La difficulté doit être entre 1 et 5 étoiles.',
            'difficulty.max' => 'La difficulté doit être entre 1 et 5 étoiles.',
            'time.required' => 'Le temps est obligatoire.',
            'time.integer' => 'Le temps doit être un nombre entier.',
            'statement.required' => 'L\'énoncé est obligatoire.',
            'images.*.image' => 'Chaque fichier doit être une image.',
            'images.*.mimes' => 'Les images doivent être au format jpeg, png, jpg, gif ou svg.',
            'images.*.max' => 'Chaque image ne doit pas dépasser 2 MB.',
            'correction_pdf.mimes' => 'Le fichier de correction doit être au format PDF.',
            'correction_pdf.max' => 'Le PDF de correction ne doit pas dépasser 2 MB.',
        ];
    }
}
