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
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',

            ],
            'phone' => ['required', 'string', 'max:20'],
            'workplace_name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'max:2048', 'mimes:png,jpg,jpeg'],
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
