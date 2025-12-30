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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pembayaran')->unique();
            $table->unsignedBigInteger('booking_id');
            $table->string('metode_pembayaran'); // ini bisa berupa 'tranfer dan qris
            $table->string('bukti_pembayaran');
            $table->decimal('jumlah_pembayaran', 8, 2);
            $table->enum('status_pembayaran', ['pending', 'verified',])->default('pending');
            $table->text('alasan_penolakan');
            $table->timestamp('tanggal_pembayaran')->nullable();
            $table->timestamps();

            $table->foreign('booking_id')->references('id')->on('booking')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
