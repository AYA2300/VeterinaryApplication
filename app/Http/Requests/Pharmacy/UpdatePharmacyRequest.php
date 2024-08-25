<?php

namespace App\Http\Requests\Pharmacy;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePharmacyRequest extends FormRequest
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
            'name'=>'nullable|string',
            'owner' =>'nullable|string',
            'open_time'=>'nullable|date_format:H:i',
            'close_time'=>'nullable|date_format:H:i||after:open_time',
            'address'=>'nullable|string',
            'medicine_id' => 'nullable|array|exists:medicines,id',
            'price' => 'nullable',
        ];
    }
}
