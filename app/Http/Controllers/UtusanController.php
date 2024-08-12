<?php

namespace App\Http\Controllers;

use App\Models\Utusan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class UtusanController extends Controller
{
    protected $message = [
        'id_mahasiswa.required' => 'Mahasiswa harus diisi.',
        'tingkat.required' => 'Tingkat harus diisi.',
        'total_skor.required' => 'Total skor harus diisi.',
    ];

    // Method untuk DataTables API
    public function getUtusanData(): JsonResponse
    {
        try {
            $utusan = Utusan::with('mahasiswa')->get();
            return DataTables::of($utusan)
                ->make(true);
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
            $request->validate([
                'id_mahasiswa' => 'required|exists:mahasiswas,id',
                'tingkat' => 'required|in:departmen,fakultas,universitas',
                'total_skor' => 'required|integer|min:0',
                'tanggal_utus_departmen' => 'nullable|date',
                'tanggal_utus_fakultas' => 'nullable|date',
                'tanggal_utus_universitas' => 'nullable|date',
            ], $this->message);

            $data = $request->all();
            if ($data['tingkat'] === 'departmen') {
                $data['tanggal_utus_departmen'] = now();
            }
            $data['created_by'] = Auth::user()->id;
            $utusan = Utusan::create($data);
            return response()->json([
                'message' => 'Berhasil menambahkan data utusan baru',
                'data' => $utusan
            ], 201);
        } catch (Exception $e) {
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

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'id_mahasiswa' => 'required|exists:mahasiswas,id',
                'tingkat' => 'required|in:departmen,fakultas,universitas',
                'total_skor' => 'required|integer|min:0',
                'tanggal_utus_departmen' => 'nullable|date',
                'tanggal_utus_fakultas' => 'nullable|date',
                'tanggal_utus_universitas' => 'nullable|date',
            ], $this->message);

            $data = $request->all();

            if ($data['tingkat'] === 'universitas') {
                $data['tanggal_utus_universitas'] = now();
            } else if ($data['tingkat'] === 'fakultas') {
                $data['tanggal_utus_fakultas'] = now();
            } else {
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
