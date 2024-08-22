<?php

namespace App\Http\Resources\Breeder;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Auth_BreederResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'phone_number' => $this->phone_number,
            'region' => $this->region,
            'category_id' => $this->category_id,



        ];

    }
}
