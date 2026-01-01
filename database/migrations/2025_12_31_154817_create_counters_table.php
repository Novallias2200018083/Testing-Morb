<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('counters', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "Loket 1", "Counter A"
            
            // Status operasional loket
            $table->enum('status', ['open', 'closed', 'break'])->default('closed');
            
            // Siapa staff yang sedang menjaga loket ini? (Bisa null jika tutup)
            // Jika user dihapus, set null (jangan hapus loketnya)
            $table->foreignId('active_user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counters');
    }
};