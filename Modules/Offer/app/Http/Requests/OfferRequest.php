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
            'offer_buy_quantity' => 'required|integer|min:1',
            'offer_free_quantity' => 'required|integer|min:0',
            'offer_start_date' => 'required|date',
            'offer_end_date' => 'required|date|after_or_equal:offer_start_date',
            'notes' => 'nullable|string',
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
