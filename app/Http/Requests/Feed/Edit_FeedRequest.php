<?php

namespace App\Http\Requests\Feed;

use Illuminate\Foundation\Http\FormRequest;

class Edit_FeedRequest extends FormRequest
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
            'name'=>'nullable|string',
            'type'=>'nullable|string',
            'Detailes'=>'nullable|string|min:4',
            'price'=>'nullable|string',
            'image'=>'nullable|file|image|mimes:png,jpg',
        ];
    }
}
