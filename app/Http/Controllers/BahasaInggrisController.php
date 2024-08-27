<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BahasaInggris; // Pastikan model ini ada
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class BahasaInggrisController extends Controller
{
    protected $message = [
        'periode.required' => 'Periode harus ada',
        'listening.required' => 'Nilai listening harus diisi.',
        'listening.numeric' => 'Nilai listening harus berupa angka.',
        'speaking.required' => 'Nilai speaking harus diisi.',
        'speaking.numeric' => 'Nilai speaking harus berupa angka.',
        'writing.required' => 'Nilai writing harus diisi.',
        'writing.numeric' => 'Nilai writing harus berupa angka.',
        'listening_universitas.numeric' => 'Nilai listening harus berupa angka.',
        'speaking_universitas.numeric' => 'Nilai speaking harus berupa angka.',
        'writing_universitas.numeric' => 'Nilai writing harus berupa angka.',
        'id_mahasiswa.unique' => 'Mahasiswa sudah pernah di uji, hapus jika ingin pengujian ulang',
    ];

    public function getBahasaInggrisDataByFakultas(): JsonResponse
    {
        try {
            $periode = session('portal')->periode;
            $idFakultas = Auth::user()->id_fakultas;
            $bahasaInggris = BahasaInggris::whereHas('mahasiswa.departmen', function ($query) use ($idFakultas) {
                $query->where('id_fakultas', $idFakultas);
            })
                ->with(['mahasiswa.departmen'])
                ->where('periode', $periode)
                ->get();
            return DataTables::of($bahasaInggris)
                ->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data Bahasa Inggris tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }
    public function getBahasaInggrisDataByUniversitas(): JsonResponse
    {
        try {
            $periode = session('portal')->periode;
            $idFakultas = Auth::user()->id_fakultas;
            $bahasaInggris = BahasaInggris::with(['mahasiswa.departmen', 'mahasiswa.departmen.fakultas'])
                ->where('periode', $periode)
                ->get();
            return DataTables::of($bahasaInggris)
                ->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data Bahasa Inggris tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    public function index(): JsonResponse
    {
        try {
            $bahasaInggris = BahasaInggris::all();
            return response()->json([
                'data' => $bahasaInggris
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data Bahasa Inggris tidak ditemukan',
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
                'id_mahasiswa' => 'required|integer|unique:bahasa_inggris',
                'listening' => 'required|numeric',
                'speaking' => 'required|numeric',
                'writing' => 'required|numeric',
            ], $this->message);

            DB::beginTransaction();
            $request->merge([
                'created_by' => Auth::user()->id
            ]);
            $bahasaInggris = BahasaInggris::create($request->all());
            DB::commit();
            return response()->json([
                'message' => 'Berhasil menambahkan data Bahasa Inggris baru',
                'data' => $bahasaInggris
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menambahkan data Bahasa Inggris baru',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $bahasaInggris = BahasaInggris::findOrFail($id);
            return response()->json([
                'data' => $bahasaInggris
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data Bahasa Inggris tidak ditemukan',
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
                'id_mahasiswa' => 'required|integer|unique:bahasa_inggris,id_mahasiswa,' . $id,
                'listening' => 'nullable|numeric',
                'speaking' => 'nullable|numeric',
                'writing' => 'nullable|numeric',
                'listening_universitas' => 'nullable|numeric',
                'speaking_universitas' => 'nullable|numeric',
                'writing_universitas' => 'nullable|numeric',
            ], $this->message);

            DB::beginTransaction();
            $request->merge([
                'updated_by' => Auth::user()->id
            ]);
            $bahasaInggris = BahasaInggris::findOrFail($id);
            $bahasaInggris->update($request->all());
            DB::commit();
            return response()->json([
                'message' => 'Berhasil mengubah data Bahasa Inggris',
                'data' => $bahasaInggris
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal mengubah data Bahasa Inggris',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $bahasaInggris = BahasaInggris::findOrFail($id);
            $bahasaInggris->delete();
            return response()->json([
                'message' => 'Data Bahasa Inggris berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus data Bahasa Inggris',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}
