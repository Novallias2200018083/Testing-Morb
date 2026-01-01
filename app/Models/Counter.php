<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',            // Nama Loket (Loket 1)
        'status',          // open, closed, break
        'active_user_id',  // Siapa yang jaga sekarang
    ];

    // --- RELATIONS ---

    // Siapa staff yang sedang aktif di loket ini

    public function activeUser()
    {
        return $this->belongsTo(User::class, 'active_user_id');
    }

    // Antrian apa saja yang pernah diproses di loket ini

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'counter_service');
    }

    // --- SCOPES (Penyederhanaan Query) ---

    // Ambil loket yang sedang buka
    
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }


}