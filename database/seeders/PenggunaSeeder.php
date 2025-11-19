<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pendaftar\Pengguna;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        if (Pengguna::where('role', 'admin')->count() > 0) {
            $this->command->info('Data pengguna sudah ada, skip seeding.');
            return;
        }

        $users = [
            [
                'nama' => 'Super Admin',
                'email' => 'admin@gmail.com',
                'hp' => '081234567890',
                'password_hash' => Hash::make('admin123'),
                'role' => 'admin',
                'aktif' => true,
            ],
            [
                'nama' => 'Verifikator Administrasi',
                'email' => 'verifikator@gmail.com',
                'hp' => '081234567891',
                'password_hash' => Hash::make('verif123'),
                'role' => 'verifikator_adm',
                'aktif' => true,
            ],
            [
                'nama' => 'Staff Keuangan',
                'email' => 'keuangan@gmail.com',
                'hp' => '081234567892',
                'password_hash' => Hash::make('keuangan123'),
                'role' => 'keuangan',
                'aktif' => true,
            ],
            [
                'nama' => 'Kepala Sekolah',
                'email' => 'kepsek@gmail.com',
                'hp' => '081234567893',
                'password_hash' => Hash::make('kepsek123'),
                'role' => 'kepsek',
                'aktif' => true,
            ],
        ];

        foreach ($users as $user) {
            Pengguna::create($user);
        }
        
        $this->command->info('Data pengguna berhasil ditambahkan.');
    }
}