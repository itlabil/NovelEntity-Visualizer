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
        Schema::create('entity_aliases', function (Blueprint $table) {
            $table->ulid('id')->primary(); // Tetap gunakan ULID agar seragam
            
            // Menghubungkan ke tabel entities yang menggunakan ULID
            $table->foreignUlid('entity_id')->constrained()->onDelete('cascade');

            $table->string('alias_name'); // Tempat menyimpan nama alias/julukan
            $table->timestamps();

            // Membuat index khusus untuk mempercepat pencarian teks di PostgreSQL
            $table->index('alias_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_aliases');
    }
};
