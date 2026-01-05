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
        Schema::create('barang_keluar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_perusahaan');
            $table->unsignedBigInteger('id_produksi');
            $table->unsignedBigInteger('id_costumer')->nullable();
            $table->unsignedBigInteger('id_tujuan')->nullable();
            $table->unsignedBigInteger('id_proses')->nullable();
            $table->unsignedBigInteger('id_detail_inventory');
            $table->date('tanggal_keluar')->nullable();
            $table->string('jenis_keluar')->nullable();
            $table->double('jumlah_keluar')->nullable();
            $table->double('harga')->nullable();
            $table->double('total_harga')->nullable();
            $table->string('no_jalan')->nullable();
            $table->string('no_faktur')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_keluar');
    }
};
