<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar\Pendaftar;
use App\Models\Pendaftar\Jurusan;
use App\Models\Pendaftar\PendaftarAsalSekolah;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // KPI Ringkas
            $totalPendaftar = Pendaftar::count();
            $totalKuota = Jurusan::sum('kuota') ?? 0;
            $rasioTerverifikasi = Pendaftar::whereIn('pendaftar.status', ['ADM_PASS', 'PAID'])->count();
            $persentaseKuota = $totalKuota > 0 ? round(($totalPendaftar / $totalKuota) * 100, 1) : 0;
            
            // Tren Harian (7 hari terakhir) - buat data untuk semua 7 hari
            $trenHarian = collect();
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->toDateString();
                $count = Pendaftar::whereDate('created_at', $date)->count();
                $trenHarian->push([
                    'tanggal' => $date,
                    'jumlah' => $count
                ]);
            }

            // Komposisi per Jurusan
            $komposisiJurusan = Jurusan::withCount('pendaftar')
                ->get()
                ->map(function($item) {
                    return [
                        'nama' => $item->nama,
                        'pendaftar' => $item->pendaftar_count,
                        'kuota' => $item->kuota,
                        'persentase' => $item->kuota > 0 ? round(($item->pendaftar_count / $item->kuota) * 100, 1) : 0
                    ];
                });

            // Status Verifikasi - pastikan semua status ada
            $statusCounts = Pendaftar::select('pendaftar.status', DB::raw('COUNT(*) as jumlah'))
                ->groupBy('pendaftar.status')
                ->pluck('jumlah', 'pendaftar.status');
                
            $statusLabels = [
                'DRAFT' => 'Draft',
                'SUBMIT' => 'Menunggu Verifikasi',
                'ADM_PASS' => 'Lulus Administrasi', 
                'ADM_REJECT' => 'Ditolak Administrasi',
                'PAYMENT_PENDING' => 'Menunggu Pembayaran',
                'PAYMENT_REJECT' => 'Pembayaran Ditolak',
                'PAID' => 'Sudah Bayar'
            ];
            
            $statusVerifikasi = collect($statusLabels)->mapWithKeys(function($label, $status) use ($statusCounts) {
                return [$label => $statusCounts->get($status, 0)];
            });

            // Komposisi Asal Sekolah (Top 5)
            $asalSekolah = PendaftarAsalSekolah::select('nama_sekolah', DB::raw('COUNT(*) as jumlah'))
                ->whereNotNull('nama_sekolah')
                ->groupBy('nama_sekolah')
                ->orderByDesc('jumlah')
                ->limit(5)
                ->get();

            // Komposisi Wilayah
            $wilayah = PendaftarAsalSekolah::select('kabupaten as wilayah', DB::raw('COUNT(*) as jumlah'))
                ->whereNotNull('kabupaten')
                ->groupBy('kabupaten')
                ->orderByDesc('jumlah')
                ->limit(5)
                ->get();

            return view('kepsek.dashboard', compact(
                'totalPendaftar', 'totalKuota', 'rasioTerverifikasi', 'persentaseKuota',
                'trenHarian', 'komposisiJurusan', 'statusVerifikasi', 'asalSekolah', 'wilayah'
            ));
        } catch (\Exception $e) {
            \Log::error('Error in Kepsek Dashboard: ' . $e->getMessage());
            
            // Return with default empty data if error occurs
            return view('kepsek.dashboard', [
                'totalPendaftar' => 0,
                'totalKuota' => 0,
                'rasioTerverifikasi' => 0,
                'persentaseKuota' => 0,
                'trenHarian' => collect(),
                'komposisiJurusan' => collect(),
                'statusVerifikasi' => collect(),
                'asalSekolah' => collect(),
                'wilayah' => collect()
            ]);
        }
    }
}