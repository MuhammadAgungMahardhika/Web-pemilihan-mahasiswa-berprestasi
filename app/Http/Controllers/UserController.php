<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Exception;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    protected $message = [
        'username.required' => 'Username harus diisi.',
        'username.unique' => 'Username sudah terdaftar.',
        'password.required' => 'Password harus diisi.',
        'name.required' => 'Nama harus diisi.',
        'id_role.required' => 'Role harus dipilih.',
        'status.required' => 'Status harus diisi.',
    ];

    // Method untuk ambil data user berdasarkan fakultas
    public function getUserDataByFakultas(): JsonResponse
    {
        try {

            $user = User::with(['fakultas'])
                ->where('id_role', 3)
                ->where('id_fakultas', '!=', null)
                ->get();
            return DataTables::of($user)
                ->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data user tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    // Method untuk DataTables API
    public function getUserDataByDepartmen(): JsonResponse
    {
        try {
            $idFakultas = Auth::user()->id_fakultas;
            $user = User::with(['departmen'])
                ->where('id_role', 2)
                ->where('id_departmen', '!=', null)
                ->whereHas('departmen', function ($query) use ($idFakultas) {
                    $query->where('id_fakultas', $idFakultas);
                })
                ->orderBy('id', 'desc');
            return DataTables::of($user)
                ->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data user tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    public function index(): JsonResponse
    {
        try {
            $user = User::all();
            return response()->json([
                'data' => $user
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data user tidak ditemukan',
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
                'username' => 'required|string|max:255|unique:users',
                'password' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'id_role' => 'required|integer',
                'id_mahasiswa' => 'nullable|integer',
                'id_departmen' => 'nullable|integer',
                'id_fakultas' => 'nullable|integer',
                'foto_url' => 'nullable|string|max:255',
                'status' => 'required|in:aktif,nonaktif',
            ], $this->message);

            $request->merge([
                'created_by' => Auth::user()->id
            ]);
            $user = User::create($request->all());

            return response()->json([
                'message' => 'Berhasil menambahkan user baru',
                'data' => $user
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan user baru',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            return response()->json([
                'data' => $user
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'User tidak ditemukan',
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
                'username' => 'required|string|max:255|unique:users,username,' . $id,
                'password' => 'nullable|string|max:255',
                'name' => 'required|string|max:255',
                'id_mahasiswa' => 'nullable|integer',
                'id_departmen' => 'nullable|integer',
                'id_fakultas' => 'nullable|integer',
                'foto_url' => 'nullable|string|max:255',
                'status' => 'required|in:aktif,nonaktif',
            ], $this->message);

            $request->merge([
                'updated_by' => Auth::user()->id
            ]);
            $user = User::findOrFail($id);
            $user->update($request->all());
            return response()->json([
                'message' => 'Berhasil mengubah data user',
                'data' => $user
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengubah data user',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json([
                'message' => 'Data user ' . $user->username . ' berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus data user',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function activateUser(Request $request): JsonResponse
    {
        try {
            $id = $request->id;
            $user = User::findOrFail($id);
            $user->status = "aktif";
            $user->save();
            return response()->json([
                'message' => 'Akun' . $user->name . ' berhasil diaktifkan',
                'data' => null
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal aktivasi akun',
                'data' => $e->getMessage()
            ], 500);
        }
    }
    public function deactivateUser(Request $request): JsonResponse
    {
        try {
            $id = $request->id;
            $user = User::findOrFail($id);
            $user->status = "nonaktif";
            $user->save();
            return response()->json([
                'message' => 'Akun ' . $user->name . ' berhasil dinonaktifkan',
                'data' => null
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal nonaktifkan akun ',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}
