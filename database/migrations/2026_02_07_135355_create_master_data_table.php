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
        Schema::create('master_data', function (Blueprint $table) {
            $table->id();
            // === KOLOM TEMPLATE (Ganti sesuai kebutuhan) ===
            $table->string('kode')->unique();           // Kode unik
            $table->string('nama');                      // Nama item
            $table->text('deskripsi')->nullable();       // Deskripsi opsional
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_data');
    }
};
