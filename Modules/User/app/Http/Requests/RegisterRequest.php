<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class registerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20|regex:/^\+?[0-9]{7,20}$/|unique:users,phone',
            'workplace_name' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8), // Minimum length of 8 characters
                // ->mixedCase() // Must include both uppercase and lowercase letters
                // ->letters()   // Must include at least one letter
                // ->numbers()   // Must include at least one number
                // ->symbols()   // Must include at least one symbol
                // ->uncompromised(), // Checks against known data breaches
            ],
            'role' => 'required|string|in:صيدلي,مورد',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
