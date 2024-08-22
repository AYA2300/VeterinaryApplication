<?php

namespace App\Http\Requests\Breeder;

use Illuminate\Foundation\Http\FormRequest;

class Register_Breeder_Request extends FormRequest
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
            'name'=>'required|string|min:2',
            'password'=>'required|string|min:6|max:8',
            'confirm_password' => 'min:6|same:password',
            'phone_number'=>'required|string',
            'region'=>'string|max:255',
            'role'=>['in:breeder'],
            'category_id'=>'required'
        ];
    }
}
