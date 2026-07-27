<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'order_number' => $this->order_number,

            'customer' => [
                'name' => $this->name,
                'phone' => $this->phone,
                'governorate' => $this->governorate,
                'address' => $this->address,
                'notes' => $this->notes,
            ],

            'items' => $this->items->map(function ($item) {

                return [
                    'product_id' => $item->product->id,

                    'title_ar' => $item->product->title_ar,
                    'title_en' => $item->product->title_en,

                    'images' => collect($item->product->images)->map(function ($image) {
                        return asset('img/products/' . $image);
                    }),

                    'price' => $item->price,

                    'quantity' => $item->quantity,

                    'total' => $item->total,
                ];
            }),

            'total' => $this->total,

            'status' => $this->status,

            'created_at' => $this->created_at?->format('Y-m-d H:i'),
        ];
    }
}
