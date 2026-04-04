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
        Schema::table('booking', function (Blueprint $table) {
            $table->index('status_booking', 'booking_status_booking_index');
            $table->index('fasilitas_id', 'booking_fasilitas_id_index');
            $table->index('tipe_sewa_id', 'booking_tipe_sewa_id_index');
            $table->index('kegiatan_id', 'booking_kegiatan_id_index');
        });

        Schema::table('fasilitas', function (Blueprint $table) {
            $table->index('kategori_id', 'fasilitas_kategori_id_index');
            $table->index('status_fasilitas', 'fasilitas_status_fasilitas_index');
        });

        Schema::table('kategori', function (Blueprint $table) {
            $table->index('status', 'kategori_status_index');
        });

        Schema::table('harga_sewa', function (Blueprint $table) {
            $table->index(['fasilitas_id', 'tipe_sewa_id'], 'harga_sewa_fasilitas_tipe_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('role', 'users_role_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_role_index');
        });

        Schema::table('harga_sewa', function (Blueprint $table) {
            $table->dropIndex('harga_sewa_fasilitas_tipe_index');
        });

        Schema::table('kategori', function (Blueprint $table) {
            $table->dropIndex('kategori_status_index');
        });

        Schema::table('fasilitas', function (Blueprint $table) {
            $table->dropIndex('fasilitas_kategori_id_index');
            $table->dropIndex('fasilitas_status_fasilitas_index');
        });

        Schema::table('booking', function (Blueprint $table) {
            $table->dropIndex('booking_status_booking_index');
            $table->dropIndex('booking_fasilitas_id_index');
            $table->dropIndex('booking_tipe_sewa_id_index');
            $table->dropIndex('booking_kegiatan_id_index');
        });
    }
};