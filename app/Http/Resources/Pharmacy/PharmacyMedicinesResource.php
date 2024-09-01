<?php

namespace App\Http\Resources\Pharmacy;

use App\Models\Medicine;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Medicine\MedicineResource;

class PharmacyMedicinesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $medicines = $this->medicines->map(function ($medicine) {
            return [
                 'medicines'=> new MedicineResource($medicine),
                'price' => $medicine->pivot->price,
            ];
        });


        return [
            'id' =>$this->id,
            'owner'=>$this->owner,
            'name' => $this->name,
            'open_time' => $this->open_time->format("H:i A"),
            'close_time' => $this->close_time->format("H:i A"),
            'address' => $this->address,
            'medicines' =>$medicines
        ];
    }
}
