<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class SendTeacherCorrectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'correction_pictures'   => ['required', 'array', 'min:1', 'max:20'],
            'correction_pictures.*' => ['required', 'string', 'max:5000'],
            'correction_message'    => ['nullable', 'string', 'max:1000'],
            'grade'                 => ['nullable', 'numeric', 'min:0', 'max:20'],
        ];
    }
}
