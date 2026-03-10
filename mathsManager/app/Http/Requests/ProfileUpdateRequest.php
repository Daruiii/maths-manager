<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        
        // Règles de base (étudiants, admins, profs non validés)
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ];

        // Un professeur validé ne peut PLUS changer son identité de base
        // On exclut simplement ces champs de la validation (ils seront ignorés dans le controller)
        if ($user->role === 'teacher' && $user->status === 'active') {
            unset($rules['first_name'], $rules['last_name'], $rules['email']);
        }

        // Si l'utilisateur peut agir comme prof (teacher ou admin avec canActAsTeacher),
        // on autorise la modification de ses infos complémentaires
        if ($user->canActAsTeacher()) {
            $rules = array_merge($rules, [
                'bio'            => ['nullable', 'string', 'max:1000'],
                'location'       => ['nullable', 'string', 'max:255'],
                'teaching_level' => ['nullable', 'in:college,lycee,prepa,superieur,autre'],
                'diploma'        => ['nullable', 'in:licence,master,agregation,capes,doctorat,autre'],
                'phone'          => ['nullable', 'string', 'max:20'],
            ]);
        }

        return $rules;
    }
}
