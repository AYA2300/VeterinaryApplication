<?php

namespace App\Http\Requests\Auth_VeterinarianRequest;

use Illuminate\Foundation\Http\FormRequest;

class Login_VeterinarianRequest extends FormRequest
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
            'email' => 'nullable|email|required_without:phone_number',
            'phone_number' => 'nullable|string|required_without:email',
            'password' => 'required|min:6|max:8',
        ];
    }
}
