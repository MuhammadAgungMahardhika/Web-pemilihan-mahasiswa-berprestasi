<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MahasiswaController extends Controller
{
    protected $message = [
        'id_departmen.required' => 'Departmen wajib ada',
        'nama.required' => 'Nama mahasiswa harus diisi.',
        'nim.required' => 'NIM harus diisi.',
        'nim.unique' => 'NIM sudah terdaftar.',
        'ipk.required' => 'Ipk wajib diisi',
        'semester.required' => 'Semester harus dipilih.',
        'jenis_kelamin.required' => 'Jenis kelamin harus dipilih.',
    ];

    // ambil data mahasiswa berdasarkan departmen
    public function getMahasiswaDataByDepartmen($idDepartmen): JsonResponse
    {
        try {
            $mahasiswa = Mahasiswa::with(['user'])
                ->where('id_departmen', $idDepartmen)
                ->get();
            return DataTables::of($mahasiswa)
                ->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    // ambil data ranking mahasiswa berdasarkan departmen
    public function getMahasiswaRankingDataByDepartmen($idDepartmen): JsonResponse
    {
        try {
            $periode = session('portal')->periode;
            $mahasiswa = Mahasiswa::select('mahasiswas.*', DB::raw('SUM(capaian_unggulans.skor) as total_skor'))
                ->join('dokumen_prestasis', function ($join) use ($periode) {
                    $join->on('mahasiswas.id', '=', 'dokumen_prestasis.id_mahasiswa')
                        ->where('dokumen_prestasis.status', '=', 'diterima')
                        ->where('dokumen_prestasis.periode', $periode);
                })
                ->join('capaian_unggulans', 'dokumen_prestasis.id_capaian_unggulan', '=', 'capaian_unggulans.id')
                ->leftJoin('utusans', 'utusans.id_mahasiswa', '=', 'mahasiswas.id')
                ->where('mahasiswas.id_departmen', $idDepartmen)
                ->where('utusans.id_mahasiswa', '=', null)
                ->groupBy('mahasiswas.id', 'mahasiswas.nama', 'mahasiswas.nim')
                ->get();

            return DataTables::of($mahasiswa)
                ->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    // ambil data ranking mahasiswa berdasarkan fakultas
    public function getMahasiswaRankingDataByFakultas($idFakultas): JsonResponse
    {
        try {
            $periode = session('portal')->periode;
            $subqueryKaryaIlmiah = DB::table('penilaian_karya_ilmiahs')
            ->select('id_karya_ilmiah', DB::raw('AVG(skor_fakultas) as rata_rata_skor_fakultas'))
            ->groupBy('id_karya_ilmiah');
        
        $mahasiswa = DB::table('mahasiswas as m')
            ->leftJoin('karya_ilmiahs as k', 'm.id', '=', 'k.id_mahasiswa')
            ->leftJoinSub($subqueryKaryaIlmiah, 'subqueryKaryaIlmiah', function ($join) {
                $join->on('k.id', '=', 'subqueryKaryaIlmiah.id_karya_ilmiah');
            })
            ->leftJoin('bahasa_inggris as bi', 'm.id', '=', 'bi.id_mahasiswa')
            ->leftJoin('dokumen_prestasis as dp', 'm.id', '=', 'dp.id_mahasiswa')
            ->leftJoin('capaian_unggulans as cu', 'dp.id_capaian_unggulan', '=', 'cu.id')
            ->leftJoin('departmens as d', 'm.id_departmen', '=', 'd.id')
            ->leftJoin('utusans as u', 'm.id', '=', 'u.id_mahasiswa')
            ->select(
                'm.id',
                'm.nim',
                'm.nama',
                'm.ipk',
                'd.nama_departmen',
                'u.id as id_utusan',
                DB::raw('IFNULL(subqueryKaryaIlmiah.rata_rata_skor_fakultas, 0) as karya_ilmiah_skor'),
                DB::raw('ROUND(IFNULL(bi.listening, 0) + IFNULL(bi.speaking, 0) + IFNULL(bi.writing, 0), 2) as bahasa_inggris_skor'),
                DB::raw('IFNULL(SUM(cu.skor), 0) as dokumen_prestasi_skor'),
                DB::raw('ROUND(
                    IFNULL(subqueryKaryaIlmiah.rata_rata_skor_fakultas, 0) +
                    IFNULL(bi.listening, 0) +
                    IFNULL(bi.speaking, 0) +
                    IFNULL(bi.writing, 0) +
                    IFNULL(SUM(cu.skor), 0),
                2) as total_skor')
            )
            ->where('dp.status', '=', 'diterima')
            ->where('dp.periode', '=', 2024)
            ->where('d.id_fakultas', '=', 1)
            ->where('u.tingkat', '=', 'departmen') // Sesuaikan dengan kondisi yang sesuai
            ->groupBy(
                'm.id',
                'm.nim',
                'm.nama',
                'm.ipk',
                'd.nama_departmen',
                'u.id',
                'subqueryKaryaIlmiah.rata_rata_skor_fakultas',
                'bi.listening',
                'bi.speaking',
                'bi.writing'
            )
            ->get();
        
        
            return DataTables::of($mahasiswa)->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    // ambil data ranking mahasiswa berdasarkan universitas
    public function getMahasiswaRankingDataByUniversitas(): JsonResponse
    {
        try {
            $periode = session('portal')->periode;

           // Subquery to calculate the average score for each 'id_karya_ilmiah'
            $subqueryKaryaIlmiah = DB::table('penilaian_karya_ilmiahs')
            ->select('id_karya_ilmiah', DB::raw('COALESCE(AVG(skor_universitas), 0) as rata_rata_skor_universitas'))
            ->groupBy('id_karya_ilmiah');

            // Main query to fetch mahasiswa data with scores and total score calculation
            $mahasiswa = DB::table('mahasiswas as m')
            ->leftJoin('karya_ilmiahs as k', 'm.id', '=', 'k.id_mahasiswa')
            ->leftJoinSub($subqueryKaryaIlmiah, 'subqueryKaryaIlmiah', function ($join) {
                $join->on('k.id', '=', 'subqueryKaryaIlmiah.id_karya_ilmiah');
            })
            ->leftJoin('bahasa_inggris as bi', 'm.id', '=', 'bi.id_mahasiswa')
            ->leftJoin('dokumen_prestasis as dp', 'm.id', '=', 'dp.id_mahasiswa')
            ->leftJoin('capaian_unggulans as cu', 'dp.id_capaian_unggulan', '=', 'cu.id')
            ->leftJoin('departmens as d', 'm.id_departmen', '=', 'd.id')
            ->leftJoin('fakultas as f', 'd.id_fakultas', '=', 'f.id')
            ->leftJoin('utusans as u', 'm.id', '=', 'u.id_mahasiswa')
            ->select(
                'm.id',
                'm.nim',
                'm.nama',
                'm.ipk',
                'f.nama_fakultas',
                'd.nama_departmen',
                'u.id as id_utusan',
                DB::raw('IFNULL(subqueryKaryaIlmiah.rata_rata_skor_universitas, 0) as karya_ilmiah_skor'),
                DB::raw('ROUND(IFNULL(bi.listening, 0) + IFNULL(bi.speaking, 0) + IFNULL(bi.writing, 0), 2) as bahasa_inggris_skor'),
                DB::raw('IFNULL(SUM(cu.skor), 0) as dokumen_prestasi_skor'),
                DB::raw('ROUND(
                    IFNULL(subqueryKaryaIlmiah.rata_rata_skor_universitas, 0) +
                    IFNULL(bi.listening, 0) +
                    IFNULL(bi.speaking, 0) +
                    IFNULL(bi.writing, 0) +
                    IFNULL(SUM(cu.skor), 0),
                2) as total_skor')
            )
            ->where('dp.status', '=', 'diterima')
            ->where('dp.periode', '=', $periode) // Gunakan variabel periode yang tepat
            ->where('u.tingkat', '=', 'fakultas')
            ->groupBy(
                'm.id',
                'm.nim',
                'm.nama',
                'm.ipk',
                'f.nama_fakultas',
                'd.nama_departmen',
                'u.id',
                'subqueryKaryaIlmiah.rata_rata_skor_universitas',
                'bi.listening',
                'bi.speaking',
                'bi.writing'
            )
            ->get();

            return DataTables::of($mahasiswa)->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }


    public function index(): JsonResponse
    {
        try {
            $mahasiswa = Mahasiswa::all();
            return response()->json([
                'data' => $mahasiswa
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function create(): JsonResponse
    {
        return response()->json(['message' => 'Create form not available in API']);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'nik' => 'nullable|string|max:20',
                'nim' => 'required|string|max:20|unique:mahasiswas',
                'nama' => 'required|string|max:255',
                'ipk' => 'required|string',
                'semester' => 'required|in:1,2,3,4,5,6,7,8',
                'id_departmen' => 'required',
                'jenis_kelamin' => 'required|in:perempuan,laki-laki',
                'no_hp' => 'nullable|string|max:15',
                'alamat' => 'nullable|string',
                'nama_ayah' => 'nullable|string|max:255',
                'no_hp_ayah' => 'nullable|string|max:15',
                'nama_ibu' => 'nullable|string|max:255',
            ], $this->message);

            DB::beginTransaction();
            $userId =  Auth::user()->id;
            $request->merge([
                'created_by' => $userId
            ]);
            $mahasiswa = Mahasiswa::create($request->all());

            $userData = [
                'name' => $mahasiswa->nama,
                'username' => strtolower($mahasiswa->nim),
                'password' => '12345678',
                'password_confirmation' => '12345678',
                'id_role' => 1,
                'id_mahasiswa' => $mahasiswa->id,
                'status' => 'aktif',
                'created_by' =>  $userId
            ];

            $filteredRequest = new Request($userData);
            $registeredUserController = new RegisteredUserController();
            $registeredUserController->store($filteredRequest);

            DB::commit();
            return response()->json([
                'message' => 'Berhasil menambahkan data mahasiswa baru',
                'data' => $mahasiswa
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menambahkan data mahasiswa baru',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $mahasiswa = Mahasiswa::findOrFail($id);
            return response()->json([
                'data' => $mahasiswa
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Mahasiswa tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    public function edit($id): JsonResponse
    {
        return response()->json(['message' => 'Edit form not available in API']);
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'nik' => 'nullable|string|max:20',
                'nim' => 'required|string|max:20|unique:mahasiswas,nim,' . $id,
                'nama' => 'required|string|max:255',
                'ipk' => 'required|string',
                'semester' => 'required|in:1,2,3,4,5,6,7,8',
                'id_departmen' => 'required',
                'jenis_kelamin' => 'required|in:perempuan,laki-laki',
                'no_hp' => 'nullable|string|max:15',
                'alamat' => 'nullable|string',
                'nama_ayah' => 'nullable|string|max:255',
                'no_hp_ayah' => 'nullable|string|max:15',
                'nama_ibu' => 'nullable|string|max:255',
            ], $this->message);

            $request->merge([
                'updated_by' => Auth::user()->id
            ]);
            $mahasiswa = Mahasiswa::findOrFail($id);
            $mahasiswa->update($request->all());
            return response()->json([
                'message' => 'Berhasil mengubah data mahasiswa',
                'data' => $mahasiswa
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengubah data mahasiswa',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $mahasiswa = Mahasiswa::findOrFail($id);
            $mahasiswa->delete();
            return response()->json([
                'message' => 'Data mahasiswa ' . $mahasiswa->nama . ' berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus data mahasiswa',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}
