<?php

namespace App\Http\Requests\Td;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class AssignTdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role, ['admin', 'teacher']);
    }

    public function rules(): array
    {
        return [
            'exercise_ids'           => 'nullable|array',
            'exercise_ids.*'         => 'exists:exercises,id',
            'private_exercise_ids'   => 'nullable|array',
            'private_exercise_ids.*' => 'exists:private_exercises,id',
            'student_ids'            => 'required|array|min:1',
            'student_ids.*'          => 'exists:users,id',
            'group_ids'              => 'nullable|array',
            'group_ids.*'            => 'exists:student_groups,id',
            'custom_title'           => 'nullable|string|max:255',
            'custom_level'           => 'nullable|string|max:255',
            'custom_instructions'    => 'nullable|string',
            'due_date'               => 'nullable|date|after_or_equal:today',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $total = count((array) $this->input('exercise_ids', []))
                + count((array) $this->input('private_exercise_ids', []));

            if ($total === 0) {
                $validator->errors()->add('content', 'Sélectionnez au moins un exercice pour le TD.');
            }
        });
    }
}
