<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CapaianUnggulanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path file CSV yang benar, berdasarkan folder 'database/csv/'
        $filePath = base_path('database/csv/capaian_unggulan.csv');

        // Membuka file CSV
        if (($handle = fopen($filePath, 'r')) !== false) {
            // Membaca header
            $header = fgetcsv($handle, 1000, ';'); // Menyesuaikan dengan delimiter ";"

            // Membaca setiap baris CSV dan memasukkan data
            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                DB::table('capaian_unggulans')->insert([
                    'id_bidang' => $data[1],
                    'id_kategori' => $data[2],
                    'kode' => $data[3],
                    'nama' => $data[4],
                    'skor' => $data[5],
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
