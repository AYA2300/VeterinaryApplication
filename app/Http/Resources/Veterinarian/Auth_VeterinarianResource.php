<?php

namespace App\Http\Resources\Veterinarian;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Auth_VeterinarianResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' => $this->id,
            'name' => $this->name,
            'email'=>$this->email,
            'certificate_image'=>$this->certificate_image,
            'experience_certificate_image'=>$this->experience_certificate_image,
            'address'=>$this->Address,
            'Specialization'=>$this->Specialization,
            'photo'=>$this->photo,

        ];
    }
}
