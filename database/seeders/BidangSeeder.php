<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bidangs')->insert([
            'nama' => 'bidang 1',
        ]);
        DB::table('bidangs')->insert([
            'nama' => 'bidang 2',
        ]);
    }
}
