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
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('fasilitas_id');
            $table->unsignedBigInteger('tipe_sewa_id');
            $table->date('tanggal_sewa');
            $table->date('tanggal_selesai');
            $table->integer('durasi_hari');
            $table->decimal('total_harga', 10, 2);
            $table->enum('status_booking', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->timestamp('deadline_pembayaran')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('fasilitas_id')->references('id')->on('fasilitas')->onDelete('cascade');
            $table->foreign('tipe_sewa_id')->references('id')->on('tipe_sewa')->onDelete('cascade');
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
