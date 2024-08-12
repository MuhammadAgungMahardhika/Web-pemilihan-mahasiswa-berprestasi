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

    // Datatable utusan
    public function getUtusanData(): JsonResponse
    {
        try {
            $utusan = Utusan::with(['mahasiswa', 'portal'])->get();
            return DataTables::of($utusan)
                ->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data utusan tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }
    // Datatabel utusan departemen
    public function getUtusanDataByDepartmen($idDepartmen): JsonResponse
    {
        try {
            $portal = session('portal');
            if (!$portal) {
                throw new \Exception('Portal belum dibuka');
            }
            if ($portal['status'] !== 'buka') {
                throw new \Exception('Portal saat ini tidak dibuka.');
            }

            $utusan = Utusan::with(['mahasiswa', 'portal'])
                ->where('id_portal', $portal['id'])
                ->whereHas('mahasiswa', function ($query) use ($idDepartmen) {
                    $query->where('id_departmen', $idDepartmen);
                })
                ->where('tingkat', 'departmen')
                ->where('id_portal', session('portal')->id)
                ->get();
            return DataTables::of($utusan)
                ->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data utusan tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }
    // Datatabel utusan fakultas
    public function getUtusanDataByFakultas($idFakultas): JsonResponse
    {
        try {
            $portal = session('portal');
            if (!$portal) {
                throw new \Exception('Portal belum dibuka');
            }
            if ($portal['status'] !== 'buka') {
                throw new \Exception('Portal saat ini tidak dibuka.');
            }

            $utusan = Utusan::with(['mahasiswa', 'portal'])
                ->where('id_portal', $portal['id'])
                ->whereHas('mahasiswa', function ($query) use ($idFakultas) {
                    $query->join('departmens', 'mahasiswas.id_departmen', '=', 'departmens.id')
                        ->where('departmens.id_fakultas', $idFakultas);
                })
                ->where('tingkat', 'fakultas')
                ->where('id_portal', session('portal')->id)
                ->get();
            return DataTables::of($utusan)
                ->make(true);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data utusan tidak ditemukan',
                'data' => $e->getMessage()
            ], 404);
        }
    }
    public function getUtusanDataByUniversitas(): JsonResponse
    {
        try {
            $portal = session('portal');
            if (!$portal) {
                throw new \Exception('Portal belum dibuka');
            }
            if ($portal['status'] !== 'buka') {
                throw new \Exception('Portal saat ini tidak dibuka.');
            }

            $utusan = Utusan::where('utusans.id_portal', $portal['id'])
                ->where('tingkat', 'universitas')
                ->join('mahasiswas', 'utusans.id_mahasiswa', '=', 'mahasiswas.id')
                ->join('departmens', 'mahasiswas.id_departmen', '=', 'departmens.id')
                ->join('fakultas', 'departmens.id_fakultas', '=', 'fakultas.id')
                ->select(
                    'utusans.*',
                    'mahasiswas.nim as nim_mahasiswa',
                    'mahasiswas.nama as nama_mahasiswa',
                    'departmens.nama_departmen',
                    'fakultas.nama_fakultas'
                )
                ->get();
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
            // Validasi awal request
            $request->validate([
                'id_mahasiswa' => 'required|exists:mahasiswas,id',
                'tingkat' => 'required|in:departmen,fakultas,universitas',
                'total_skor' => 'required|integer|min:0',
                'tanggal_utus_departmen' => 'nullable|date',
                'tanggal_utus_fakultas' => 'nullable|date',
                'tanggal_utus_universitas' => 'nullable|date',
            ], $this->message);

            $portal = session('portal');

            if (!$portal) {
                throw new \Exception('Portal belum dibuka');
            }

            if ($portal['status'] !== 'buka') {
                throw new \Exception('Portal saat ini tidak dibuka.');
            }

            $data = $request->all();
            $data['id_portal'] = $portal['id'];

            // Set tanggal utus departmen jika tingkat adalah "departmen"
            if ($data['tingkat'] === 'departmen') {
                $data['tanggal_utus_departmen'] = now();
            }

            // Tambahkan informasi created_by ke dalam data
            $data['created_by'] = Auth::user()->id;

            // Simpan data utusan
            $utusan = Utusan::create($data);

            return response()->json([
                'message' => 'Berhasil menambahkan data utusan baru',
                'data' => $utusan
            ], 201);
        } catch (\Exception $e) {
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

    public function updateTingkat(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'tingkat' => 'required|in:departmen,fakultas,universitas',
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
                'message' => 'Berhasil mengubah status utusan',
                'data' => $utusan
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengubah status utusan',
                'data' => $e->getMessage()
            ], 500);
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
