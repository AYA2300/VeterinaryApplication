<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Request;
use App\Http\Resources\Feed\FeedResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Medicine\MedicineResource;

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
            'user_id' => $this->userable->id,
            'userable_name' => $this->userable->name,
            'role' => $this->userable->role,
            'total_price' =>$this->total_price,

            'location' =>$this->location->name??'center' ,
            'order_number' => $this->order_number,
            'status' => $this->status??'pending',
            'time'=> ($this->created_at)->format('Y-m-d H:i:s A'),
          'items' => $this->orderItems->map(function ($item) {
        $item_type = class_basename($item->itemable_type);

        if ($item_type === 'Feed') {
            return [
                'item_id' => $item->itemable->id,
                'quantity' => $item->quantity,
                'item_type' => $item_type,
                'item_name' => $item->itemable->name,
                'item_details' => new FeedResource($item->itemable),
            ];        }

        if ($item_type === 'Medicine') {
            return [
                'item_id' => $item->itemable->id,
                'quantity' => $item->quantity,
                'item_type' => $item_type,
                'item_name' => $item->itemable->name,
                'item_details' => new MedicineResource($item->itemable),
            ];        }

        // return [
        //     'item_id' => $item->itemable->id,
        //     'quantity' => $item->quantity,
        //     'item_type' => $item_type,
        //     'item_name' => $item->itemable->name,
        // ];
    }),
        ];

    }
}
