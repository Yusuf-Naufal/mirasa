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
        Schema::create('pemakaian_gas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_perusahaan');
            $table->unsignedBigInteger('id_supplier')->nullable();
            $table->unsignedBigInteger('id_pengeluaran')->nullable();
            $table->date('tanggal_pemakaian')->nullable();
            $table->double('jumlah_gas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemakaian_gas');
    }
};
