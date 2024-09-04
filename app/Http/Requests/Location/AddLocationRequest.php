<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class AddLocationRequest extends FormRequest
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
            'name' => 'required|string',
            ' delivery_time ' => 'nullable',
            'delivery_price' => 'required|integer'
        ];
    }
}
