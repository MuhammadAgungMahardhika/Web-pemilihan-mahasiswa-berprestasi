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
        Schema::create('penilaian_karya_ilmiahs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_karya_ilmiah');
            $table->unsignedBigInteger('id_user');
            $table->float('skor_fakultas')->nullable();
            $table->float('skor_universitas')->nullable();
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
        Schema::dropIfExists('penilaian_karya_ilmiahs');
    }
};
