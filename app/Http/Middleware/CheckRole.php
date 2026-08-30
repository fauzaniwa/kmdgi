<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Jika pengguna belum login, atau role pengguna tidak sama dengan parameter role di route
        if (!auth()->check() || auth()->user()->role !== $role) {
            abort(403, 'Akses tidak diizinkan.');
        }

        return $next($request);
    }
}