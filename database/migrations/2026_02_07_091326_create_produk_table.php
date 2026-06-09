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
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->string('kategori')->nullable();
            $table->string('rasa')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('slug')->unique();
            $table->string('foto')->nullable();
            $table->boolean('is_aktif')->default(false);
            $table->boolean('is_unggulan')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
