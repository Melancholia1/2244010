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
        Schema::create('social_media', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama medsos (Facebook, Instagram, etc)
            $table->string('link'); // URL medsos
            $table->string('icon')->nullable(); // Icon Bootstrap Icons (bi-facebook, bi-instagram, etc)
            $table->integer('order_index')->default(0); // Urutan tampil
            $table->boolean('is_active')->default(true); // Status aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media');
    }
};
