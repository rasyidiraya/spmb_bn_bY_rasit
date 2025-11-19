<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Log aktivitas setelah request selesai
        if (Auth::guard('pengguna')->check()) {
            $this->logActivity($request);
        }
        
        return $response;
    }
    
    private function logActivity(Request $request)
    {
        $user = Auth::guard('pengguna')->user();
        
        // Tentukan aksi berdasarkan route dan method
        $aksi = $this->determineAction($request);
        $objek = $this->determineObject($request);
        
        if ($aksi && $objek) {
            LogAktivitas::create([
                'user_id' => $user->id,
                'aksi' => $aksi,
                'objek' => $objek,
                'objek_data' => $this->getObjectData($request),
                'waktu' => now(),
                'ip' => $request->ip()
            ]);
        }
    }
    
    private function determineAction(Request $request)
    {
        $method = $request->method();
        $route = $request->route()->getName();
        
        if (str_contains($route, 'login')) return 'login';
        if (str_contains($route, 'logout')) return 'logout';
        if ($method === 'POST' && !str_contains($route, 'login')) return 'create';
        if ($method === 'PUT' || $method === 'PATCH') return 'update';
        if ($method === 'DELETE') return 'delete';
        if ($method === 'GET') return 'view';
        
        return null;
    }
    
    private function determineObject(Request $request)
    {
        $route = $request->route()->getName();
        
        if (str_contains($route, 'login') || str_contains($route, 'logout')) return 'auth';
        if (str_contains($route, 'pendaftaran')) return 'pendaftaran';
        if (str_contains($route, 'berkas')) return 'berkas';
        if (str_contains($route, 'pembayaran')) return 'pembayaran';
        if (str_contains($route, 'jurusan')) return 'jurusan';
        if (str_contains($route, 'gelombang')) return 'gelombang';
        
        return 'system';
    }
    
    private function getObjectData(Request $request)
    {
        return [
            'route' => $request->route()->getName(),
            'url' => $request->url(),
            'user_agent' => $request->userAgent()
        ];
    }
}