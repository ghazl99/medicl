<?php

namespace Modules\Offer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'medicine_user_id' => 'required|exists:medicine_user,id',
            'title' => 'required|string|max:255',
            'details' => 'required|string',
            'offer_start_date' => 'required|date',
            'offer_end_date' => 'required|date|after_or_equal:offer_start_date',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif',

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
