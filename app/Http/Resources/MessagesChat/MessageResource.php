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

          'sender' => $this->messageable_sender_id,
            'time'=> ($this->created_at)->format('Y-m-d H:i:s A'),
        ];
    }
}
