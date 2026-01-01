<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',      // admin / staff
        'is_active', // status aktif staff
    ];
   

    protected $hidden = [
        'password',
        'remember_token',
    ];

    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // --- RELATIONS ---

    // Relasi ke Loket yang sedang dia jaga saat ini

    public function activeCounter()
    {
        return $this->hasOne(Counter::class, 'active_user_id');
    }

    // Relasi ke History antrian yang pernah dia layani

    public function servedQueues()
    {
        return $this->hasMany(Queue::class, 'user_id');
    }
    
    // Cek apakah user adalah admin

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
}