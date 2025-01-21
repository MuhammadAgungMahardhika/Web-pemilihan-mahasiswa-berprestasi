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
        // Path file CSV yang benar, berdasarkan folder 'database/csv/'
        $filePath = base_path('database/csv/kategori.csv');

        // Membuka file CSV
        if (($handle = fopen($filePath, 'r')) !== false) {
            // Membaca header (baris pertama) dan mengabaikannya
            $header = fgetcsv($handle, 1000, ';'); // Menyesuaikan dengan delimiter ";"

            // Membaca setiap baris CSV dan memasukkan data
            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                DB::table('kategoris')->insert([
                    'jenis' => $data[1], // Mengambil jenis kategori dari CSV (data[1] karena data pertama adalah 'id')
                    'nama' => $data[2],
                    'created_at' => $data[3],
                    'updated_at' => $data[4],
                    'created_by' => $data[5],
                    'updated_by' => $data[6],
                ]);
            }

            // Menutup file setelah selesai membaca
            fclose($handle);
        }
    }
}
