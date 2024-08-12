<?php

namespace App\Http\Controllers;

use App\Models\DokumenPrestasi;
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

            $mahasiswaId = Auth::user()->id_mahasiswa;
            $waitingDocument =  DokumenPrestasi::where('status', 'pending')
                ->where('id_mahasiswa', $mahasiswaId)->count();
            $acceptedDocument = DokumenPrestasi::where('status', 'diterima')
                ->where('id_mahasiswa', $mahasiswaId)->count();
            $abortedDocumentt = DokumenPrestasi::where('status', 'ditolak')
                ->where('id_mahasiswa', $mahasiswaId)->count();
            $send['menunggu'] = $waitingDocument;
            $send['diterima'] = $acceptedDocument;
            $send['ditolak'] = $abortedDocumentt;
        } else if ($userRole == "admin_departmen") {
            $departmenId = Auth::user()->id_departmen;
            $documentCounts =
                DokumenPrestasi::join('mahasiswas', 'dokumen_prestasis.id_mahasiswa', '=', 'mahasiswas.id')
                ->where('mahasiswas.id_departmen', $departmenId)
                ->selectRaw('
                SUM(CASE WHEN dokumen_prestasis.status = "pending" THEN 1 ELSE 0 END) as menunggu,
                SUM(CASE WHEN dokumen_prestasis.status = "diterima" THEN 1 ELSE 0 END) as diterima,
                SUM(CASE WHEN dokumen_prestasis.status = "ditolak" THEN 1 ELSE 0 END) as ditolak')
                ->first();

            // Menghitung jumlah mahasiswa
            $mahasiswaCount = Mahasiswa::where('id_departmen', $departmenId)->count();

            $send['menunggu'] = $documentCounts->menunggu ?? 0;
            $send['diterima'] = $documentCounts->diterima ?? 0;
            $send['ditolak'] = $documentCounts->ditolak ?? 0;
            $send['mahasiswa'] = $mahasiswaCount ?? 0;
            $send['utusan'] = Utusan::join('mahasiswas', 'utusans.id_mahasiswa', '=', 'mahasiswas.id')
                ->where('mahasiswas.id_departmen', $departmenId)
                ->where('utusans.id_portal', session('portal')->id)
                ->count();
        } else if ($userRole == "admin_fakultas") {
            $fakultasId = Auth::user()->id_fakultas;
            $send['admin_departmen']  = User::join('departmens', 'users.id_departmen', '=', 'departmens.id')
                ->where('departmens.id_fakultas', $fakultasId)
                ->where('users.id_role', 2)
                ->count();
            $send['utusan_fakultas'] = Utusan::join('mahasiswas', 'utusans.id_mahasiswa', '=', 'mahasiswas.id')
                ->join('departmens', 'mahasiswas.id_departmen', '=', 'departmens.id')
                ->where('departmens.id_fakultas', $fakultasId) // Membatasi hanya pada fakultas tertentu
                ->where('utusans.tingkat', 'fakultas') // Membatasi hanya pada tingkat fakultas
                ->where('utusans.id_portal', session('portal')->id)
                ->count();
        } else if ($userRole == "admin_universitas") {
            $adminFakultas =  User::where('id_fakultas', '!=', null)
                ->where('id_role', 3)
                ->count();
            $send['admin_fakultas'] = $adminFakultas;
            // $send['utusan_kampus'] = Utusan::where('utusans.tingkat', 'universitas')
            //     ->where('utusans.id_portal', session('portal')->id)
            //     ->count();
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
    public function adminDepartmen()
    {
        $send = [
            'title' => "Admin Departemen"
        ];
        return view('pages.admin-departmen.index', $send);
    }
    public function utusanDepartmen()
    {
        $send = [
            'title' => "Utusan Departemen"
        ];
        return view('pages.utusan-departmen.index', $send);
    }
    public function utusanFakultas()
    {
        $send = [
            'title' => "Utusan Fakultas"
        ];
        return view('pages.utusan-fakultas.index', $send);
    }
    public function utusanUniversitas()
    {
        $send = [
            'title' => "Utusan Universitas"
        ];
        return view('pages.utusan-universitas.index', $send);
    }

    public function rankingDepartmen()
    {
        $send = [
            'title' => "Ranking Departemen"
        ];
        return view('pages.ranking-departmen.index', $send);
    }
    public function rankingFakultas()
    {
        $send = [
            'title' => "Ranking Fakultas"
        ];
        return view('pages.ranking-fakultas.index', $send);
    }
    public function rankingUniversitas()
    {
        $send = [
            'title' => "Ranking Universitas"
        ];
        return view('pages.ranking-universitas.index', $send);
    }
}
