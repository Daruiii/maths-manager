<?php

namespace App\Http\Requests\Teacher;

class UpdateBuilderTemplateRequest extends BuilderTemplateRequest
{
    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:255'],
            'student_group_id' => ['nullable', 'integer'],
            'payload'          => ['nullable', 'array'],
            'payload.items'    => ['nullable', 'array'],
        ];
    }
}
