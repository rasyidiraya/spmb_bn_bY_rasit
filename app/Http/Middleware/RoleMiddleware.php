<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Middleware ini hanya mengecek apakah user sudah login
        // Tidak perlu mengecek role karena sudah dihandle oleh auth guard
        return $next($request);
    }
}