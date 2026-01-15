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
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_perusahaan');
            $table->date('tanggal_pengeluaran');
            $table->string('nama_pengeluaran')->nullable();
            $table->string('kategori')->nullable();
            $table->string('sub_kategori')->nullable();
            $table->boolean('is_hpp')->default(false);
            $table->double('jumlah_pengeluaran')->nullable();
            $table->string('bukti')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
    }
};
