<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JurusanSeeder extends Seeder
{
    public function run(): void
    {
        // Cek apakah sudah ada data jurusan
        if (DB::table('jurusan')->count() > 0) {
            $this->command->info('Data jurusan sudah ada, skip seeding.');
            return;
        }
        
        // Reset auto increment hanya jika tabel kosong
        DB::statement('ALTER TABLE jurusan AUTO_INCREMENT = 1;');

        $jurusanData = [
            [
                'kode' => 'PPLG',
                'nama' => 'Pengembangan Perangkat Lunak dan Gim',
                'kuota' => 100,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode' => 'DKV',
                'nama' => 'Desain Komunikasi Visual',
                'kuota' => 80,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode' => 'AKT',
                'nama' => 'Akuntansi',
                'kuota' => 120,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode' => 'PM',
                'nama' => 'Pemasaran',
                'kuota' => 100,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode' => 'ANM',
                'nama' => 'Animasi',
                'kuota' => 60,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('jurusan')->insert($jurusanData);
        $this->command->info('Data jurusan berhasil ditambahkan.');
    }
}