<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'id_role' => 1,
            'id_mahasiswa' => 1,
            "name" => "mahasiswa",
            "username" => 123456789,
            "password" => Hash::make("12345678"),
        ]);

        DB::table('users')->insert([
            'id_role' => 2,
            'id_departmen' => 1,
            "name" => "admin_departmen",
            "username" => "admin_departmen",
            "password" => Hash::make("12345678"),
        ]);

        DB::table('users')->insert([
            'id_role' => 3,
            'id_fakultas' => 1,
            "name" => "admin_fakultas",
            "username" => "admin_fakultas",
            "password" => Hash::make("12345678"),
        ]);
        DB::table('users')->insert([
            'id_role' => 4,
            "name" => "admin_universitas",
            "username" => "admin_universitas",
            "password" => Hash::make("12345678"),
        ]);
        DB::table('users')->insert([
            'id_role' => 5,
            'id_fakultas' => 1,
            "name" => "juri_fakultas",
            "username" => "juri_fakultas",
            "password" => Hash::make("12345678"),
        ]);

        DB::table('users')->insert([
            'id_role' => 6,
            "name" => "juri_universitas",
            "username" => "juri_universitas",
            "password" => Hash::make("12345678"),
        ]);
    }
}
