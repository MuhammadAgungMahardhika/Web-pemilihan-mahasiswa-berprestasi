<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function dokumenPrestasi()
    {
        $send = [
            'title' => "Dokumen Prestasi"
        ];
        return view('pages.dokumen-prestasi.index', $send);
    }
    public function capaianUnggulan()
    {
        $send = [
            'title' => "Capaian Unggulan"
        ];
        return view('pages.capaian-unggulan.index', $send);
    }
    public function bidang()
    {
        $send = [
            'title' => "Bidang"
        ];
        return view('pages.bidang.index', $send);
    }
    public function kategori()
    {
        $send = [
            'title' => "Kategori"
        ];
        return view('pages.kategori.index', $send);
    }

    public function mahasiswa()
    {
        $send = [
            'title' => "Mahasiswa"
        ];
        return view('pages.mahasiswa.index', $send);
    }
    public function fakultas()
    {
        $send = [
            'title' => "Fakultas"
        ];
        return view('pages.fakultas.index', $send);
    }
    public function departmen()
    {
        $send = [
            'title' => "Departmen"
        ];
        return view('pages.departmen.index', $send);
    }
    public function verifikasiDokumen()
    {
        $send = [
            'title' => "Verifikasi Dokumen"
        ];
        return view('pages.verifikasi-dokumen.index', $send);
    }
    public function adminFakultas()
    {
        $send = [
            'title' => "Admin Fakultas"
        ];
        return view('pages.admin-fakultas.index', $send);
    }
}
