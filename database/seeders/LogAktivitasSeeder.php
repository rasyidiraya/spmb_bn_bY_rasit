<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LogAktivitas;
use App\Models\Pendaftar\Pengguna;

class LogAktivitasSeeder extends Seeder
{
    public function run(): void
    {
        $users = Pengguna::all();
        
        foreach ($users as $user) {
            // Log login
            LogAktivitas::create([
                'user_id' => $user->id,
                'aksi' => 'login',
                'objek' => 'auth',
                'objek_data' => [
                    'ip' => '127.0.0.1',
                    'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
                    'role' => $user->role
                ],
                'waktu' => now()->subHours(rand(1, 24)),
                'ip' => '127.0.0.1'
            ]);
            
            // Log view dashboard
            LogAktivitas::create([
                'user_id' => $user->id,
                'aksi' => 'view',
                'objek' => 'dashboard',
                'objek_data' => [
                    'route' => $user->role . '.dashboard',
                    'url' => url('/' . $user->role . '/dashboard'),
                    'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)'
                ],
                'waktu' => now()->subHours(rand(1, 12)),
                'ip' => '127.0.0.1'
            ]);
        }
    }
}