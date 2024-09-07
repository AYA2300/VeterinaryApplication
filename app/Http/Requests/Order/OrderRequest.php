<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'feeds.*.quantity' => 'nullable|integer|min:1',
            'medicines.*.quantity' => 'nullable|integer|min:1',

            'delivery_type' => 'required|string|in:delivery,non_delivery',
            'location_id' => 'required_if:delivery_type,delivery|exists:locations,id',
            'total_price' => 'required|min:0|string',
            'userable_id' => 'exists:breeders,id|exists:veterinarians,id',
            'order_number' => 'string|unique:orders,order_number',
            'medicines' => 'nullable|array',
             'medicines.*.id' => 'required_with:medicines|exists:medicines,id',
              'feeds' => 'nullable|array',
             'feeds.*.id' => 'required_with:feeds|exists:feeds,id',
        ];
    }
}
