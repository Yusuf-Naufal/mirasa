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
        Schema::table('perusahaan', function (Blueprint $table) {
            $table->string('kota')->nullable()->after('kontak');
            $table->string('logo')->nullable()->after('kota');
        });

        Schema::table('barang_keluar', function (Blueprint $table) {
            $table->json('keterangan')->nullable()->after('no_faktur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perusahaan', function (Blueprint $table) {
            $table->dropColumn('kota');
            $table->dropColumn('logo');
        });

        Schema::table('barang_keluar', function (Blueprint $table) {
            $table->dropColumn('keterangan');
        });
    }
};
