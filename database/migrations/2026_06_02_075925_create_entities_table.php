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
        Schema::create('entities', function (Blueprint $table) {
            $table->ulid('id')->primary(); // ID utama menggunakan ULID
            $table->foreignUlid('novel_id')->constrained()->onDelete('cascade');
            $table->string('main_name'); // Contoh: Mok Gyeongun / Pedang Asura
            $table->string('type'); // Isi: 'character', 'item', atau 'place'
            $table->string('gender')->nullable();
            $table->string('image_url'); // URL gambar objek/karakter
            $table->text('description')->nullable();
            $table->string('display_aliases')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entities');
    }
};
