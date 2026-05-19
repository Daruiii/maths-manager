<?php

namespace App\Http\Requests\Teacher;

use App\Models\StudentGroup;
use Illuminate\Foundation\Http\FormRequest;

abstract class BuilderTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        if ($groupId = $this->input('student_group_id')) {
            return StudentGroup::where('id', $groupId)
                ->where('teacher_id', $this->user()->id)
                ->exists();
        }

        return true;
    }
}
