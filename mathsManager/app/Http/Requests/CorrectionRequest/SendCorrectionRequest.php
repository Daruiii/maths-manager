<?php

namespace App\Http\Requests\CorrectionRequest;

use Illuminate\Foundation\Http\FormRequest;

class SendCorrectionRequest extends FormRequest
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
            'pictures' => 'required|array|min:1',
            'pictures.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'message' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'pictures.required' => 'Au moins une photo de votre copie est obligatoire.',
            'pictures.array' => 'Le format des photos est invalide.',
            'pictures.min' => 'Vous devez envoyer au moins une photo.',
            'pictures.*.required' => 'Chaque photo est obligatoire.',
            'pictures.*.image' => 'Chaque fichier doit être une image.',
            'pictures.*.mimes' => 'Les images doivent être au format jpeg, png, jpg, gif ou svg.',
            'message.max' => 'Le message ne peut pas dépasser 255 caractères.',
        ];
    }
}
