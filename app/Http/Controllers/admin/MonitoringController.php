<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
    public function berkas(Request $request)
    {
        $query = DB::table('pendaftar')
            ->join('pendaftar_data_siswa', 'pendaftar.id', '=', 'pendaftar_data_siswa.pendaftar_id')
            ->join('jurusan', 'pendaftar.jurusan_id', '=', 'jurusan.id')
            ->join('gelombang', 'pendaftar.gelombang_id', '=', 'gelombang.id')
            ->select(
                'pendaftar.id',
                'pendaftar.no_pendaftaran',
                'pendaftar_data_siswa.nama as nama_siswa',
                'jurusan.nama as nama_jurusan',
                'gelombang.nama as nama_gelombang',
                'pendaftar.status',
                'pendaftar.created_at'
            );

        if ($request->status) {
            $query->where('pendaftar.status', $request->status);
        }

        $pendaftar = $query->paginate(20);
        
        // Get berkas count for each pendaftar
        foreach ($pendaftar as $item) {
            $item->jumlah_berkas = DB::table('pendaftar_berkas')
                ->selectRaw('COUNT(DISTINCT jenis) as count')
                ->where('pendaftar_id', $item->id)
                ->whereIn('jenis', ['IJAZAH', 'RAPOR', 'KIP', 'AKTA'])
                ->whereNotNull('url')
                ->where('url', '!=', '')
                ->value('count') ?? 0;
        }
        
        return view('admin.monitoring.berkas', compact('pendaftar'));
    }

    public function export()
    {
        $data = DB::table('pendaftar')
            ->join('pendaftar_data_siswa', 'pendaftar.id', '=', 'pendaftar_data_siswa.pendaftar_id')
            ->join('jurusan', 'pendaftar.jurusan_id', '=', 'jurusan.id')
            ->select('pendaftar.no_pendaftaran', 'pendaftar_data_siswa.nama', 'jurusan.nama as nama_jurusan', 'pendaftar.status')
            ->get();

        $filename = 'monitoring_berkas_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['No Pendaftaran', 'Nama', 'Jurusan', 'Status']);
        
        foreach ($data as $row) {
            fputcsv($output, [$row->no_pendaftaran, $row->nama, $row->nama_jurusan, $row->status]);
        }
        
        fclose($output);
        exit;
    }
}