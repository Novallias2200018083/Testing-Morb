<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id();

            // --- Relasi (Foreign Keys) ---
            // 1. Antrian ini untuk layanan apa saja (Wajib)
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            
            // 2. Dilayani di loket mana nantinya (Bisa null saat baru ambil tiket)
            $table->foreignId('counter_id')->nullable()->constrained('counters')->nullOnDelete();
            
            // 3. Siapa yang akan melayani (Bisa null saat baru ambil tiket)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // --- Data Inti Antrian ---
            $table->integer('queue_number'); // Urutan harian: 1, 2, 3
            $table->string('queue_code', 20); // Kode display: "CS-001"
            
            // --- Status Flow (Support Realtime) ---
            // pending   : Menunggu dipanggil
            // called    : Sedang dipanggil (notifikasi bunyi)
            // serving   : Sedang dilayani di meja
            // completed : Selesai
            // skipped   : Dilewati/Tidak datang
            $table->enum('status', ['pending', 'called', 'serving', 'completed', 'skipped'])
                  ->default('pending')
                  ->index(); // Index agar query filter status cepat

            // --- Timestamps Audit ---
            $table->timestamp('called_at')->nullable();     // Kapan admin tekan tombol panggil
            $table->timestamp('served_at')->nullable();     // Kapan mulai bicara
            $table->timestamp('completed_at')->nullable();  // Kapan selesai

            $table->timestamps(); // created_at = Waktu ambil tiket

            // --- Optimasi Database (Best Practice) ---
            // Index komposit untuk query halaman admin/user agar cepat
            // Contoh query: "Ambilkan antrian Pending untuk layanan Service ID 1 hari ini"
            $table->index(['service_id', 'status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};