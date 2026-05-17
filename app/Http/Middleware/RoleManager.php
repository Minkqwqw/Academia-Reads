<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleManager
{
    /**
     * Handle an incoming request.
     * Logika: Memeriksa apakah user sudah login DAN memiliki role yang sesuai.
     * Jika tidak, sistem akan menghentikan request (Abort 403).
     */
    public function handle(
        Request $request,
        Closure $next,
        string $role,
    ): Response {
        // Proteksi Multi-Actor: Memastikan role user sesuai dengan requirement route
        if (!Auth::check() || Auth::user()->role !== $role) {
            abort(403, "Unauthorized access.");
        }

        return $next($request);
    }
}
