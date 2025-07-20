<?php

namespace Modules\Medicine\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class medicineRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'unique:medicines,name',
                'string',
                'max:255',
            ],
            'manufacturer' => [
                'nullable',
                'string',
                'max:255',
            ],
            'quantity_available' => [
                'required',
                'integer',
                'min:0',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
                'decimal:0,2',
            ],
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
