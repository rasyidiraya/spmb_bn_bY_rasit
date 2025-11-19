<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik dashboard
        $totalPendaftar = DB::table('pendaftar')->count();
        $menungguVerifikasi = DB::table('pendaftar')->whereIn('status', ['ADM_PASS', 'PAYMENT_PENDING'])->count();
        $sudahBayar = DB::table('pendaftar')->where('status', 'PAID')->count();
        
        $totalPemasukan = DB::table('pendaftar')
            ->join('gelombang', 'pendaftar.gelombang_id', '=', 'gelombang.id')
            ->where('pendaftar.status', 'PAID')
            ->sum('gelombang.biaya_daftar') ?? 0;

        return view('keuangan.dashboard', compact(
            'totalPendaftar',
            'menungguVerifikasi', 
            'sudahBayar',
            'totalPemasukan'
        ));
    }
}