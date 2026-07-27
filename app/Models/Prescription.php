<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    const PENDING = 'pending';
    const PROCESSING = 'processing';
    const COMPLETED = 'completed';
    const CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'image',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
