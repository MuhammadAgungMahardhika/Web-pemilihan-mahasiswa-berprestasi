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

    // Method untuk DataTables API
    public function getKaryaIlmiahData(): JsonResponse
    {
        try {
            $karyaIlmiah = KaryaIlmiah::get();
            return DataTables::of($karyaIlmiah)
                ->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data karya ilmiah tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }
    public function getKaryaIlmiahDataByFakultas($idFakultas): JsonResponse
    {
        try {
            $periode = session('portal')->periode;
            $karyaIlmiah = KaryaIlmiah::whereHas('mahasiswa.departmen', function ($query) use ($idFakultas) {
                $query->where('id_fakultas', $idFakultas);
            })
                ->with(['mahasiswa.departmen'])
                ->where('periode', $periode)
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
                $request->merge(['dokumen_url' => $fileUrl, 'status' => 'pending']);
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
            $karyaIlmiah = KaryaIlmiah::findOrFail($id);
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

    public function changeStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:ditolak,diterima',
                'skor_fakultas' => 'required_if:status,diterima|numeric'
            ], $this->message);

            $status = $request->status;
            $skor_fakultas = $request->skor_fakultas;
            $idUser = Auth::user()->id;
            DB::beginTransaction();
            $karyaIlmiah = KaryaIlmiah::findOrFail($id);
            $karyaIlmiah->status =  $status;
            $karyaIlmiah->updated_by =  $idUser;
            $karyaIlmiah->save();

            if ($status == "diterima" && $skor_fakultas) {
                PenilaianKaryaIlmiah::updateOrCreate([
                    'id_karya_ilmiah' => $karyaIlmiah->id,
                    'id_user' => $idUser,
                    'skor_fakultas' => $skor_fakultas
                ]);
            } else if ($status == "ditolak") {
                $penilaianKaryaIlmiah = PenilaianKaryaIlmiah::where('id_karya_ilmiah', $karyaIlmiah->id)->where('id_user', $idUser)->first();
                if ($penilaianKaryaIlmiah) {
                    $penilaianKaryaIlmiah->delete();
                }
            }
            DB::commit();
            return response()->json([
                'message' => 'Berhasil mengubah status karya ilmiah',
                'data' => $karyaIlmiah
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal mengubah status karya ilmiah',
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
