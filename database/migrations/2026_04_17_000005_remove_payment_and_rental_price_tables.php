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
        if (Schema::hasTable('pembayaran')) {
            Schema::drop('pembayaran');
        }

        if (Schema::hasTable('harga_sewa')) {
            Schema::drop('harga_sewa');
        }

        if (Schema::hasTable('booking') && Schema::hasColumn('booking', 'deadline_pembayaran')) {
            Schema::table('booking', function (Blueprint $table) {
                $table->dropColumn('deadline_pembayaran');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('harga_sewa')) {
            Schema::create('harga_sewa', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('fasilitas_id');
                $table->unsignedBigInteger('tipe_sewa_id');
                $table->decimal('harga', 12, 2);
                $table->timestamps();

                $table->foreign('fasilitas_id')->references('id')->on('fasilitas')->onDelete('cascade');
                $table->foreign('tipe_sewa_id')->references('id')->on('tipe_sewa')->onDelete('cascade');
                $table->index(['fasilitas_id', 'tipe_sewa_id'], 'harga_sewa_fasilitas_tipe_index');
            });
        }

        if (! Schema::hasTable('pembayaran')) {
            Schema::create('pembayaran', function (Blueprint $table) {
                $table->id();
                $table->string('kode_pembayaran')->unique();
                $table->unsignedBigInteger('booking_id');
                $table->string('metode_pembayaran');
                $table->string('bukti_pembayaran');
                $table->decimal('jumlah_pembayaran', 12, 2);
                $table->enum('status_pembayaran', ['pending', 'verified'])->default('pending');
                $table->text('alasan_penolakan');
                $table->timestamp('tanggal_pembayaran')->nullable();
                $table->timestamps();

                $table->foreign('booking_id')->references('id')->on('booking')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('booking') && ! Schema::hasColumn('booking', 'deadline_pembayaran')) {
            Schema::table('booking', function (Blueprint $table) {
                $table->timestamp('deadline_pembayaran')->nullable()->after('status_booking');
            });
        }
    }
};
