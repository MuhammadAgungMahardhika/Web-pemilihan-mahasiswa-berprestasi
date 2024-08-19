<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            'nama' => 'mahasiswa',
        ]);
        DB::table('roles')->insert([
            'nama' => 'admin_departmen',
        ]);
        DB::table('roles')->insert([
            'nama' => 'admin_fakultas',
        ]);
        DB::table('roles')->insert([
            'nama' => 'admin_universitas',
        ]);
        DB::table('roles')->insert([
            'nama' => 'juri_fakultas',
        ]);
        DB::table('roles')->insert([
            'nama' => 'juri_universitas',
        ]);
    }
}
