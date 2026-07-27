<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class Category extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getTranslatedNameAttribute()
    {
        return LaravelLocalization::getCurrentLocale() === 'ar'
            ? $this->name_ar
            : $this->name_en;
    }
}
