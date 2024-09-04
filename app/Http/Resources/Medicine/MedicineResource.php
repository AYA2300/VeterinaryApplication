<?php

namespace App\Http\Resources\Medicine;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
        [
            'id' => $this->id,
            'name' =>$this->name,
            'image'=>$this->image,
            'status' => $this->status??'available',
            'category'=>$this->category,
             'type_of_medicine' =>$this->type_of_medicine,
             'usage' =>$this->usage,
             'price'=>$this->price,
             'base_price' => $this->Base_price,
             'Composition' => $this->Composition,

        ];
    }
}
