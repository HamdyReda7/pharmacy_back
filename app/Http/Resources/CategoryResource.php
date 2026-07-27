<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name_ar,
            "name_en" => $this->name_en,
            'image' => $this->image
                ? asset('img/categories/' . $this->image)
                : null,
            'status' => $this->status,
            'products_count' => $this->products_count,

            'created_at' => optional($this->created_at)->format('Y-m-d'),
        ];
    }
}
