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
        Schema::create('transaksi_items', function (Blueprint $table) {
            $table->id();
            // === DETAIL ITEM TRANSAKSI ===
            $table->foreignId('transaksi_id')->constrained('transaksis')->onDelete('cascade');
            $table->foreignId('master_data_id')->constrained('master_data')->onDelete('cascade'); // FK ke barang/produk
            $table->string('nama_item');           // Snapshot nama saat transaksi
            $table->integer('jumlah')->default(1);
            $table->decimal('harga', 15, 2);       // Harga satuan
            $table->decimal('subtotal', 15, 2);    // jumlah * harga
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_items');
    }
};
