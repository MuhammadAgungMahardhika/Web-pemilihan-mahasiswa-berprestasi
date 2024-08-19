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

    protected $message = [
        'periode.required' => 'Periode wajib diisi',
        'tanggal_tutup_departmen.required' => 'Tanggal Tutup Departemen wajib diisi',
        'tanggal_tutup_fakultas.required' => 'Tanggal Tutup Fakultas wajib diisi',
        'tanggal_tutup_fakultas.date' => 'Format Tanggal Tidak Valid',
        'tanggal_tutup_departmen.date' => 'Format Tanggal Tidak Valid',
        'periode.unique' => 'Periode sudah ada',
        'periode.max' => 'Tahun Periode Tidak Valid',
        'periode.min' => 'Tahun Periode Tidak Valid',
    ];

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
            // Validasi awal
            $request->validate([
                'periode' => 'required|number|min:4|max:4|unique:portals',
                'tanggal_tutup_departmen' => 'required|date',
                'tanggal_tutup_fakultas' => 'required|date',
                'status' => 'required|in:tutup,buka',
            ], $this->message);

            // Ambil nilai periode maksimal yang sudah ada di database
            $maxPeriode = Portal::max('periode');

            // Validasi periode baru agar tidak lebih kecil dari periode maksimal yang ada
            if ($maxPeriode && $request->periode < $maxPeriode) {
                throw new \Exception('Periode tidak boleh lebih kecil dari periode terakhir yang sudah ada (' . $maxPeriode . ')');
            }

            // Tambahkan informasi created_by ke dalam request
            $request->merge([
                'created_by' => Auth::user()->id
            ]);

            // Simpan data portal
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
            $request->validate([
                'periode' => 'required|number|min:4|max:4|unique:portals,periode,' . $id,
                'tanggal_tutup_departmen' => 'required|date',
                'tanggal_tutup_fakultas' => 'required|date',
                'status' => 'required|in:tutup,buka',
            ], $this->message);

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
