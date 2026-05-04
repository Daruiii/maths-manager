<?php

namespace App\Http\Requests\Td;

use Illuminate\Foundation\Http\FormRequest;

class AssignTdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role, ['admin', 'teacher']);
    }

    public function rules(): array
    {
        return [
            'exercise_ids'           => 'required_without:private_exercise_ids|array|min:1',
            'exercise_ids.*'         => 'exists:exercises,id',
            'private_exercise_ids'   => 'required_without:exercise_ids|array|min:1',
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
}
