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
        if (!$portal) {
            return redirect()->route('dashboard')->with('portalSession', 'Portal belum dibuka');
        }

        // Cek status portal
        if ($portal['status'] !== 'buka') {
            return redirect()->route('dashboard')->with('portalSession', 'Portal saat ini tidak dibuka.');
        }

        return $next($request);
    }
}
