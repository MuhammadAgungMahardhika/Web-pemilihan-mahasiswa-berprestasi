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
        Schema::create('karya_ilmiahs', function (Blueprint $table) {
            $table->id();
            $table->year('periode');
            $table->unsignedBigInteger('id_mahasiswa')->unique();
            $table->string('judul');
            $table->string('dokumen_url');
            $table->enum('status', ['pending', 'ditolak', 'diterima'])->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karya_ilmiahs');
    }
};
