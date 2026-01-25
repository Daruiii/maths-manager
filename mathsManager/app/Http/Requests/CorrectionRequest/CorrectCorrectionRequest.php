<?php

namespace App\Http\Requests\CorrectionRequest;

use Illuminate\Foundation\Http\FormRequest;

class CorrectCorrectionRequest extends FormRequest
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
            'correction_pictures' => 'nullable|array',
            'correction_pictures.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'delete_correction_pictures' => 'nullable|array',
            'delete_correction_pictures.*' => 'string',
            'correction_pdf' => 'nullable|mimes:pdf',
            'grade' => 'required|numeric|min:0|max:20',
            'correction_message' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'correction_pictures.*.image' => 'Chaque fichier de correction doit être une image.',
            'correction_pictures.*.mimes' => 'Les images de correction doivent être au format jpeg, png, jpg, gif ou svg.',
            'correction_pdf.mimes' => 'Le fichier de correction doit être au format PDF.',
            'grade.required' => 'La note est obligatoire.',
            'grade.numeric' => 'La note doit être un nombre.',
            'grade.min' => 'La note doit être au minimum 0.',
            'grade.max' => 'La note doit être au maximum 20.',
            'correction_message.max' => 'Le message de correction ne peut pas dépasser 255 caractères.',
        ];
    }
}
