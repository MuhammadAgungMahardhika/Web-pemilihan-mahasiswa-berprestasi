<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KaryaIlmiah;
use App\Models\PenilaianKaryaIlmiah;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class KaryaIlmiahController extends Controller
{
    protected $message = [
        'periode.required' => 'Periode harus ada',
        'judul.required' => 'Judul karya ilmiah harus diisi.',
        'judul.max' => 'Judul karya ilmiah tidak boleh lebih dari :max karakter.',
        'dokumen_url.required' => 'Dokumen harus di upload',
    ];

    public function getKaryaIlmiahDataByFakultas($idFakultas): JsonResponse
    {
        try {
            $periode = session('portal')->periode;

            $karyaIlmiah = KaryaIlmiah::whereHas('mahasiswa.departmen', function ($query) use ($idFakultas) {
                $query->where('id_fakultas', $idFakultas);
            })->where('periode', $periode)
                ->with(['mahasiswa.departmen', 'penilaian_karya_ilmiah'])
                ->get();

            return DataTables::of($karyaIlmiah)
                ->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data karya ilmiah tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }
    public function getKaryaIlmiahDataByUniversitas(): JsonResponse
    {
        try {
            $periode = session('portal')->periode;

            $karyaIlmiah = KaryaIlmiah::where('periode', $periode)
                ->with(['mahasiswa.departmen', 'penilaian_karya_ilmiah'])
                ->get();


            return DataTables::of($karyaIlmiah)
                ->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data karya ilmiah tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    public function index(): JsonResponse
    {
        try {
            $karyaIlmiah = KaryaIlmiah::all();
            return response()->json([
                'data' => $karyaIlmiah
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data karya ilmiah tidak ditemukan',
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
                'periode' => 'required|string|min:4|max:4',
                'judul' => 'required|string|max:255',
                'dokumen_url' => 'required|string',
                'id_mahasiswa' => 'required|integer',
            ], $this->message);

            DB::beginTransaction();
            $folderId  = $request->dokumen_url;
            if ($folderId) {
                $temporary = new TemporaryFileController();
                $fileUrl = $temporary->moveToPermanentPath($folderId, "karya_ilmiah");
                $request->merge(['dokumen_url' => $fileUrl]);
            }
            $request->merge([
                'created_by' => Auth::user()->id
            ]);
            $karyaIlmiah = KaryaIlmiah::create($request->all());
            DB::commit();
            return response()->json([
                'message' => 'Berhasil menambahkan karya ilmiah baru',
                'data' => $karyaIlmiah
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menambahkan karya ilmiah baru',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $karyaIlmiah = KaryaIlmiah::with(['penilaian_karya_ilmiah'])->findOrFail($id);
            return response()->json([
                'data' => $karyaIlmiah
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Karya ilmiah tidak ditemukan',
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
                'periode' => 'required|string|min:4|max:4',
                'judul' => 'required|string|max:255',
                'dokumen_url' => 'required|string',
                'id_mahasiswa' => 'required|integer',
            ], $this->message);

            DB::beginTransaction();
            $folderId  = $request->dokumen_url;
            if ($folderId) {
                $temporary = new TemporaryFileController();
                $fileUrl = $temporary->moveToPermanentPath($folderId, "karya_ilmiah");
                $request->merge(['dokumen_url' => $fileUrl]);
            }
            $request->merge([
                'updated_by' => Auth::user()->id
            ]);
            $karyaIlmiah = KaryaIlmiah::findOrFail($id);
            $karyaIlmiah->update($request->all());
            DB::commit();
            return response()->json([
                'message' => 'Berhasil mengubah karya ilmiah',
                'data' => $karyaIlmiah
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal mengubah karya ilmiah',
                'data' => $e->getMessage()
            ], 500);
        }
    }
    public function reviewKaryaIlmiahTingkatFakultas(Request $request, $id)
    {
        try {
            $request->validate([
                'skor_fakultas' => 'required',
            ], $this->message);
            $userId = Auth::user()->id;

            $karyaIlmiah = PenilaianKaryaIlmiah::where('id_karya_ilmiah', $id)
                ->where('id_user', $userId)
                ->first();

            // Jika record ada, update; jika tidak ada, create
            if ($karyaIlmiah) {
                $karyaIlmiah->update([
                    'skor_fakultas' => $request->skor_fakultas,
                    'updated_by' => $userId,
                ]);
            } else {
                $karyaIlmiah = PenilaianKaryaIlmiah::create([
                    'id_karya_ilmiah' => $id,
                    'id_user' => $userId,
                    'skor_fakultas' => $request->skor_fakultas,
                    'created_by' => $userId,
                ]);
            }
            return response()->json([
                'message' => 'Berhasil melakukan penilaian karya ilmiah',
                'data' => $karyaIlmiah
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal melakukan penilaian karya ilmiah',
                'data' => $e->getMessage()
            ], 500);
        }
    }
    public function reviewKaryaIlmiahTingkatUniversitas(Request $request, $id)
    {
        try {
            $request->validate([
                'skor_universitas' => 'required',
            ], $this->message);
            $userId = Auth::user()->id;

            $karyaIlmiah = PenilaianKaryaIlmiah::where('id_karya_ilmiah', $id)
                ->where('id_user', $userId)
                ->first();

            // Jika record ada, update; jika tidak ada, create
            if ($karyaIlmiah) {
                $karyaIlmiah->update([
                    'skor_universitas' => $request->skor_universitas,
                    'updated_by' => $userId,
                ]);
            } else {
                $karyaIlmiah = PenilaianKaryaIlmiah::create([
                    'id_karya_ilmiah' => $id,
                    'id_user' => $userId,
                    'skor_universitas' => $request->skor_universitas,
                    'created_by' => $userId,
                ]);
            }
            return response()->json([
                'message' => 'Berhasil melakukan penilaian karya ilmiah',
                'data' => $karyaIlmiah
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal melakukan penilaian karya ilmiah',
                'data' => $e->getMessage()
            ], 500);
        }
    }


    public function destroy($id): JsonResponse
    {
        try {
            $karyaIlmiah = KaryaIlmiah::findOrFail($id);
            $karyaIlmiah->delete();
            return response()->json([
                'message' => 'Karya ilmiah ' . $karyaIlmiah->judul . ' berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus karya ilmiah',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}
