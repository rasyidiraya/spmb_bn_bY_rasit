<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pendaftar\Pengguna;
use App\Models\LogAktivitas;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = Pengguna::where('email', $credentials['email'])->first();
        
        if ($user && password_verify($credentials['password'], $user->password_hash)) {
            // Cek apakah user aktif
            if (!$user->aktif) {
                return back()->withErrors(['email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.']);
            }
            // Login dengan guard sesuai role
            $guard = match($user->role) {
                'pendaftar' => 'pengguna',
                'admin' => 'admin',
                'verifikator_adm' => 'verifikator',
                'keuangan' => 'keuangan',
                'kepsek' => 'kepsek',
                default => 'pengguna'
            };
            
            Auth::guard($guard)->login($user);
            
            // Log aktivitas login
            LogAktivitas::create([
                'user_id' => $user->id,
                'aksi' => 'login',
                'objek' => 'auth',
                'objek_data' => [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'role' => $user->role
                ],
                'waktu' => now(),
                'ip' => $request->ip()
            ]);
            
            // Route berdasarkan role
            return match($user->role) {
                'pendaftar' => redirect()->route('pendaftar.dashboard'),
                'admin' => redirect()->route('admin.dashboard'),
                'verifikator_adm' => redirect()->route('verifikator.index'),
                'keuangan' => redirect()->route('keuangan.dashboard'),
                'kepsek' => redirect()->route('kepsek.dashboard'),
                default => redirect()->route('pendaftar.dashboard')
            };
        }

        return back()->withErrors(['email' => 'Login gagal']);
    }

    public function showRegistrasi()
    {
        return view('auth.register');
    }

    public function registrasi(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pengguna,email',
            'hp' => 'required|string|max:20',
            'password' => 'required|min:6|confirmed'
        ]);

        Pengguna::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'hp' => $request->hp,
            'password_hash' => Hash::make($request->password),
            'role' => 'pendaftar',
            'aktif' => true
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function logout(Request $request)
    {
        // Cek semua guard dan logout dari yang aktif
        $guards = ['pengguna', 'admin', 'verifikator', 'keuangan', 'kepsek'];
        $user = null;
        
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                Auth::guard($guard)->logout();
                break;
            }
        }
        
        if ($user) {
            // Log aktivitas logout
            LogAktivitas::create([
                'user_id' => $user->id,
                'aksi' => 'logout',
                'objek' => 'auth',
                'objek_data' => [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'role' => $user->role
                ],
                'waktu' => now(),
                'ip' => $request->ip()
            ]);
        }
        
        // Hapus session dan invalidate
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('logout', true);
    }
}