<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;
use Yajra\DataTables\DataTables;

class FakultasController extends Controller
{
    protected $message = [
        'nama_fakultas.required' => 'Nama fakultas harus diisi.',
        'dekan.required' => 'Dekan harus diisi.',
        'email.unique' => 'Email sudah terdaftar.',
    ];

    // Method untuk DataTables API
    public function getFakultasData(): JsonResponse
    {
        try {
            $fakultas = Fakultas::orderBy('id', 'DESC')->get();
            return DataTables::of($fakultas)
                ->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data fakultas tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    public function index(): JsonResponse
    {
        try {
            $fakultas = Fakultas::all();
            return response()->json([
                'data' => $fakultas
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data fakultas tidak ditemukan',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'nama_fakultas' => 'required|string|max:255',
                'dekan' => 'required|string|max:255',
                'contact_number' => 'nullable|string|max:15',
                'email' => 'nullable|string|email|max:255|unique:fakultas',
            ], $this->message);

            $fakultas = Fakultas::create($request->all());

            return response()->json([
                'message' => 'Berhasil menambahkan fakultas baru',
                'data' => $fakultas
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan fakultas baru',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $fakultas = Fakultas::findOrFail($id);
            return response()->json([
                'data' => $fakultas
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Fakultas tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'nama_fakultas' => 'required|string|max:255',
                'dekan' => 'required|string|max:255',
                'contact_number' => 'nullable|string|max:15',
                'email' => 'nullable|string|email|max:255|unique:fakultas,email,' . $id,
            ], $this->message);

            $fakultas = Fakultas::findOrFail($id);
            $fakultas->update($request->all());

            return response()->json([
                'message' => 'Berhasil mengubah data fakultas',
                'data' => $fakultas
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengubah data fakultas',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $fakultas = Fakultas::findOrFail($id);
            $fakultas->delete();

            return response()->json([
                'message' => 'Data fakultas ' . $fakultas->nama_fakultas . ' berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus data fakultas',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}
