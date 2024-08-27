<?php

namespace App\Http\Controllers;

use App\Models\Utusan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class UtusanController extends Controller
{
    protected $message = [
        'periode.required' => 'Periode harus ada',
        'id_mahasiswa.required' => 'Mahasiswa harus diisi.',
        'tingkat.required' => 'Tingkat harus diisi.',
    ];

    // Datatable utusan
    public function getUtusanData(): JsonResponse
    {
        try {
            $utusan = Utusan::with(['mahasiswa', 'portal'])->get();
            return DataTables::of($utusan)
                ->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data utusan tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }
    // Datatabel utusan departemen
    public function getUtusanDataByDepartmen($idDepartmen): JsonResponse
    {
        try {
            $periode = session('portal')->periode;

            // Main query to fetch utusan data with total score calculation
            $utusan = Utusan::select(
                'utusans.id as id',
                'utusans.tanggal_utus_departmen as tanggal_utus_departmen',
                'mahasiswas.nim as nim_mahasiswa',
                'mahasiswas.nama as nama_mahasiswa',
                'departmens.nama_departmen as nama_departmen',
                DB::raw('IFNULL(SUM(capaian_unggulans.skor), 0) as total_skor')
            )
                ->join('mahasiswas', 'mahasiswas.id', '=', 'utusans.id_mahasiswa')
                ->join('dokumen_prestasis', 'dokumen_prestasis.id_mahasiswa', '=', 'mahasiswas.id')
                ->join('capaian_unggulans', 'capaian_unggulans.id', '=', 'dokumen_prestasis.id_capaian_unggulan')
                ->leftJoin('departmens', 'mahasiswas.id_departmen', '=', 'departmens.id') // Ensure to join with departmens table
                ->where('mahasiswas.id_departmen', $idDepartmen)
                ->where('utusans.tingkat', 'departmen')
                ->where('utusans.periode', $periode)
                ->groupBy(
                    'utusans.id',
                    'utusans.tanggal_utus_departmen',
                    'mahasiswas.nim',
                    'mahasiswas.nama',
                    'departmens.nama_departmen'
                )
                ->get();

            return DataTables::of($utusan)->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data utusan tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    // Datatabel utusan fakultas
    public function getUtusanDataByFakultas($idFakultas): JsonResponse
    {
        try {
            $periode = session('portal')->periode;

            // Subquery to calculate the average score for each 'id_karya_ilmiah'
            $subqueryKaryaIlmiah = DB::table('penilaian_karya_ilmiahs')
                ->select('id_karya_ilmiah', DB::raw('COALESCE(AVG(skor_fakultas), 0) as rata_rata_skor_fakultas'))
                ->groupBy('id_karya_ilmiah');

            // Main query to fetch utusan data with scores and total score calculation
            $utusan = Utusan::select(
                'utusans.id as id',
                'utusans.tanggal_utus_fakultas as tanggal_utus_fakultas',
                'mahasiswas.nim as nim_mahasiswa',
                'mahasiswas.nama as nama_mahasiswa',
                'departmens.nama_departmen as nama_departmen',
                DB::raw('IFNULL(subqueryKaryaIlmiah.rata_rata_skor_fakultas, 0) as karya_ilmiah_skor'),
                DB::raw('ROUND(IFNULL(bahasa_inggris.listening, 0) + IFNULL(bahasa_inggris.speaking, 0) + IFNULL(bahasa_inggris.writing, 0), 2) as bahasa_inggris_skor'),
                DB::raw('IFNULL(SUM(capaian_unggulans.skor), 0) as dokumen_prestasi_skor'),
                DB::raw('ROUND(
                IFNULL(subqueryKaryaIlmiah.rata_rata_skor_fakultas, 0) +
                IFNULL(bahasa_inggris.listening, 0) + IFNULL(bahasa_inggris.speaking, 0) + IFNULL(bahasa_inggris.writing, 0) +
                IFNULL(SUM(capaian_unggulans.skor), 0), 2) as total_skor')
            )
                ->join('mahasiswas', 'utusans.id_mahasiswa', '=', 'mahasiswas.id')
                ->join('dokumen_prestasis', function ($join) use ($periode) {
                    $join->on('mahasiswas.id', '=', 'dokumen_prestasis.id_mahasiswa')
                        ->where('dokumen_prestasis.status', '=', 'diterima')
                        ->where('dokumen_prestasis.periode', '=', $periode);
                })
                ->join('capaian_unggulans', 'dokumen_prestasis.id_capaian_unggulan', '=', 'capaian_unggulans.id')
                ->leftJoinSub($subqueryKaryaIlmiah, 'subqueryKaryaIlmiah', function ($join) {
                    $join->on('mahasiswas.id', '=', 'subqueryKaryaIlmiah.id_karya_ilmiah');
                })
                ->join('departmens', 'mahasiswas.id_departmen', '=', 'departmens.id')
                ->leftJoin('bahasa_inggris', 'mahasiswas.id', '=', 'bahasa_inggris.id_mahasiswa')
                ->where('departmens.id_fakultas', $idFakultas)
                ->where('utusans.tingkat', 'fakultas')
                ->where('utusans.periode', $periode)
                ->groupBy(
                    'utusans.id',
                    'utusans.tanggal_utus_fakultas',
                    'mahasiswas.nim',
                    'mahasiswas.nama',
                    'departmens.nama_departmen',
                    'subqueryKaryaIlmiah.rata_rata_skor_fakultas',
                    'bahasa_inggris.listening',
                    'bahasa_inggris.speaking',
                    'bahasa_inggris.writing'
                )
                ->get();

            return DataTables::of($utusan)->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data utusan tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    public function getUtusanDataByUniversitas(): JsonResponse
    {
        try {
            $periode = session('portal')->periode;

            // Subquery to calculate the average score for each 'id_karya_ilmiah'
            $subqueryKaryaIlmiah = DB::table('penilaian_karya_ilmiahs')
                ->select('id_karya_ilmiah', DB::raw('COALESCE(AVG(skor_universitas), 0) as rata_rata_skor_universitas'))
                ->groupBy('id_karya_ilmiah');

            // Main query to fetch utusan data with scores and total score calculation
            $utusan = Utusan::select(
                'utusans.id as id',
                'utusans.tanggal_utus_universitas as tanggal_utus_universitas',
                'mahasiswas.id as id_mahasiswa',
                'mahasiswas.nim as nim_mahasiswa',
                'mahasiswas.nama as nama_mahasiswa',
                'fakultas.nama_fakultas as nama_fakultas',
                'departmens.nama_departmen as nama_departmen',
                DB::raw('IFNULL(subqueryKaryaIlmiah.rata_rata_skor_universitas, 0) as karya_ilmiah_skor'),
                DB::raw('ROUND(IFNULL(bahasa_inggris.listening_universitas, 0) + IFNULL(bahasa_inggris.speaking_universitas, 0) + IFNULL(bahasa_inggris.writing_universitas, 0), 2) as bahasa_inggris_skor'),
                DB::raw('IFNULL(SUM(capaian_unggulans.skor), 0) as dokumen_prestasi_skor'),
                DB::raw('ROUND(
                    IFNULL(subqueryKaryaIlmiah.rata_rata_skor_universitas, 0) +
                    IFNULL(bahasa_inggris.listening_universitas, 0) + IFNULL(bahasa_inggris.speaking_universitas, 0) + 
                    IFNULL(bahasa_inggris.writing_universitas, 0) +
                    IFNULL(SUM(capaian_unggulans.skor), 0), 2) as total_skor')
            )
                ->join('mahasiswas', 'utusans.id_mahasiswa', '=', 'mahasiswas.id')
                ->join('dokumen_prestasis', function ($join) use ($periode) {
                    $join->on('mahasiswas.id', '=', 'dokumen_prestasis.id_mahasiswa')
                        ->where('dokumen_prestasis.status', '=', 'diterima')
                        ->where('dokumen_prestasis.periode', '=', $periode);
                })
                ->join('capaian_unggulans', 'dokumen_prestasis.id_capaian_unggulan', '=', 'capaian_unggulans.id')
                ->leftJoin('karya_ilmiahs', 'mahasiswas.id', '=', 'karya_ilmiahs.id_mahasiswa')
                ->leftJoinSub($subqueryKaryaIlmiah, 'subqueryKaryaIlmiah', function ($join) {
                    $join->on('karya_ilmiahs.id', '=', 'subqueryKaryaIlmiah.id_karya_ilmiah');
                })
                ->join('departmens', 'mahasiswas.id_departmen', '=', 'departmens.id')
                ->join('fakultas', 'departmens.id_fakultas', '=', 'fakultas.id')
                ->leftJoin('bahasa_inggris', 'mahasiswas.id', '=', 'bahasa_inggris.id_mahasiswa')
                ->where('utusans.tingkat', 'universitas')
                ->where('utusans.periode', $periode)
                ->groupBy(
                    'utusans.id',
                    'utusans.tanggal_utus_universitas',
                    'mahasiswas.id',
                    'mahasiswas.nim',
                    'mahasiswas.nama',
                    'fakultas.nama_fakultas',
                    'departmens.nama_departmen',
                    'subqueryKaryaIlmiah.rata_rata_skor_universitas',
                    'bahasa_inggris.listening_universitas',
                    'bahasa_inggris.speaking_universitas',
                    'bahasa_inggris.writing_universitas'
                )
                ->get();
            return DataTables::of($utusan)->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data utusan tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }


    public function index(): JsonResponse
    {
        try {
            $utusan = Utusan::all();
            return response()->json([
                'data' => $utusan
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data utusan tidak ditemukan',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            // Validasi awal request
            $request->validate([
                'periode' => 'required|string|min:4|max:4',
                'id_mahasiswa' => 'required|exists:mahasiswas,id',
                'tingkat' => 'required|in:departmen,fakultas,universitas',
                'tanggal_utus_departmen' => 'nullable|date',
                'tanggal_utus_fakultas' => 'nullable|date',
                'tanggal_utus_universitas' => 'nullable|date',
            ], $this->message);

            $data = $request->all();

            // Set tanggal utus departmen jika tingkat adalah "departmen"
            if ($data['tingkat'] === 'departmen') {
                $data['tanggal_utus_departmen'] = now();
            }

            // Tambahkan informasi created_by ke dalam data
            $data['created_by'] = Auth::user()->id;

            // Simpan data utusan
            $utusan = Utusan::create($data);

            return response()->json([
                'message' => 'Berhasil menambahkan data utusan baru',
                'data' => $utusan
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan data utusan baru',
                'data' => $e->getMessage()
            ], 500);
        }
    }


    public function show($id): JsonResponse
    {
        try {
            $utusan = Utusan::findOrFail($id);
            return response()->json([
                'data' => $utusan
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data utusan tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    public function updateTingkat(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'tingkat' => 'required|in:departmen,fakultas,universitas',
            ], $this->message);

            $data = $request->all();

            if ($data['tingkat'] === 'universitas') {
                $data['tanggal_utus_universitas'] = now();
            } else if ($data['tingkat'] === 'fakultas') {
                $data['tanggal_utus_universitas'] = null;
                $data['tanggal_utus_fakultas'] = now();
            } else {
                $data['tanggal_utus_fakultas'] = null;
                $data['tanggal_utus_departmen'] = now();
            }

            $data['updated_by'] = Auth::user()->id;
            $utusan = Utusan::findOrFail($id);
            $utusan->update($data);

            return response()->json([
                'message' => 'Berhasil mengubah status utusan',
                'data' => $utusan
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengubah status utusan',
                'data' => $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'id_mahasiswa' => 'required|exists:mahasiswas,id',
                'tingkat' => 'required|in:departmen,fakultas,universitas',
                'tanggal_utus_departmen' => 'nullable|date',
                'tanggal_utus_fakultas' => 'nullable|date',
                'tanggal_utus_universitas' => 'nullable|date',
            ], $this->message);

            $data = $request->all();

            if ($data['tingkat'] === 'universitas') {
                $data['tanggal_utus_universitas'] = now();
            } else if ($data['tingkat'] === 'fakultas') {
                $data['tanggal_utus_universitas'] = null;
                $data['tanggal_utus_fakultas'] = now();
            } else {
                $data['tanggal_utus_fakultas'] = null;
                $data['tanggal_utus_departmen'] = now();
            }

            $data['updated_by'] = Auth::user()->id;
            $utusan = Utusan::findOrFail($id);

            $utusan->update($data);

            return response()->json([
                'message' => 'Berhasil mengubah data utusan',
                'data' => $utusan
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengubah data utusan',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $utusan = Utusan::findOrFail($id);
            $utusan->delete();

            return response()->json([
                'message' => 'Data utusan berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus data utusan',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}
