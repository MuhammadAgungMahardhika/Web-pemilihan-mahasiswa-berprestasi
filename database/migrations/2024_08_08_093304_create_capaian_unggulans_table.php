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
        Schema::create('capaian_unggulans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_bidang')->nullable();
            $table->unsignedBigInteger('id_kategori')->nullable();
            $table->string('kode')->nullable();
            $table->string('nama')->nullable();
            $table->float('skor')->nullable();
            $table->timestamps(); // for created_at and updated_at columns
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capaian_unggulans');
    }
};
