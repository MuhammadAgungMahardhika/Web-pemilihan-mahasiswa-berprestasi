<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DokumenPrestasi; // Model yang sesuai dengan tabel dokumen_prestasi
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class DokumenPrestasiController extends Controller
{
    protected $message = [
        'id_capaian_unggulan.required' => 'Capaian unggulan harus diisi.',
        'id_mahasiswa.required' => 'Mahasiswa harus diisi.',
        'judul.required' => 'Judul harus diisi.',
        'judul.max' => 'Judul tidak boleh lebih dari :max karakter.',
        'dokumen_url.required' => 'Dokumen harus diupload.',
    ];

    // Method ambil data dokumen prestasi oleh mahasiswa
    public function getDokumenPrestasiData(): JsonResponse
    {
        try {

            $idMahasiswa = Auth::user()->id_mahasiswa;
            $dokumenPrestasi = DokumenPrestasi::with(['capaian_unggulan', 'mahasiswa'])
                ->where('id_mahasiswa', $idMahasiswa)
                ->get();
            return DataTables::of($dokumenPrestasi)
                ->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data dokumen prestasi tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }
    // Method ambil data dokumen prestasi oleh admin departmen
    public function getDokumenPrestasiDataByDepartmen($idDepartmen): JsonResponse
    {
        try {
            $dokumenPrestasi = DokumenPrestasi::with(['capaian_unggulan', 'mahasiswa'])
                ->whereHas('mahasiswa', function ($query) use ($idDepartmen) {
                    $query->where('id_departmen', $idDepartmen);
                })
                ->get();
            return DataTables::of($dokumenPrestasi)
                ->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data dokumen prestasi tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    public function changeStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:ditolak,diterima',
            ], $this->message);

            $status = $request->status;
            $dokumenPrestasi = DokumenPrestasi::findOrFail($id);
            $dokumenPrestasi->status =  $status;
            $dokumenPrestasi->updated_by = Auth::user()->id;
            $dokumenPrestasi->save();
            return response()->json([
                'message' => 'Berhasil mengubah status dokumen prestasi',
                'data' => $dokumenPrestasi
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal mengubah status dokumen prestasi',
                'data' => $e->getMessage()
            ], 500);
        }
    }
    public function index(): JsonResponse
    {
        try {
            $dokumenPrestasi = DokumenPrestasi::all();
            return response()->json([
                'data' => $dokumenPrestasi
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data dokumen prestasi tidak ditemukan',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'id_capaian_unggulan' => 'required|integer',
                'id_mahasiswa' => 'required|integer',
                'judul' => 'required|string|max:255',
                'uploaded_at' => 'required',
                'dokumen_url' => 'required|string',
            ], $this->message);

            DB::beginTransaction();
            $folderId  = $request->dokumen_url;
            if ($folderId) {
                $temporary = new TemporaryFileController();
                $fileUrl = $temporary->moveToPermanentPath($folderId, "dokumen_prestasi");
                $request->merge(['dokumen_url' => $fileUrl, 'status' => 'pending']);
            }
            $request->merge(['created_by' => Auth::user()->id]);
            $dokumenPrestasi = DokumenPrestasi::create($request->all());
            DB::commit();
            return response()->json([
                'message' => 'Berhasil menambahkan data dokumen prestasi',
                'data' => $dokumenPrestasi
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => "Gagal menambahkan data dokumen prestasi",
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $dokumenPrestasi = DokumenPrestasi::findOrFail($id);
            return response()->json([
                'data' => $dokumenPrestasi
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Dokumen prestasi tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'id_capaian_unggulan' => 'required|integer',
                'id_mahasiswa' => 'required|integer',
                'judul' => 'required|string|max:255',
                'dokumen_url' => 'required|string',
                'status' => 'required|in:pending,ditolak,diterima',
            ], $this->message);

            DB::beginTransaction();
            $folderId  = $request->dokumen_url;
            if ($folderId) {
                $temporary = new TemporaryFileController();
                $fileUrl = $temporary->moveToPermanentPath($folderId, "dokumen_prestasi");
                $request->merge(['dokumen_url' => $fileUrl]);
            }

            $dokumenPrestasi = DokumenPrestasi::findOrFail($id);
            $dokumenPrestasi->updated_by = Auth::user()->id;
            $dokumenPrestasi->update($request->all());
            DB::commit();
            return response()->json([
                'message' => 'Berhasil mengubah data dokumen prestasi',
                'data' => $dokumenPrestasi
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal mengubah data dokumen prestasi',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $dokumenPrestasi = DokumenPrestasi::findOrFail($id);

            if ($dokumenPrestasi->dokumen_url && Storage::disk('public')->exists('dokumen_prestasi/' . $dokumenPrestasi->dokumen_url)) {
                Storage::disk('public')->delete('dokumen_prestasi/' . $dokumenPrestasi->dokumen_url);
            }

            $dokumenPrestasi->delete();
            return response()->json([
                'message' => 'Data dokumen prestasi berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus data dokumen prestasi',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    // custom
    public function status(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,ditolak,diterima',
            ], $this->message);

            $status = $request->status;
            $dokumenPrestasi = DokumenPrestasi::findOrFail($id);
            $dokumenPrestasi->status = $status;
            $dokumenPrestasi->updated_by = Auth::user()->id;
            $dokumenPrestasi->save();

            return response()->json([
                'message' => 'Status dokumen prestasi "' . $dokumenPrestasi->judul . '" berhasil diubah'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengubah status dokumen prestasi',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}
