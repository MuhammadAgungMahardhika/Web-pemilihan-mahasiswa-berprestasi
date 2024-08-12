<?php

namespace App\Http\Controllers;

use App\Models\Portal;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class PortalController extends Controller
{


    // Method untuk DataTables API
    public function getPortalData(): JsonResponse
    {
        try {
            $portal = Portal::get();
            return DataTables::of($portal)
                ->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data Portal tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    public function index(): JsonResponse
    {
        try {
            $portal = Portal::all();
            return response()->json([
                'data' => $portal
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data Portal tidak ditemukan',
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

            $request->merge([
                'created_by' => Auth::user()->id
            ]);
            $portal = Portal::create($request->all());
            return response()->json([
                'message' => 'Berhasil menambahkan data Portal baru',
                'data' => $portal
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan data Portal baru',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $portal = Portal::findOrFail($id);
            return response()->json([
                'data' => $portal
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Portal tidak ditemukan',
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

            $request->merge([
                'updated_by' => Auth::user()->id
            ]);
            $portal = Portal::findOrFail($id);
            $portal->update($request->all());
            return response()->json([
                'message' => 'Berhasil mengubah data Portal',
                'data' => $portal
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengubah data Portal',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $portal = Portal::findOrFail($id);
            $portal->delete();
            return response()->json([
                'message' => 'Data Portal ' . $portal->nama . ' berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus data Portal',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}
