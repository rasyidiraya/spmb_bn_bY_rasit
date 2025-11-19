<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PenggunaSeeder::class,   // WAJIB - Data pengguna admin
                WilayahSeeder::class,    // WAJIB - Data wilayah untuk dropdown
            JurusanSeeder::class,    // WAJIB - Dropdown form pendaftaran
            GelombangSeeder::class,  // WAJIB - Dropdown form pendaftaran
            // PendaftarSeeder::class,  // Data dummy untuk dashboard - DIHAPUS
            // AdminSeeder::class,   // OPSIONAL - Uncomment jika butuh akun admin default
        ]);
    }
}
