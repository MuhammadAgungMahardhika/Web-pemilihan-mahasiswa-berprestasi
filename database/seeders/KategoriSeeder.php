<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategoris')->insert([
            'jenis' => 'a',
            'nama' => 'kategori 1',

        ]);
        DB::table('kategoris')->insert([
            'jenis' => 'b',
            'nama' => 'kategori 2',

        ]);
    }
}
