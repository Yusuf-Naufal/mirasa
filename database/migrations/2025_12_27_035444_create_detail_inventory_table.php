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
        Schema::create('detail_inventory', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_inventory');
            $table->unsignedBigInteger('id_supplier')->nullable();
            $table->unsignedBigInteger('id_produksi')->nullable();
            $table->string('nomor_batch')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->date('tanggal_exp')->nullable();
            $table->double('stok')->nullable();
            $table->double('jumlah_diterima')->nullable();
            $table->double('jumlah_rusak')->nullable();
            $table->double('harga')->nullable();
            $table->double('total_harga')->nullable();
            $table->double('diskon')->nullable();
            $table->string('kondisi_barang')->nullable();
            $table->string('kondisi_kendaraan')->nullable();
            $table->string('tempat_penyimpanan')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_inventory');
    }
};
