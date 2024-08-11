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
        Schema::create('dokumen_prestasis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_capaian_unggulan')->nullable();
            $table->unsignedBigInteger('id_mahasiswa')->nullable();
            $table->string('judul')->nullable();
            $table->string('dokumen_url')->nullable();
            $table->date('uploaded_at');
            $table->enum('status', ['pending', 'ditolak', 'diterima'])->nullable();
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
        Schema::dropIfExists('dokumen_prestasis');
    }
};
