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
        Schema::create('bahan_baku', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_perusahaan');
            $table->unsignedBigInteger('id_supplier');
            $table->unsignedBigInteger('id_barang');
            $table->unsignedBigInteger('id_produksi');
            $table->date('tanggal_masuk')->nullable();
            $table->double('jumlah_diterima')->nullable();
            $table->double('harga')->nullable();
            $table->double('total_harga')->nullable();
            $table->string('kondisi_barang')->nullable();
            $table->string('kondisi_kendaraan')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_baku');
    }
};
