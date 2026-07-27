<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'user' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'phone' => $this->user?->phone,
                'email' => $this->user?->email,
                'image' => $this->user?->image
                    ? asset('img/users/' . $this->user->image)
                    : null,
            ],

            'prescription_image' => $this->image
                ? asset('img/prescriptions/' . $this->image)
                : null,

            'status' => $this->status,

            'created_at' => $this->created_at?->format('Y-m-d H:i'),
        ];
    }
}
