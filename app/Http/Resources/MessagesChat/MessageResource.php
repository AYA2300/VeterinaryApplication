<?php

namespace App\Http\Resources\MessagesChat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Breeder\Auth_BreederResource;
use App\Http\Resources\Veterinarian\Auth_VeterinarianResource;

class MessageResource extends JsonResource
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
            'message'=> $this->message,
            'type' => $this->type,
            'breeder_id' => $this->conversation->breeder_id,
            'breeder_name'=> $this->conversation->breeder->name,
             'veterinary_id' => $this->conversation->veterinary_id,
             'veterinary_name'=>$this->conversation->Veterinarian->name,
             'sender' => $this->sender_message_type,

        ];
    }
}
