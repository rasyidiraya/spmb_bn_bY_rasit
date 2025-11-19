<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftar\Pendaftar;
use App\Models\Pendaftar\Jurusan;
use App\Models\Pendaftar\Gelombang;


class LaporanController extends Controller
{
    public function index()
    {
        $jurusan = Jurusan::all();
        $gelombang = Gelombang::all();
        return view('admin.laporan.index', compact('jurusan', 'gelombang'));
    }

    public function export(Request $request)
    {
        $query = Pendaftar::with(['jurusan', 'gelombang', 'dataSiswa']);
        
        if ($request->jurusan_id) {
            $query->where('jurusan_id', $request->jurusan_id);
        }
        
        if ($request->gelombang_id) {
            $query->where('gelombang_id', $request->gelombang_id);
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }
        
        $data = $query->get();
        
        $filename = 'laporan_pendaftar_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'No Pendaftaran',
                'Nama',
                'Email', 
                'Jurusan',
                'Gelombang',
                'Status',
                'Tanggal Daftar',
                'Tanggal Verifikasi'
            ]);
            
            // Data rows
            foreach ($data as $pendaftar) {
                fputcsv($file, [
                    $pendaftar->no_pendaftaran,
                    $pendaftar->dataSiswa->nama ?? '-',
                    $pendaftar->email,
                    $pendaftar->jurusan->nama ?? '-',
                    $pendaftar->gelombang->nama ?? '-',
                    $pendaftar->status,
                    $pendaftar->created_at->format('Y-m-d H:i:s'),
                    $pendaftar->tgl_verifikasi_adm ? date('Y-m-d H:i:s', strtotime($pendaftar->tgl_verifikasi_adm)) : '-'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}