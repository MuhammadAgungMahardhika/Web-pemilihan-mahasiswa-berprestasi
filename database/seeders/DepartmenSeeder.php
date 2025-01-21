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
        // Path file CSV yang benar, berdasarkan folder 'database/csv/'
        $filePath = base_path('database/csv/departmen.csv');

        // Membuka file CSV
        if (($handle = fopen($filePath, 'r')) !== false) {
            // Membaca header (baris pertama) dan mengabaikannya
            $header = fgetcsv($handle, 1000, ';'); // Menyesuaikan dengan delimiter ";"

            // Membaca setiap baris CSV dan memasukkan data
            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                DB::table('departmens')->insert([
                    'id_fakultas' => $data[1], // Mengambil id_fakultas dari CSV (data[1] karena data pertama adalah 'id')
                    'nama_departmen' => $data[2],
                    'kepala_departmen' => $data[3],
                    'contact_number' => $data[4],
                    'email' => $data[5],
                    'created_at' => $data[6],
                    'updated_at' => $data[7],
                    'created_by' => $data[8],
                    'updated_by' => $data[9],
                ]);
            }

            // Menutup file setelah selesai membaca
            fclose($handle);
        }
    }
}
