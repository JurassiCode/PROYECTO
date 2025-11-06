<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // 👈 IMPORTANTE

class EsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user(); // null si no hay sesión

        if (! $user || $user->rol !== 'admin') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'No autorizado'], 403);
            }
            return redirect()
                ->route('home')
                ->withErrors(['auth' => 'No tenés permisos para acceder a /admin.']);
        }

        return $next($request);
    }
}
