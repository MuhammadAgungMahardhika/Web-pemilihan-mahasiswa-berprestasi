<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departmen;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class DepartmenController extends Controller
{
    protected $message = [
        'nama_departmen.required' => 'Nama departmen harus diisi.',
        'kepala_departmen.required' => 'Kepala departmen harus diisi',
        'id_fakultas.required' => 'Fakultas harus dipilih',
        'email.unique' => 'Email sudah terdaftar.',
    ];

    // ambil data departmen 
    public function getDepartmenData(): JsonResponse
    {
        try {
            $departmen = Departmen::get();
            return DataTables::of($departmen)
                ->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data departmen tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }
    // ambil data departmen berdasarkan fakultas admin
    public function getDepartmenDataByFakultas($idFakultas): JsonResponse
    {
        try {
            $departmen = Departmen::where('id_fakultas', $idFakultas)->get();
            return DataTables::of($departmen)
                ->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data departmen tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    public function index(): JsonResponse
    {
        try {
            $departmen = Departmen::all();
            return response()->json([
                'data' => $departmen
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data departmen tidak ditemukan',
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
                'nama_departmen' => 'required|string|max:255',
                'kepala_departmen' => 'required|string|max:255',
                'id_fakultas' => 'required|integer',
                'contact_number' => 'nullable|string|max:15',
                'email' => 'nullable|string|email|max:255|unique:departmens',
            ], $this->message);

            $departmen = Departmen::create($request->all());

            return response()->json([
                'message' => 'Berhasil menambahkan data departmen baru',
                'data' => $departmen
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan data departmen baru',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $departmen = Departmen::findOrFail($id);
            return response()->json([
                'data' => $departmen
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Departmen tidak ditemukan',
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
                'nama_departmen' => 'required|string|max:255',
                'kepala_departmen' => 'required|string|max:255',
                'id_fakultas' => 'required|integer',
                'contact_number' => 'nullable|string|max:15',
                'email' => 'nullable|string|email|max:255|unique:departmens,email,' . $id,
            ], $this->message);

            $departmen = Departmen::findOrFail($id);
            $departmen->update($request->all());
            return response()->json([
                'message' => 'Berhasil mengubah data departmen',
                'data' => $departmen
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengubah data departmen',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $departmen = Departmen::findOrFail($id);
            $departmen->delete();
            return response()->json([
                'message' => 'Data departmen ' . $departmen->nama_departmen . ' berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus data departmen',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}
