<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pendaftar MODIFY COLUMN status ENUM('SUBMIT', 'ADM_PASS', 'ADM_REJECT', 'PAID', 'PAYMENT_REJECT')");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pendaftar MODIFY COLUMN status ENUM('SUBMIT', 'ADM_PASS', 'ADM_REJECT', 'PAID')");
    }
};