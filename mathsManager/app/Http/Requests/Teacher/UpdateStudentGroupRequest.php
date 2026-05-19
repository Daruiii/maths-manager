<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canActAsTeacher() && $this->user()?->status === 'active';
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:80'],
        ];
    }
}
