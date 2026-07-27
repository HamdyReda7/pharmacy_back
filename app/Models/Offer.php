<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'image',
        'price_before',
        'price_after',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'price_before' => 'decimal:2',
        'price_after' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'boolean',
    ];
}