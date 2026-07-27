<?php

namespace App\Http\Resources\Dashboard;

use App\Http\Resources\OrderResource;
use App\Http\Resources\PrescriptionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailsResource extends JsonResource
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

            'name' => $this->name,

            'email' => $this->email,

            'phone' => $this->phone,

            'image' => $this->image
                ? asset('img/users/' . $this->image)
                : null,

            'role' => $this->role,

            'status' => $this->status,

            'orders' => OrderResource::collection(
                $this->whenLoaded('orders')
            ),

            'prescriptions' => PrescriptionResource::collection(
                $this->whenLoaded('prescriptions')
            ),

            'created_at' => optional($this->created_at)->format('Y-m-d H:i'),
        ];
    }
}
