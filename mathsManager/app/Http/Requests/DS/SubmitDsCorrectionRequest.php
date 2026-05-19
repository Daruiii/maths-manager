<?php

namespace App\Http\Requests\DS;

use Illuminate\Foundation\Http\FormRequest;

class SubmitDsCorrectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'upload_session_token' => ['required', 'string', 'size:48'],
            'message'              => ['nullable', 'string', 'max:500'],
        ];
    }
}
