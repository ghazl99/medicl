<?php

namespace Modules\Medicine\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MedicineRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png',
            'type' => 'nullable|string|max:255',
            'type_ar'=> 'nullable|string|max:255',
            'composition' => 'nullable|string|max:255',
            'form' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:500',
            'net_dollar_old' => 'nullable|numeric|min:0',
            'public_dollar_old' => 'nullable|numeric|min:0',
            'net_dollar_new' => 'nullable|numeric|min:0',
            'public_dollar_new' => 'nullable|numeric|min:0',
            'net_syp' => 'nullable|numeric|min:0',
            'public_syp' => 'nullable|numeric|min:0',
            'price_change_percentage' => 'nullable|numeric',
            'description' => 'nullable|string',
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
