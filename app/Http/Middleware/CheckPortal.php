<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPortal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $portal = session('portal');

        // Cek apakah portal ada
        if ($portal) {
            // Cek status portal
            if ($portal['status'] !== 'buka') {
                return redirect()->route('dashboard')->with('portalSession', 'Portal saat ini tidak dibuka.');
            }
            // Tentukan tanggal tutup yang sesuai dengan session fakultas atau departmen
            $tanggalTutupPortal = null;
            if (session('fakultas')) {
                $tanggalTutupPortal = $portal->tanggal_tutup_fakultas;
            } elseif (session('departmen')) {
                $tanggalTutupPortal = $portal->tanggal_tutup_departmen;
            }

            // Periksa apakah tanggal tutup melebihi tanggal sekarang
            if ($tanggalTutupPortal && now()->greaterThan($tanggalTutupPortal)) {
                return redirect()->route('dashboard')
                    ->with('portalSession', 'Portal sudah ditutup.');
            }
        } else {
            return redirect()->route('dashboard')->with('portalSession', 'Portal belum dibuka');
        }
        return $next($request);
    }
}
