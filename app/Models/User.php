<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const USER = 'user';
    const ADMIN = 'admin';
    const MANAGER = 'manager';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'image',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'boolean',
    ];

    public function isAdmin(): bool
    {
        return $this->role === self::ADMIN;
    }

    public function isManager(): bool
    {
        return $this->role === self::MANAGER;
    }

    public function isUser(): bool
    {
        return $this->role === self::USER;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}
