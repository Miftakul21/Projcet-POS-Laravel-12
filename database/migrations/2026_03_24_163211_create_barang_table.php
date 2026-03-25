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
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // nama barang
            $table->string('kategori');
            $table->string('satuan', 30)->nullable();
            $table->string('brand_barang')->nullable(); // untuk brand barang
            $table->integer('stok')->nullable();
            $table->integer('harga_eceran')->nullable();
            $table->integer('harga_reseller')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};