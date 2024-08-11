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
        Schema::create('utusans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_mahasiswa')->nullable();
            $table->integer('total_skor');
            $table->enum('status', ['departmen', 'fakultas', 'universitas'])->nullable();
            $table->date('tanggal_utus_departmen')->nullable();
            $table->date('tanggal_utus_fakultas')->nullable();
            $table->date('tanggal_utus_universitas')->nullable();
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
        Schema::dropIfExists('utusans');
    }
};
