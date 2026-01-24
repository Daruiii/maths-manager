<?php

namespace App\Http\Requests\DS;

use Illuminate\Foundation\Http\FormRequest;

class ReAssignDSRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Seuls les admins et teachers peuvent réassigner des DS
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
            'user_id' => 'required|exists:users,id',
            'ds_id' => 'required|exists:DS,id',
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
            'user_id.required' => 'L\'élève est obligatoire.',
            'user_id.exists' => 'L\'élève sélectionné n\'existe pas.',
            'ds_id.required' => 'Le DS est obligatoire.',
            'ds_id.exists' => 'Le DS sélectionné n\'existe pas.',
        ];
    }
}
