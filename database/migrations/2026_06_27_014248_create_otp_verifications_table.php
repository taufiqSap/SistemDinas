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
        Schema::create('otp_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('no_hp');
            $table->string('kode', 6);
            $table->tinyInteger('attempt_count')->default(0); 
            $table->timestamp('expired_at');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index('no_hp');
            $table->index('expired_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_verifications');
    }
};
