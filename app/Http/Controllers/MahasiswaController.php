<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class MahasiswaController extends Controller
{
    protected $message = [
        'id_departmen.required' => 'Departmen wajib ada',
        'nama.required' => 'Nama mahasiswa harus diisi.',
        'nim.required' => 'NIM harus diisi.',
        'nim.unique' => 'NIM sudah terdaftar.',
        'semester.required' => 'Semester harus dipilih.',
        'jenis_kelamin.required' => 'Jenis kelamin harus dipilih.',
    ];

    // ambil data mahasiswa
    public function getMahasiswaData(): JsonResponse
    {
        try {
            $mahasiswa = Mahasiswa::with(['user'])->get();
            return DataTables::of($mahasiswa)
                ->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }

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
            $mahasiswa = Mahasiswa::select('mahasiswas.id', 'mahasiswas.nim', 'mahasiswas.nama', DB::raw('SUM(capaian_unggulans.skor) as total_skor'))
                ->join('dokumen_prestasis', function ($join) {
                    $join->on('mahasiswas.id', '=', 'dokumen_prestasis.id_mahasiswa')
                        ->where('dokumen_prestasis.status', '=', 'diterima');
                })
                ->join('capaian_unggulans', 'dokumen_prestasis.id_capaian_unggulan', '=', 'capaian_unggulans.id')
                ->leftJoin('utusans', 'utusans.id_mahasiswa', '=', 'mahasiswas.id')
                ->where('utusans.id_mahasiswa', '=', null)
                ->where('mahasiswas.id_departmen', $idDepartmen)
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
