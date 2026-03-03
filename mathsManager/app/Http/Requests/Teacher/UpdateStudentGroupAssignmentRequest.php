<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentGroupAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canActAsTeacher() && $this->user()?->status === 'active';
    }

    public function rules(): array
    {
        return [
            'group_id' => ['nullable', 'integer', 'exists:student_groups,id'],
        ];
    }
}
