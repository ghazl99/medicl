<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Modules\Medicine\Models\Medicine;

class orderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'supplier_id' => ['required', 'exists:users,id'],
            'medicines' => ['required', 'array', 'min:1'],
            'medicines.*' => ['required', 'exists:medicines,id'],
            'quantities' => ['required', 'array', 'min:1'],
            'quantities.*' => ['required', 'integer', 'min:1'],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $medicines = $this->input('medicines', []);
            $quantities = $this->input('quantities', []);

            foreach ($medicines as $index => $medicineId) {
                $quantity = $quantities[$index] ?? 0;
                $medicine = Medicine::find($medicineId);
                if (! $medicine) {
                    continue; // الدواء غير موجود ، موجود تحقق في rules
                }

                // افترض أن الكمية المتوفرة في الحقل quantity_available
                if ($quantity > $medicine->quantity_available) {
                    $validator->errors()->add(
                        'quantities.'.$index,
                        "الكمية المطلوبة ($quantity) للدواء '{$medicine->name}' تتجاوز الكمية المتوفرة ({$medicine->quantity_available})"
                    );
                }
            }
        });
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
