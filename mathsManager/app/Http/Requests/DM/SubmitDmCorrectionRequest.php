<?php

namespace App\Http\Requests\DM;

use Illuminate\Foundation\Http\FormRequest;

class SubmitDmCorrectionRequest extends FormRequest
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
