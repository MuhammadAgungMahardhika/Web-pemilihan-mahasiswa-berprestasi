<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Departmen;
use App\Models\Fakultas;
use App\Models\Mahasiswa;
use App\Models\Portal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $this->storePortalSession();
        $this->storeDepartmenOrFakultasSession();

        return response()->json([
            'message' => 'Berhasil Login',
            'data' => null
        ], 200);
    }

    private function storePortalSession()
    {
        $activePortal = Portal::orderBy('id', 'desc')->first();

        // Simpan data portal di session
        if ($activePortal) {
            session(['portal' => $activePortal]);
        }
    }
    private function storeDepartmenOrFakultasSession()
    {
        $user = Auth::user();
        if ($user->id_departmen) {
            $departmen = Departmen::find($user->id_departmen);
            return session(['departmen' => $departmen]);
        } elseif ($user->id_fakultas) {
            $fakultas = Fakultas::find($user->id_fakultas);
            return session(['fakultas' => $fakultas]);
        } elseif ($user->id_mahasiswa) {
            $mahasiswa = Mahasiswa::find($user->id_mahasiswa);

            $departmen = $mahasiswa->departmen;
            return session(['departmen' => $departmen]);
        } else {
            $universitas = new \stdClass();
            $universitas->nama = "Universitas Andalas";

            return session(['universitas' => $universitas]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Berhasil Logout',
            'data' => null
        ], 200);
    }
}
