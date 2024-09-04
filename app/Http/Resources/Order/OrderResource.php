<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'breeder_id' => $this->cart->breeder_id,
            'breeder_name' => $this->cart->breeder->name,
            'cart_id' => $this->cart_id,
            'location' =>$this->location->name??'null' ,
             'delivery_price' =>$this->location->delivery_price??'0',
            'order_number' => $this->order_number,
            'status' => $this->status??'pending',
            'time'=> ($this->created_at)->format('Y-m-d H:i:s A')

        ];
    }
}
