<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class App_AddToCartRequest extends FormRequest
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
            'user_id' => 'exists:breeders,id|exists:veterinarians,id',
            'cart_id' => 'exists:carts,id',
            'quantity' => 'nullable|integer|min:1',
            'type' => 'required|string|in:medicine,feed',
            'medicine_id'=>'exists:medicines,id',
            'feed_id' => 'exists:feeds,id'
        ];
    }
}
