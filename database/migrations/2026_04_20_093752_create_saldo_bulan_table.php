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
        Schema::create('saldo_bulan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_inventory');
            $table->integer('periode_bulan');
            $table->integer('periode_tahun');
            $table->double('stok_awal', 15, 2);
            $table->double('nilai_awal', 15, 2);

            $table->unique(['id_inventory', 'periode_bulan', 'periode_tahun']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saldo_bulan');
    }
};
