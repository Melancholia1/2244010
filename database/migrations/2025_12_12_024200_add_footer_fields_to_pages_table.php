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
        Schema::table('pages', function (Blueprint $table) {
            $table->string('section', 50)->nullable()->after('slug')->comment('Section untuk footer: company, services, support');
            $table->integer('sort_order')->default(0)->after('section')->comment('Urutan tampil di footer');
            $table->string('link_url', 255)->nullable()->after('sort_order')->comment('URL eksternal (opsional, jika kosong akan menggunakan slug)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['section', 'sort_order', 'link_url']);
        });
    }
};
