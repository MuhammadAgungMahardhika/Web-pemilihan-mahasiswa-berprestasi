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
        // Path file CSV yang benar, berdasarkan folder 'database/csv/'
        $filePath = base_path('database/csv/fakultas.csv');

        // Membuka file CSV
        if (($handle = fopen($filePath, 'r')) !== false) {
            // Membaca header (baris pertama) dan mengabaikannya
            $header = fgetcsv($handle, 1000, ';'); // Menyesuaikan dengan delimiter ";"

            // Membaca setiap baris CSV dan memasukkan data
            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                DB::table('fakultas')->insert([
                    'nama_fakultas' => $data[1],
                    'dekan' => $data[2],
                    'contact_number' => $data[3] ?: null,  // Jika kosong, set null
                    'email' => $data[4] ?: null,  // Jika kosong, set null
                    'created_at' => $data[5],
                    'updated_at' => $data[6],
                    'created_by' => $data[7],
                    'updated_by' => $data[8],
                ]);
            }

            // Menutup file setelah selesai membaca
            fclose($handle);
        }
    }
}
