<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',

            ],
            'profile_photo' => ['nullable', 'image', 'mimes:png,jpg,jpeg'],
            'phone' => ['required', 'string', 'max:20'],
            'workplace_name' => ['required', 'string', 'max:255'],
            'cities' => ['required', 'array'],
            'cities.*' => ['integer', 'exists:cities,id'],
            'profile_photo' => ['nullable', 'image', 'max:2048', 'mimes:png,jpg,jpeg'],
            'is_approved' => ['nullable', 'boolean'],

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
