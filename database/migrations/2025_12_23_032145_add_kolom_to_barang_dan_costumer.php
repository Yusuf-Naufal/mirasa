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
        Schema::table('barang', function (Blueprint $table) {
            $table->string('satuan')->nullable()->after('kode');
        });

        Schema::table('costumer', function (Blueprint $table) {
            $table->unsignedBigInteger('id_perusahaan')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn('satuan');
        });

        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn('id_perusahaan');
        });
    }
};
