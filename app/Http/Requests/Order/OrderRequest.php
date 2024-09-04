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
            'cart_id' => 'exists:carts,id',
            'order_number' => 'string|unique:orders,order_number',
            'location_id' => 'nullable|exists:locations,id',
            'delivery_type' => 'in:delivery,non_delivery',
            
        ];
    }
}
