<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Exception;
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

    // Method untuk DataTables API
    public function getMahasiswaData(): JsonResponse
    {
        try {
            $mahasiswa = Mahasiswa::with(['user'])->orderBy('id', 'DESC');
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
            $mahasiswa = Mahasiswa::create($request->all());

            $userData = [
                'name' => $mahasiswa->nama,
                'username' => strtolower($mahasiswa->nim),
                'password' => '12345678',
                'password_confirmation' => '12345678',
                'id_role' => 1,
                'id_mahasiswa' => $mahasiswa->id,
                'status' => 'nonaktif'
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
