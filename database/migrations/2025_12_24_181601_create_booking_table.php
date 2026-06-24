<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    
        public function up(): void
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('fasilitas_id')->constrained('fasilitas')->onDelete('cascade');
            
            // Perubahan: Menggunakan datetime agar presisi per jam lintas hari
            $table->dateTime('waktu_mulai'); 
            $table->dateTime('waktu_selesai');
            
            // Tambahan kolom baru
            $table->text('kegiatan'); // Isi kegiatan
            $table->string('dokumen_pdf')->nullable(); // Path file PDF (nullable jika opsional, hapus jika wajib)
            
            $table->enum('status_booking', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->text('alasan_pembatalan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
