<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mahasiswas')->insert([
            'id_departmen' => 1,
            'nim' => 123456789,
            'nama' => 'Mahasiswa Nama',
            'jenis_kelamin' => 'laki-laki',
        ]);
    }
}
