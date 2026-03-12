<?php

namespace App\Http\Requests\DS;

use Illuminate\Foundation\Http\FormRequest;

class AssignDSRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Seuls les admins et teachers peuvent assigner des DS
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
            'problems' => 'required|array',
            'problems.*' => 'exists:problems,id',
            'user_id' => 'required|exists:users,id',
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
            'problems.required' => 'Vous devez sélectionner au moins un exercice.',
            'problems.array' => 'Les exercices doivent être un tableau.',
            'problems.*.exists' => 'Un des exercices sélectionnés n\'existe pas.',
            'user_id.required' => 'L\'élève est obligatoire.',
            'user_id.exists' => 'L\'élève sélectionné n\'existe pas.',
        ];
    }
}
