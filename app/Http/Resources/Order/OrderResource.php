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
            'user_id' => $this->cart->userable->name,
            'userable_name' => $this->cart->userable->id,
            'role' => $this->cart->userable->role,

            'cart_id' => $this->cart_id,
            'location' =>$this->location->name??'center' ,
             'delivery_price' =>$this->location->delivery_price??'0',
            'order_number' => $this->order_number,
            'status' => $this->status??'pending',
            'time'=> ($this->created_at)->format('Y-m-d H:i:s A')

        ];
    }
}
