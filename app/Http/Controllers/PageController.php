<?php

namespace App\Http\Controllers;

use App\Models\BahasaInggris;
use App\Models\DokumenPrestasi;
use App\Models\KaryaIlmiah;
use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\Utusan;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function dashboard()
    {
        $send = [
            'title' => "Dashboard"
        ];
        $userRole = Auth::user()->role->nama;

        if ($userRole == "mahasiswa") {
        } else if ($userRole == "admin_departmen") {
        } else if ($userRole == "admin_fakultas") {
        } else if ($userRole == "admin_universitas") {
        }

        return view('pages.dashboard.index', $send);
    }
    public function profil()
    {
        $send = [
            'title' => "Profil"
        ];
        return view('pages.profil.index', $send);
    }
    public function portal()
    {
        $send = [
            'title' => "Portal"
        ];
        return view('pages.portal.index', $send);
    }
    public function dokumenPrestasi()
    {
        $send = [
            'title' => "Dokumen Prestasi"
        ];
        return view('pages.dokumen-prestasi.index', $send);
    }
    public function ujiBahasaInggris()
    {
        $send = [
            'title' => "Uji Bahasa Inggris"
        ];
        return view('pages.uji-bahasa-inggris.index', $send);
    }
    public function karyaIlmiah()
    {
        $periode = session('portal')->periode;
        $idMahasiswa = Auth::user()->id_mahasiswa;
        $data = KaryaIlmiah::with(['user', 'penilaian_karya_ilmiah'])
            ->where('id_mahasiswa', $idMahasiswa)
            ->where('periode', $periode)
            ->first();
        $send = [
            'title' => "Karya Ilmiah",
            'data' => $data
        ];
        return view('pages.karya-ilmiah.index', $send);
    }
    public function bahasaInggris()
    {
        $periode = session('portal')->periode;
        $idMahasiswa = Auth::user()->id_mahasiswa;
        $data = BahasaInggris::with('user')
            ->where('id_mahasiswa', $idMahasiswa)
            ->where('periode', $periode)
            ->first();
        $send = [
            'title' => "Bahasa Inggris",
            'data' => $data
        ];
        return view('pages.bahasa-inggris.index', $send);
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
    public function verifikasiDokumenByMahasiswa($idMahasiswa)
    {
        $mahasiswa = Mahasiswa::findOrFail($idMahasiswa);
        $send = [
            'title' => "Verifikasi Dokumen",
            'data' =>  $mahasiswa
        ];
        return view('pages.verifikasi-dokumen.index', $send);
    }
    public function penilaianKaryaIlmiahFakultas()
    {
        $send = [
            'title' => "Penilaian Karya Ilmiah"
        ];
        return view('pages.penilaian-karya-ilmiah.fakultas', $send);
    }
    public function penilaianKaryaIlmiahUniversitas()
    {
        $send = [
            'title' => "Penilaian Karya Ilmiah"
        ];
        return view('pages.penilaian-karya-ilmiah.universitas', $send);
    }

    public function juriFakultas()
    {
        $send = [
            'title' => "Juri Fakultas"
        ];
        return view('pages.juri.fakultas', $send);
    }
    public function juriUniversitas()
    {
        $send = [
            'title' => "Juri Universitas"
        ];
        return view('pages.juri.universitas', $send);
    }


    public function adminDepartmen()
    {
        $send = [
            'title' => "Admin Departemen"
        ];
        return view('pages.admin.departmen', $send);
    }
    public function adminFakultas()
    {
        $send = [
            'title' => "Admin Fakultas"
        ];
        return view('pages.admin.fakultas', $send);
    }


    public function utusanDepartmen()
    {
        $send = [
            'title' => "Utusan Departemen"
        ];
        return view('pages.utusan.departmen', $send);
    }
    public function utusanFakultas()
    {
        $send = [
            'title' => "Utusan Fakultas"
        ];
        return view('pages.utusan.fakultas', $send);
    }
    public function utusanUniversitas()
    {
        $send = [
            'title' => "Utusan Universitas"
        ];
        return view('pages.utusan.universitas', $send);
    }

    public function rankingDepartmen()
    {
        $send = [
            'title' => "Ranking Departemen"
        ];
        return view('pages.ranking.departmen', $send);
    }
    public function rankingFakultas()
    {
        $send = [
            'title' => "Ranking Fakultas"
        ];
        return view('pages.ranking.fakultas', $send);
    }
    public function rankingUniversitas()
    {
        $send = [
            'title' => "Ranking Universitas"
        ];
        return view('pages.ranking.universitas', $send);
    }
}
