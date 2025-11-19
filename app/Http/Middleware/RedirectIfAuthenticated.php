<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                // Redirect berdasarkan role
                return match($user->role) {
                    'pendaftar' => redirect()->route('pendaftar.dashboard'),
                    default => redirect()->route('pendaftar.dashboard')
                };
            }
        }

        return $next($request);
    }
}