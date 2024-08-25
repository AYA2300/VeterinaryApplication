<?php

namespace App\Http\Resources\Diseases;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiseasesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'treatment'=>$this->treatment,
            'causes'=>$this->causes,
            'symptoms'=>$this->symptoms,
            'image'=>$this->image





        ];
    }
}
