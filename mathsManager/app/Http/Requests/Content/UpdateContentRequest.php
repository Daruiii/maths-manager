<?php

namespace App\Http\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'teacher']);
    }

    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Le contenu est obligatoire.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être au format jpeg, png, jpg, gif, svg ou webp.',
            'image.max' => 'L\'image ne peut pas dépasser 2 Mo.',
        ];
    }
}
