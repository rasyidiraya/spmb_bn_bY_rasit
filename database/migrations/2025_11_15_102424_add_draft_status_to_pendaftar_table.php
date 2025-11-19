<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            $table->enum('status', ['DRAFT', 'SUBMIT', 'ADM_PASS', 'ADM_REJECT', 'PAID', 'PAYMENT_REJECT', 'PAYMENT_PENDING'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            $table->enum('status', ['SUBMIT', 'ADM_PASS', 'ADM_REJECT', 'PAID', 'PAYMENT_REJECT', 'PAYMENT_PENDING'])->change();
        });
    }
};