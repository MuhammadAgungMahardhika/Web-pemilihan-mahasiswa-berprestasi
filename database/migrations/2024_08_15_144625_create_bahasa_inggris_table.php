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
        Schema::create('bahasa_inggris', function (Blueprint $table) {
            $table->id();
            $table->year('periode');
            $table->unsignedBigInteger('id_mahasiswa')->unique();
            $table->float('listening');
            $table->float('speaking');
            $table->float('writing');
            $table->float('listening_universitas')->nullable();
            $table->float('speaking_universitas')->nullable();
            $table->float('writing_universitas')->nullable();
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
        Schema::dropIfExists('bahasa_inggris');
    }
};
