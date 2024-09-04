<?php

namespace App\Http\Requests\Diseases;

use Illuminate\Foundation\Http\FormRequest;

class Add_DiseasesRequest extends FormRequest
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
            'name'=>'required|string',
            'treatment'=>'required|string',
            'causes'=>'required|string',
            'symptoms'=>'nullable|string',
            'image'=>'nullable|file|image|mimes:png,jpg,jpeg,jfif|max:10000|mimetypes:image/jpeg,image/png,image/jpg,image/jfif',
             'medicine_id'=>'nullable',
             'prevention_methods'=>'required|string',


        ];
    }
}
