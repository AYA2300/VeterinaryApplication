<?php

namespace App\Http\Requests\group;

use Illuminate\Foundation\Http\FormRequest;

class MesaageRequest extends FormRequest
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
       'message' => 'required|string',
        'breeder_id' => 'exists:breeders,id',
        'type' => 'required|in:text,audio,image',
        'audio' => 'required_if:type,audio|mimes:mp3,wav,ogg|max:20000', //
        'image' => 'required_if:type,image|file|image|mimes:png,jpg,jpeg,jfif|max:10000|mimetypes:image/jpeg,image/png,image/jpg,image/jfif',



        ];
    }
}
