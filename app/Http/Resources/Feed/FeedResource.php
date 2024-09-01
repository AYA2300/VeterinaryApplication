<?php

namespace App\Http\Resources\Feed;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedResource extends JsonResource
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
            'name'=> $this->name,
            'type'=>$this->type,
            'Detailes'=>$this->Detailes,
            'price'=>$this->price,
            'image'=>$this->image,
            'base_price' => $this->Base_price,



        ];
    }
}
