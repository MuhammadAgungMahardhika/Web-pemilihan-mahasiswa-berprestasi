<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CapaianUnggulanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('capaian_unggulans')->insert([
            'id_bidang' => 1,
            'id_kategori' => 1,
            'kode' => 'k001',
            'nama' => 'kategori 2',
            'skor' => 20
        ]);
    }
}
