<?php

namespace App\Http\Resources\Cart;

use Illuminate\Http\Request;
use App\Http\Resources\Feed\FeedResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Medicine\MedicineResource;
use App\Http\Resources\Breeder\Auth_BreederResource;

class AddToCartResource extends JsonResource
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
                 'medicine'=> new MedicineResource($medicine),
                'quantity' => $medicine->pivot->quantity,
                'priceTotal' => $medicine->pivot->quantity * $medicine->price
            ];
        });
        $feeds = $this->feeds->map(function ($feed) {
            return [
                 'feed'=> new FeedResource($feed),
                'quantity' => $feed->pivot->quantity,
                'priceTotal' => $feed->pivot->quantity * $feed->price
            ];
        });

        return[
           'id'=> $this->id,
            'user'=>$this->userable->id,
            'role' =>$this->userable->role,
            'name'=>$this->userable->name,
            'medicines' => $medicines,
            'feeds' =>  $feeds,
            'time'=> ($this->created_at)->format('Y-m-d H:i:s A')
        // ]
        ]
        ;
    }
}
