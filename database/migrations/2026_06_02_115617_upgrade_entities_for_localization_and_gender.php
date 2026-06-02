<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Membuat tabel transkripsi bahasa baru
        Schema::create('entity_translations', function (Blueprint $table) {
            // 1. Primary Key menggunakan ULID
            $table->ulid('id')->primary(); 
            
            // 2. Foreign Key berupa string/char karena menyambung ke ULID tabel entities
            $table->string('entity_id'); 
            
            $table->string('locale', 5); // 'id', 'en', 'ko', dll.
            $table->text('description'); // Deskripsi sesuai bahasa
            $table->timestamps();

            // Deklarasi relasi foreign key ke tabel entities
            $table->foreign('entity_id')->references('id')->on('entities')->onDelete('cascade');

            // Proteksi unik agar satu entitas tidak punya ganda deskripsi di bahasa yang sama
            $table->unique(['entity_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entity_translations');
    }
};
