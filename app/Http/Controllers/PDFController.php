<?php

namespace App\Http\Controllers;

use App\Models\BahasaInggris;
use App\Models\CapaianUnggulan;
use App\Models\DokumenPrestasi;
use App\Models\KaryaIlmiah;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PDFController extends Controller
{
    public function generatePDF($id)
    {
        $dataMahasiswa = Mahasiswa::with('departmen.fakultas')->findOrFail($id);
        $dokumenPrestasi = DokumenPrestasi::where('id_mahasiswa', $id)->get();
        $dokumenPrestasiSkor = 0;

        foreach ($dokumenPrestasi as $dokumen) {
            $cp = CapaianUnggulan::where('id', $dokumen->id_capaian_unggulan)->first();
            $dokumenPrestasiSkor += $cp->skor;
        }

        $totalKaryaIlmiahScore = DB::table('penilaian_karya_ilmiahs')
            ->join('karya_ilmiahs', 'penilaian_karya_ilmiahs.id_karya_ilmiah', '=', 'karya_ilmiahs.id')
            ->where('karya_ilmiahs.id_mahasiswa', $id)
            ->whereNotNull('penilaian_karya_ilmiahs.skor_universitas')
            ->sum('penilaian_karya_ilmiahs.skor_universitas');

        $totalPenilaianKaryaIlmiahCount = DB::table('penilaian_karya_ilmiahs')
            ->whereNotNull('skor_universitas')
            ->count();

        $rataRataSkorKaryaIlmiah = $totalPenilaianKaryaIlmiahCount > 0 ? $totalKaryaIlmiahScore / $totalPenilaianKaryaIlmiahCount : 0;

        $bahasaInggris = BahasaInggris::where('id_mahasiswa', $id)->first();
        $bahasaInggrisSkor = $bahasaInggris->writing_universitas + $bahasaInggris->speaking_universitas + $bahasaInggris->listening_universitas;

        // Include the FPDF library from the thirdparty directory
        require_once app_path('thirdparty/fpdf/fpdf.php');

        // Data input
        $biodata = [
            'Fakultas' => $dataMahasiswa->departmen->fakultas->nama_fakultas,
            'Jurusan' => $dataMahasiswa->departmen->nama_departmen,
            'NIM' => $dataMahasiswa->nim,
            'NIK' => $dataMahasiswa->nik,
            'Nama' => $dataMahasiswa->nama,
            'IPK' => $dataMahasiswa->ipk,
            'Semester' => $dataMahasiswa->semester,
            'Jenis Kelamin' => $dataMahasiswa->jenis_kelamin,
            'Agama' => $dataMahasiswa->agama,
            'Tempat Lahir' => $dataMahasiswa->tempat_lahir,
            'Tanggal Lahir' => $dataMahasiswa->tgl_lahir,
            'No Hp' => $dataMahasiswa->no_hp,
            'Alamat' => $dataMahasiswa->alamat,
        ];

        $scores = [
            'Dokumen Prestasi' => $dokumenPrestasiSkor,
            'Karya Ilmiah' => $rataRataSkorKaryaIlmiah,
            'Bahasa Inggris' => $bahasaInggrisSkor,
            'Total Skor' => $dokumenPrestasiSkor + $rataRataSkorKaryaIlmiah + $bahasaInggrisSkor
        ];

        $photo = $dataMahasiswa->user->foto_url ? $dataMahasiswa->user->foto_url : null;
        $photoPath = $photo ? public_path('storage/profil/' . $photo) : null;

        // Create PDF
        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->Cell(0, 10, 'Data Mahasiswa Berprestasi', 0, 1, 'C');
        $pdf->Ln(10);

        // Biodata Section
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Biodata', 0, 1);

        // Check if photo is available
        if ($photoPath && file_exists($photoPath)) {
            // Add the image
            $pdf->Image($photoPath, 150, 40, 30, 40);
        } else {
            // Draw a placeholder rectangle
            $pdf->Rect(150, 40, 30, 40);
            // Add text inside the placeholder
            $pdf->SetXY(150, 40 + 20); // Position text inside the rectangle
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(30, 10, 'Foto', 0, 0, 'C');
        }

        // Move cursor to the right of the image
        $pdf->SetXY(10, 40); // Adjust X and Y position based on your needs

        // Biodata text
        $pdf->SetFont('Arial', '', 12);
        foreach ($biodata as $label => $value) {
            $pdf->Cell(40, 10, $label, 0, 0);
            $pdf->Cell(0, 10, ': ' . $value, 0, 1);
        }

        $pdf->Ln(10); // Add space between sections

        // Scores Section
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Skor', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        foreach ($scores as $label => $value) {
            $pdf->Cell(60, 10, $label, 0, 0);
            $pdf->Cell(0, 10, ': ' . $value, 0, 1);
        }

        // Output PDF
        $pdf->Output();
        exit;
    }
}
