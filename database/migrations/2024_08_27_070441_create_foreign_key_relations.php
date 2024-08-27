<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Relasi User.id_mahasiswa -> Mahasiswa.id
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('id_mahasiswa')->references('id')->on('mahasiswas')->onUpdate('cascade')->onDelete('cascade');
        });

        // Relasi Mahasiswa.id < Utusan.id_mahasiswa
        Schema::table('utusans', function (Blueprint $table) {

            $table->foreign('id_mahasiswa')->references('id')->on('mahasiswas')->onUpdate('cascade')->onDelete('cascade');
        });

        // Relasi Mahasiswa.id -> Bahasa_inggris.id_mahasiswa
        Schema::table('bahasa_inggris', function (Blueprint $table) {

            $table->foreign('id_mahasiswa')->references('id')->on('mahasiswas')->onUpdate('cascade')->onDelete('cascade');
        });

        // Relasi Mahasiswa.id -> Karya_Ilmiahs.id_mahasiswa
        Schema::table('karya_ilmiahs', function (Blueprint $table) {

            $table->foreign('id_mahasiswa')->references('id')->on('mahasiswas')->onUpdate('cascade')->onDelete('cascade');
        });

        // Relasi Mahasiswa.id < Dokumen_Prestasis.id_mahasiswa
        Schema::table('dokumen_prestasis', function (Blueprint $table) {

            $table->foreign('id_mahasiswa')->references('id')->on('mahasiswas')->onUpdate('cascade')->onDelete('cascade');
        });

        // Relasi Mahasiswa.id_departmen -> Departmen.id
        Schema::table('mahasiswas', function (Blueprint $table) {

            $table->foreign('id_departmen')->references('id')->on('departmens')->onUpdate('cascade')->onDelete('cascade');
        });

        // Relasi Departmen.id_fakultas -> Fakultas.id
        Schema::table('departmens', function (Blueprint $table) {

            $table->foreign('id_fakultas')->references('id')->on('fakultas')->onUpdate('cascade')->onDelete('cascade');
        });

        // Relasi Capaian_Unggulan.id < Dokumen_Prestasi.id_capaian_unggulan
        Schema::table('dokumen_prestasis', function (Blueprint $table) {
            $table->foreign('id_capaian_unggulan')->references('id')->on('capaian_unggulans')->onUpdate('cascade')->onDelete('cascade');
        });

        // Relasi Bidang.id < Capaian_Unggulan.id_bidang
        Schema::table('capaian_unggulans', function (Blueprint $table) {

            $table->foreign('id_bidang')->references('id')->on('bidangs')->onUpdate('cascade')->onDelete('cascade');
        });

        // Relasi Kategori.id < Capaian_Unggulan.id_kategori
        Schema::table('capaian_unggulans', function (Blueprint $table) {
            $table->foreign('id_kategori')->references('id')->on('kategoris')->onUpdate('cascade')->onDelete('cascade');
        });

        // Relasi Role.id < User.id_role
        Schema::table('users', function (Blueprint $table) {

            $table->foreign('id_role')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');
        });

        // Relasi Karya_Ilmiah.id -> penilaian_Karya_Ilmiah.id_karya_ilmiah
        Schema::table('penilaian_karya_ilmiahs', function (Blueprint $table) {

            $table->foreign('id_karya_ilmiah')->references('id')->on('karya_ilmiahs')->onUpdate('cascade')->onDelete('cascade');
        });

        // Relasi User.id -> Penilian_Karya_Ilmiah.id_user
        Schema::table('penilaian_karya_ilmiahs', function (Blueprint $table) {

            $table->foreign('id_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop Foreign Key and Columns

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_mahasiswa']);
            $table->dropForeign(['id_role']);
        });

        Schema::table('utusans', function (Blueprint $table) {
            $table->dropForeign(['id_mahasiswa']);
        });

        Schema::table('bahasa_inggris', function (Blueprint $table) {
            $table->dropForeign(['id_mahasiswa']);
        });

        Schema::table('karya_ilmiahs', function (Blueprint $table) {
            $table->dropForeign(['id_mahasiswa']);
        });

        Schema::table('dokumen_prestasis', function (Blueprint $table) {
            $table->dropForeign(['id_mahasiswa', 'id_capaian_unggulan']);
        });

        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropForeign(['id_departmen']);
        });

        Schema::table('departmens', function (Blueprint $table) {
            $table->dropForeign(['id_fakultas']);
        });

        Schema::table('capaian_unggulans', function (Blueprint $table) {
            $table->dropForeign(['id_bidang', 'id_kategori']);
        });

        Schema::table('penilaian_karya_ilmiahs', function (Blueprint $table) {
            $table->dropForeign(['id_karya_ilmiah', 'id_user']);
        });
    }
};
