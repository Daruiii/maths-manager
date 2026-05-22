<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class ConfigureInvitationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canActAsTeacher() && $this->user()?->status === 'active';
    }

    public function rules(): array
    {
        return [
            'label' => ['nullable', 'string', 'max:80'],
            'max_uses' => ['required', 'integer', 'min:1', 'max:1000'],
            'group_id' => ['nullable', 'integer', 'exists:student_groups,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'max_uses.required' => 'Le nombre d\'utilisations est obligatoire.',
            'max_uses.min'      => 'Le minimum est 1 utilisation.',
            'label.max'         => 'Le libellé ne doit pas dépasser 80 caractères.',
            'group_id.exists'   => 'Ce groupe n\'existe pas.',
        ];
    }
}
