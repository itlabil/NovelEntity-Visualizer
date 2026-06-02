<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('entities', function (Blueprint $table) {
            // Kolom status untuk moderasi data
            $table->string('status')->default('pending'); // nilai: pending, approved, rejected
            
            // Mengetahui siapa kontributor yang menginput data ini (menggunakan ULID jika user menggunakan ULID, atau foreignId standar)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('entities', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['status', 'user_id']);
        });
    }
};
