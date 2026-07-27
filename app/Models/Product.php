<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'images',
        'price_before',
        'price_after',
        'quantity',
        'status',
    ];

    protected $casts = [
        'images' => 'array',
        'price_before' => 'decimal:2',
        'price_after' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
