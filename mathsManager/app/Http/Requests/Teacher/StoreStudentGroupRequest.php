<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isTeacher() && $this->user()?->status === 'active';
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:80'],
        ];
    }
}
