<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakultasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('fakultas')->insert([
            'nama_fakultas' => 'Teknologi Informasi',
            'dekan' => 'Harle',
        ]);
        DB::table('fakultas')->insert([
            'nama_fakultas' => 'Teknik',
            'dekan' => 'Adinama',
        ]);
    }
}
