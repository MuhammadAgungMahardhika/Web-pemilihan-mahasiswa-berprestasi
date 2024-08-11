<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utusan; // Ganti dengan model Utusan
use Illuminate\Http\JsonResponse;
use Exception;
use Yajra\DataTables\DataTables;

class UtusanController extends Controller
{
    protected $message = [
        'id_mahasiswa.required' => 'Mahasiswa harus dipilih.',
        'status.required' => 'Status harus diisi.',
        'tanggal_utus_departmen.date' => 'Tanggal utus departmen harus berupa tanggal.',
        'tanggal_utus_fakultas.date' => 'Tanggal utus fakultas harus berupa tanggal.',
        'tanggal_utus_universitas.date' => 'Tanggal utus universitas harus berupa tanggal.',
    ];

    // Method untuk DataTables API
    public function getUtusanData(): JsonResponse
    {
        try {
            $utusan = Utusan::get();
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

    public function create(): JsonResponse
    {
        return response()->json(['message' => 'Create form not available in API']);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'id_mahasiswa' => 'required|integer',
                'status' => 'required|in:departmen,fakultas,universitas',
                'tanggal_utus_departmen' => 'nullable|date',
                'tanggal_utus_fakultas' => 'nullable|date',
                'tanggal_utus_universitas' => 'nullable|date',
            ], $this->message);

            $utusan = Utusan::create($request->all());

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
                'message' => 'Utusan tidak ditemukan',
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
                'id_mahasiswa' => 'required|integer',
                'status' => 'required|in:departmen,fakultas,universitas',
                'tanggal_utus_departmen' => 'nullable|date',
                'tanggal_utus_fakultas' => 'nullable|date',
                'tanggal_utus_universitas' => 'nullable|date',
            ], $this->message);

            $utusan = Utusan::findOrFail($id);
            $utusan->update($request->all());
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
