<?php

namespace App\Http\Requests\Auth_VeterinarianRequest;

use Illuminate\Foundation\Http\FormRequest;

class Register_VeterinarRequest extends FormRequest
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
            'name'=>'required|string|min:2',
            'password'=>'required|string|min:6|max:8',
            'confirm_password' => 'min:6|same:password',
            'email' =>'required|unique:veterinarians,email',
            'certificate_image'=>'required|file|image|mimes:png,jpg,jpeg,jfif|max:10000|mimetypes:image/jpeg,image/png,image/jpg,image/jfif',
            'experience_certificate_image.*' =>"file|image|mimes:png,jpg,jpeg,jfif|max:10000|mimetypes:image/jpeg,image/png,image/jpg,image/jfif",
             'experience_certificate_image'=>'array',
             'role' => ['in:veterinarian'],

        ];
    }
}
