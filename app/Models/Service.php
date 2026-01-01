<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',         // Nama Layanan
        'code',         // Kode Prefix (misal: CS)
        'description',  // Deskripsi
    ];

    // --- RELATIONS ---

    // Satu layanan memiliki banyak antrian
    
    public function queues()
    {
        return $this->hasMany(Queue::class);
    }
    public function counters()
    {
        return $this->belongsToMany(Counter::class, 'counter_service');
    }
}