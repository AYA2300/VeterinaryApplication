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

        return [
           'medicine'=>new MedicineResource($this->medicine),
           'price' =>$this->price
        ];
    }
}
