<?php

namespace App\Http\Requests\Teacher;

class StoreBuilderTemplateRequest extends BuilderTemplateRequest
{
    public function rules(): array
    {
        return [
            'type'             => ['required', 'in:ds,td,dm'],
            'name'             => ['required', 'string', 'max:255'],
            'student_group_id' => ['nullable', 'integer'],
            'payload'          => ['required', 'array'],
            'payload.items'    => ['required', 'array'],
        ];
    }
}
