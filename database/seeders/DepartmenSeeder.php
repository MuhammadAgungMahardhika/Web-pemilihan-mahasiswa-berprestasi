<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departmens')->insert([
            'id_fakultas' => 1,
            'nama_departmen' => 'Sistem Informasi',
            'kepala_departmen' => 'Harle',
        ]);
        DB::table('departmens')->insert([
            'id_fakultas' => 1,
            'nama_departmen' => 'Teknik Komputer',
            'kepala_departmen' => 'Adinama',
        ]);
    }
}
