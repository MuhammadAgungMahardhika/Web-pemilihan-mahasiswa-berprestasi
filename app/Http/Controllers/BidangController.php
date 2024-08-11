<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bidang;
use Illuminate\Http\JsonResponse;
use Exception;
use Yajra\DataTables\DataTables;

class BidangController extends Controller
{
    protected $message = [
        'nama.required' => 'Nama harus diisi.',
        'nama.max' => 'Nama tidak boleh lebih dari :max karakter.',
        'nama.unique' => 'Nama bidang sudah ada di database.',
    ];

    // Method untuk DataTables API
    public function getBidangData(): JsonResponse
    {
        try {
            $bidang = Bidang::get();
            return DataTables::of($bidang)
                ->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data bidang tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    public function index(): JsonResponse
    {
        try {
            $bidang = Bidang::all();
            return response()->json([
                'data' => $bidang
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data bidang tidak ditemukan',
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
                'nama' => 'required|string|max:255|unique:bidangs',
            ], $this->message);

            $bidang = Bidang::create($request->all());
            return response()->json([
                'message' => 'Berhasil menambahkan data bidang baru',
                'data' => $bidang
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan data bidang baru',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $bidang = Bidang::findOrFail($id);
            return response()->json([
                'data' => $bidang
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Bidang tidak ditemukan',
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
                'nama' => 'required|string|max:255|unique:bidangs,nama,' . $id,
            ], $this->message);

            $bidang = Bidang::findOrFail($id);
            $bidang->update($request->all());
            return response()->json([
                'message' => 'Berhasil mengubah data bidang',
                'data' => $bidang
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengubah data bidang',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $bidang = Bidang::findOrFail($id);
            $bidang->delete();
            return response()->json([
                'message' => 'Data bidang ' . $bidang->nama . ' berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus data bidang',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}
