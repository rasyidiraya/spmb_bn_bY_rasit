<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GelombangSeeder extends Seeder
{
    public function run(): void
    {
        // Cek apakah sudah ada data gelombang dengan tanggal real-time
        $currentYear = date('Y');
        $existingGelombang = DB::table('gelombang')
            ->where('tgl_mulai', 'like', $currentYear . '%')
            ->exists();
            
        if ($existingGelombang) {
            $this->command->info('Data gelombang real-time sudah ada, skip seeding.');
            return;
        }
        
        // Hapus data gelombang lama dengan aman
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('gelombang')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        
        $gelombangData = [
            [
                'nama' => 'Gelombang 1',
                'tahun' => $currentYear,
                'tgl_mulai' => $currentYear . '-03-01', // 1 Maret
                'tgl_selesai' => $currentYear . '-05-31', // 31 Mei
                'biaya_daftar' => 150000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Gelombang 2',
                'tahun' => $currentYear,
                'tgl_mulai' => $currentYear . '-06-01', // 1 Juni
                'tgl_selesai' => $currentYear . '-08-31', // 31 Agustus
                'biaya_daftar' => 175000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Gelombang 3',
                'tahun' => $currentYear,
                'tgl_mulai' => $currentYear . '-09-01', // 1 September 2025
                'tgl_selesai' => $currentYear . '-11-30', // 30 November 2025
                'biaya_daftar' => 200000,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        
        DB::table('gelombang')->insert($gelombangData);
        
        $this->command->info("Gelombang real-time berhasil dibuat:");
        $this->command->info("- Gelombang 1: Maret - Mei {$currentYear} (Rp 150.000)");
        $this->command->info("- Gelombang 2: Juni - Agustus {$currentYear} (Rp 175.000)");
        $this->command->info("- Gelombang 3: September - November {$currentYear} (Rp 200.000)");
    }
}