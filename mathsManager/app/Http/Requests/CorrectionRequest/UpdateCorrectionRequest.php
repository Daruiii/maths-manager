<?php

namespace App\Http\Requests\CorrectionRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCorrectionRequest extends FormRequest
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
            'pictures' => 'nullable|array',
            'pictures.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'delete_pictures' => 'nullable|array',
            'delete_pictures.*' => 'string',
            'message' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'pictures.array' => 'Les images doivent être un tableau.',
            'pictures.*.image' => 'Chaque fichier doit être une image.',
            'pictures.*.mimes' => 'Les images doivent être au format jpeg, png, jpg ou gif.',
            'pictures.*.max' => 'Chaque image ne doit pas dépasser 2 MB.',
            'message.max' => 'Le commentaire ne doit pas dépasser 1000 caractères.',
        ];
    }
}
