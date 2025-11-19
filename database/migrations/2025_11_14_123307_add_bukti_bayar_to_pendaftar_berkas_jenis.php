<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pendaftar_berkas MODIFY COLUMN jenis ENUM('IJAZAH', 'RAPOR', 'KIP', 'KKS', 'AKTA', 'KK', 'BUKTI_BAYAR', 'LAINNYA')");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pendaftar_berkas MODIFY COLUMN jenis ENUM('IJAZAH', 'RAPOR', 'KIP', 'KKS', 'AKTA', 'KK', 'LAINNYA')");
    }
};