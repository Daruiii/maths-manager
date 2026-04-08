<?php

namespace App\Http\Requests\DM;

use Illuminate\Foundation\Http\FormRequest;

class AssignDmBuilderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role, ['admin', 'teacher']);
    }

    public function rules(): array
    {
        return [
            'problem_ids'            => 'required_without_all:exercise_ids,private_exercise_ids|array|min:1',
            'problem_ids.*'          => 'exists:problems,id',
            'exercise_ids'           => 'required_without_all:problem_ids,private_exercise_ids|array|min:1',
            'exercise_ids.*'         => 'exists:exercises,id',
            'private_exercise_ids'   => 'required_without_all:problem_ids,exercise_ids|array|min:1',
            'private_exercise_ids.*' => 'exists:private_exercises,id',
            'student_ids'            => 'required|array|min:1',
            'student_ids.*'          => 'exists:users,id',
            'group_ids'              => 'nullable|array',
            'group_ids.*'            => 'exists:student_groups,id',
            'custom_title'           => 'nullable|string|max:255',
            'custom_level'           => 'nullable|string|max:255',
            'custom_instructions'    => 'nullable|string',
        ];
    }
}
