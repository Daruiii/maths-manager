<?php

namespace App\Http\Requests\Quizz;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
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
            'name' => 'nullable',
            'question' => 'required',
            'chapter_id' => 'required|exists:chapters,id',
            'subchapter_id' => 'required|exists:subchapters,id'
        ];
    }

    public function messages(): array
    {
        return [
            'question.required' => 'La question est obligatoire.',
            'chapter_id.required' => 'Le chapitre est obligatoire.',
            'chapter_id.exists' => 'Le chapitre sélectionné n\'existe pas.',
            'subchapter_id.required' => 'Le sous-chapitre est obligatoire.',
            'subchapter_id.exists' => 'Le sous-chapitre sélectionné n\'existe pas.',
        ];
    }
}
