<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserActive
{
    public function handle(Request $request, Closure $next)
    {
        $guards = ['pengguna', 'admin', 'verifikator', 'keuangan', 'kepsek'];
        
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                if (!$user->aktif) {
                    Auth::guard($guard)->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    
                    return redirect()->route('login')->withErrors([
                        'email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.'
                    ]);
                }
                break;
            }
        }
        
        return $next($request);
    }
}