<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pendaftar\Pendaftar;
use App\Models\Pendaftar\Jurusan;
use App\Models\Pendaftar\Gelombang;
use Carbon\Carbon;

class PendaftarSeeder extends Seeder
{
    public function run()
    {
        $jurusan = Jurusan::all();
        $gelombang = Gelombang::all();
        
        if ($jurusan->isEmpty() || $gelombang->isEmpty()) {
            return;
        }

        // Cek apakah ada user di tabel pengguna
        $userIds = \DB::table('pengguna')->pluck('id')->toArray();
        if (empty($userIds)) {
            echo "Tidak ada data pengguna, skip seeding pendaftar.\n";
            return;
        }

        // Buat data pendaftar untuk 7 hari terakhir
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = rand(3, 8); // Random 3-8 pendaftar per hari
            
            for ($j = 0; $j < $count; $j++) {
                $status = ['SUBMIT', 'ADM_PASS', 'ADM_REJECT', 'PAID'][rand(0, 3)];
                
                Pendaftar::create([
                    'user_id' => $userIds[array_rand($userIds)], // Random existing user ID
                    'jurusan_id' => $jurusan->random()->id,
                    'gelombang_id' => $gelombang->random()->id,
                    'no_pendaftaran' => 'SPMB' . $date->format('Y') . str_pad(($i * 10) + $j + 1, 4, '0', STR_PAD_LEFT),
                    'status' => $status,
                    'tanggal_daftar' => $date->toDateString(),
                    'created_at' => $date,
                    'updated_at' => $date->copy()->addHours(rand(1, 12))
                ]);
            }
        }
        
        echo "Data pendaftar berhasil ditambahkan.\n";
    }
}