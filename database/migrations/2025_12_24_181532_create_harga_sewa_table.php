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
        Schema::create('harga_sewa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fasilitas_id');
            $table->unsignedBigInteger('tipe_sewa_id');
            $table->decimal('harga', 8, 2);
            $table->timestamps();

            $table->foreign('fasilitas_id')->references('id')->on('fasilitas')->onDelete('cascade');
            $table->foreign('tipe_sewa_id')->references('id')->on('tipe_sewa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_sewa');
    }
};
