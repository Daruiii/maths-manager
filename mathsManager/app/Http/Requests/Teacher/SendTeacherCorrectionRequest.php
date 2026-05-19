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
            'upload_session_token' => ['nullable', 'string', 'size:48'],
            'correction_message'   => ['nullable', 'string', 'max:1000'],
            'grade'                => ['nullable', 'numeric', 'min:0', 'max:20'],
        ];
    }
}
