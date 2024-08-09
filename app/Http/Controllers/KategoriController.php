<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Http\JsonResponse;
use Exception;
use Yajra\DataTables\DataTables;

class KategoriController extends Controller
{
    protected $message = [
        'nama.required' => 'Nama kategori harus diisi.',
        'nama.max' => 'Nama kategori tidak boleh lebih dari :max karakter.',
        'nama.unique' => 'Nama kategori sudah ada di database.',
        'jenis.required' => 'Jenis kategori harus dipilih.',
        'jenis.in' => 'Jenis kategori tidak valid.',
    ];

    // Method untuk DataTables API
    public function getKategoriData(): JsonResponse
    {
        try {
            $kategori = Kategori::orderBy('id', 'DESC');
            return DataTables::of($kategori)
                ->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data kategori tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    public function index(): JsonResponse
    {
        try {
            $kategori = Kategori::all();
            return response()->json([
                'data' => $kategori
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data kategori tidak ditemukan',
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
                'nama' => 'required|string|max:255|unique:kategoris',
                'jenis' => 'required|in:A,B,C,D,E',
            ], $this->message);

            $kategori = Kategori::create($request->all());
            return response()->json([
                'message' => 'Berhasil menambahkan data kategori baru',
                'data' => $kategori
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan data kategori baru',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $kategori = Kategori::findOrFail($id);
            return response()->json([
                'data' => $kategori
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Kategori tidak ditemukan',
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
                'nama' => 'required|string|max:255|unique:kategoris,nama,' . $id,
                'jenis' => 'required|in:A,B,C,D,E',
            ], $this->message);

            $kategori = Kategori::findOrFail($id);
            $kategori->update($request->all());
            return response()->json([
                'message' => 'Berhasil mengubah data kategori',
                'data' => $kategori
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengubah data kategori',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $kategori = Kategori::findOrFail($id);
            $kategori->delete();
            return response()->json([
                'message' => 'Data kategori ' . $kategori->nama . ' berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus data kategori',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}
