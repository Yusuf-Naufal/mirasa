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
        Schema::table('detail_inventory', function (Blueprint $table) {
            $table->double('jumlah_return')->nullable()->after('jumlah_rusak');
            $table->string('status_return')->nullable()->after('jumlah_return');
            $table->dropColumn('kondisi_barang');
            $table->dropColumn('kondisi_kendaraan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_inventory', function (Blueprint $table) {
            $table->dropColumn('jumlah_return');
            $table->dropColumn('status_return');
            $table->string('kondisi_barang')->nullable();
            $table->string('kondisi_kendaraan')->nullable();
        });
    }
};
