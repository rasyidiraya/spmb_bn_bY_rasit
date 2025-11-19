<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekapKeuanganController extends Controller
{
    public function index(Request $request)
    {
        // Rekap per gelombang
        $rekapGelombang = DB::table('pendaftar')
            ->join('gelombang', 'pendaftar.gelombang_id', '=', 'gelombang.id')
            ->select(
                'gelombang.nama',
                'gelombang.biaya_daftar',
                DB::raw('COUNT(*) as total_pendaftar'),
                DB::raw('SUM(CASE WHEN pendaftar.status = "PAID" THEN 1 ELSE 0 END) as terbayar'),
                DB::raw('SUM(CASE WHEN pendaftar.status = "PAID" THEN gelombang.biaya_daftar ELSE 0 END) as total_pemasukan')
            )
            ->groupBy('gelombang.id', 'gelombang.nama', 'gelombang.biaya_daftar')
            ->get();

        // Rekap per jurusan
        $rekapJurusan = DB::table('pendaftar')
            ->join('jurusan', 'pendaftar.jurusan_id', '=', 'jurusan.id')
            ->join('gelombang', 'pendaftar.gelombang_id', '=', 'gelombang.id')
            ->select(
                'jurusan.nama',
                DB::raw('COUNT(*) as total_pendaftar'),
                DB::raw('SUM(CASE WHEN pendaftar.status = "PAID" THEN 1 ELSE 0 END) as terbayar'),
                DB::raw('SUM(CASE WHEN pendaftar.status = "PAID" THEN gelombang.biaya_daftar ELSE 0 END) as total_pemasukan')
            )
            ->groupBy('jurusan.id', 'jurusan.nama')
            ->get();

        // Total keseluruhan
        $totalKeseluruhan = DB::table('pendaftar')
            ->join('gelombang', 'pendaftar.gelombang_id', '=', 'gelombang.id')
            ->selectRaw('
                COUNT(*) as total_pendaftar,
                SUM(CASE WHEN pendaftar.status = "PAID" THEN 1 ELSE 0 END) as total_terbayar,
                SUM(CASE WHEN pendaftar.status = "PAID" THEN gelombang.biaya_daftar ELSE 0 END) as total_pemasukan
            ')
            ->first();

        return view('keuangan.rekap.index', compact('rekapGelombang', 'rekapJurusan', 'totalKeseluruhan'));
    }

    public function exportExcel(Request $request)
    {
        $data = DB::table('pendaftar')
            ->join('pendaftar_data_siswa', 'pendaftar.id', '=', 'pendaftar_data_siswa.pendaftar_id')
            ->join('jurusan', 'pendaftar.jurusan_id', '=', 'jurusan.id')
            ->join('gelombang', 'pendaftar.gelombang_id', '=', 'gelombang.id')
            ->leftJoin('pendaftar_berkas', function($join) {
                $join->on('pendaftar.id', '=', 'pendaftar_berkas.pendaftar_id')
                     ->where('pendaftar_berkas.jenis', '=', 'BUKTI_BAYAR');
            })
            ->select(
                'pendaftar.no_pendaftaran',
                'pendaftar_data_siswa.nama',
                'jurusan.nama as jurusan',
                'gelombang.nama as gelombang',
                'gelombang.biaya_daftar',
                'pendaftar.status',
                'pendaftar_berkas.tanggal_pembayaran'
            )
            ->where('pendaftar.status', 'PAID')
            ->get();

        $filename = 'rekap_keuangan_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['No Pendaftaran', 'Nama', 'Jurusan', 'Gelombang', 'Biaya', 'Status', 'Tgl Bayar']);
        
        foreach ($data as $row) {
            fputcsv($output, [
                $row->no_pendaftaran,
                $row->nama,
                $row->jurusan,
                $row->gelombang,
                $row->biaya_daftar,
                $row->status,
                $row->tanggal_pembayaran ? date('d/m/Y', strtotime($row->tanggal_pembayaran)) : '-'
            ]);
        }
        
        fclose($output);
        exit;
    }
}