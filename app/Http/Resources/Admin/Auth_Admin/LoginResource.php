<?php

namespace App\Http\Resources\Admin\Auth_Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
                'admin_id' => (int) $this->id,
                'name' => $this->name,
                'role' => $this->role,
                'created_at' => $this->created_at,
            ];

    }
}
