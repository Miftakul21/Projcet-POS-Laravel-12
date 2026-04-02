<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Migration tabel ini untuk mengelola barang inventory (Barang Masuk Dan Keluar)
     * Serta berkaitan dengan Laba Rugi
     * 
     */
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->string('id_supplier')->nullable();
            $table->string('id_barang')->nullable();
            $table->integer('harga_modal')->nullable();
            $table->string('status')->nullable(); // masuk atau keluar
            $table->string('deskripsi')->nullable();
            $table->string('waktu')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
