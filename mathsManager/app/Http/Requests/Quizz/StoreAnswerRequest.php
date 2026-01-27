<?php

namespace App\Http\Requests\Quizz;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnswerRequest extends FormRequest
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
            'answer' => 'required',
            'explanation' => 'nullable',
            'is_correct' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'answer.required' => 'La réponse est obligatoire.',
            'is_correct.required' => 'Vous devez indiquer si la réponse est correcte ou non.',
        ];
    }
}
