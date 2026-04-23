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
            'pictures'   => ['required', 'array', 'min:1', 'max:10'],
            'pictures.*' => ['required', 'string', 'max:5000'],
            'message'    => ['nullable', 'string', 'max:500'],
        ];
    }
}
