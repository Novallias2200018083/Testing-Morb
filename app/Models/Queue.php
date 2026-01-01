<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'counter_id',
        'user_id',
        'queue_number',
        'queue_code',
        'status',          // pending, called, serving, completed, skipped
        'called_at',
        'served_at',
        'completed_at'
    ];

    // Casting : Mengubah string tanggal di database menjadi object Carbon PHP
    // Agar bisa dipanggil $queue->called_at->format('H:i')
    
    protected $casts = [
        'called_at' => 'datetime',
        'served_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // --- RELATIONS ---

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function counter()
    {
        return $this->belongsTo(Counter::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class); // Petugas yang melayani
    }

    /**
     * Filter antrian hanya untuk HARI INI.
     * Penggunaan: Queue::today()->get();
     */

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', Carbon::today());
    }

    /**
     * Filter antrian yang masih MENUNGGU dipanggil.
     * Penggunaan: Queue::pending()->get();
     */

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Filter antrian yang sedang AKTIF (Dipanggil atau Dilayani).
     * Ini digunakan untuk tampilan Layar TV agar tahu mana yang ditampilkan besar.
     */

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['called', 'serving']);
    }

    /**
     * Filter antrian yang sudah SELESAI.
     */

    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['completed', 'skipped']);
    }
}