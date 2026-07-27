<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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

            'category' => [
                'id' => $this->category->id,
                'name_ar' => $this->category->name_ar,
                'name_en' => $this->category->name_en,
            ],

            'title_ar' => $this->title_ar,
            'title_en' => $this->title_en,

            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,

            'images' => collect($this->images)->map(function ($image) {
                return asset('img/products/' . $image);
            }),

            'price_before' => $this->price_before,
            'price_after' => $this->price_after,

            'quantity' => $this->quantity,

            'status' => $this->status,

            'created_at' => optional($this->created_at)->format('Y-m-d'),
        ];
    }
}
