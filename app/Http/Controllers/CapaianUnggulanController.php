<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CapaianUnggulan; // Model untuk tabel capaian_unggulan
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CapaianUnggulanController extends Controller
{
    protected $message = [
        'id_bidang.required' => 'Bidang harus diisi.',
        'id_kategori.required' => 'Kategori harus diisi.',
        'kode.required' => 'Kode harus diisi.',
        'kode.max' => 'Kode tidak boleh lebih dari :max karakter.',
        'nama.required' => 'Nama harus diisi.',
        'nama.max' => 'Nama tidak boleh lebih dari :max karakter.',
        'skor.required' => 'Skor harus diisi.',
        'skor.numeric' => 'Skor harus berupa angka.',
    ];

    // Method untuk DataTables API
    public function getCapaianUnggulanData(): JsonResponse
    {
        try {
            $capaianUnggulan = CapaianUnggulan::with(['bidang', 'kategori'])->get();
            return DataTables::of($capaianUnggulan)
                ->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data capaian unggulan tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    public function index(): JsonResponse
    {
        try {
            $capaianUnggulan = CapaianUnggulan::with('kategori', 'bidang')->get();
            return response()->json([
                'data' => $capaianUnggulan
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data capaian unggulan tidak ditemukan',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'kode' => 'required|string|max:255',
                'nama' => 'required|string|max:255',
                'skor' => 'required|numeric',
                'id_bidang' => 'required|integer',
                'id_kategori' => 'required|integer',
            ], $this->message);

            DB::beginTransaction();

            $request->merge([
                'created_by' => Auth::user()->id
            ]);
            $capaianUnggulan = CapaianUnggulan::create($request->all());

            DB::commit();
            return response()->json([
                'message' => 'Berhasil menambahkan data capaian unggulan',
                'data' => $capaianUnggulan
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => "Gagal menambahkan data capaian unggulan",
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $capaianUnggulan = CapaianUnggulan::findOrFail($id);
            return response()->json([
                'data' => $capaianUnggulan
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Capaian unggulan tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'id_bidang' => 'required|integer',
                'id_kategori' => 'required|integer',
                'kode' => 'required|string|max:255',
                'nama' => 'required|string|max:255',
                'skor' => 'required|numeric',
            ], $this->message);

            DB::beginTransaction();

            $request->merge([
                'updated_by' => Auth::user()->id
            ]);
            $capaianUnggulan = CapaianUnggulan::findOrFail($id);
            $capaianUnggulan->update($request->all());

            DB::commit();
            return response()->json([
                'message' => 'Berhasil mengubah data capaian unggulan',
                'data' => $capaianUnggulan
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal mengubah data capaian unggulan',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $capaianUnggulan = CapaianUnggulan::findOrFail($id);
            $capaianUnggulan->delete();

            return response()->json([
                'message' => 'Data capaian unggulan berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus data capaian unggulan',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    // custom
    public function updateSkor(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'skor' => 'required|numeric',
            ], $this->message);

            $skor = $request->skor;
            $capaianUnggulan = CapaianUnggulan::findOrFail($id);
            $capaianUnggulan->skor = $skor;
            $capaianUnggulan->save();

            return response()->json([
                'message' => 'Skor capaian unggulan "' . $capaianUnggulan->nama . '" berhasil diubah'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengubah skor capaian unggulan',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}
