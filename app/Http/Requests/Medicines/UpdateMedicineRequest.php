<?php

namespace App\Http\Requests\Medicines;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicineRequest extends FormRequest
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
            'image'=>'nullable|file|image|mimes:png,jpg,jpeg,jfif|max:10000|mimetypes:image/jpeg,image/png,image/jpg,image/jfif',
            'expiration_date' =>'nullable|date|after_or_equal:today',
              'status'=>['in:active,expired']

        ];

    }

    public function messages(){
        return[
            'expiration_date.after_or_equal'=>'لا يمكن ان يكون تاريخ الصلاحية قبل اليوم',

        ];
}
    }

