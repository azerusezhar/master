<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Role yang diizinkan (bisa multiple)
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Cek apakah user sudah login
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah role user termasuk dalam role yang diizinkan
        $userRole = auth()->user()->role;

        if (!in_array($userRole, $roles)) {
            // Redirect berdasarkan role user
            return match ($userRole) {
                'admin' => redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.'),
                'petugas' => redirect()->route('petugas.dashboard')->with('error', 'Akses ditolak.'),
                'siswa' => redirect()->route('siswa.dashboard')->with('error', 'Akses ditolak.'),
                'masyarakat' => redirect()->route('masyarakat.dashboard')->with('error', 'Akses ditolak.'),
                default => redirect('/')->with('error', 'Akses ditolak.'),
            };
        }

        return $next($request);
    }
}
