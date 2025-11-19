<?php

namespace App\Http\Controllers\VerifikatorAdministrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftar\Pendaftar;
use App\Models\Pendaftar\Jurusan;
use App\Models\Pendaftar\Gelombang;
use Illuminate\Support\Facades\DB;


class LaporanController extends Controller
{
    public function index()
    {
        $jurusan = Jurusan::all();
        $gelombang = Gelombang::all();
        
        // Statistik ringkas
        $stats = [
            'total_pendaftar' => DB::table('pendaftar')->count(),
            'menunggu_verifikasi' => DB::table('pendaftar')->where('pendaftar.status', 'SUBMIT')->count(),
            'diterima' => DB::table('pendaftar')->where('pendaftar.status', 'ADM_PASS')->count(),
            'ditolak' => DB::table('pendaftar')->where('pendaftar.status', 'ADM_REJECT')->count(),
        ];
        
        return view('verifikator-administrasi.laporan.index', compact('jurusan', 'gelombang', 'stats'));
    }

    public function export(Request $request)
    {
        $query = DB::table('pendaftar')
            ->join('pendaftar_data_siswa', 'pendaftar.id', '=', 'pendaftar_data_siswa.pendaftar_id')
            ->join('jurusan', 'pendaftar.jurusan_id', '=', 'jurusan.id')
            ->join('gelombang', 'pendaftar.gelombang_id', '=', 'gelombang.id')
            ->join('pengguna', 'pendaftar.user_id', '=', 'pengguna.id')
            ->select(
                'pendaftar.no_pendaftaran',
                'pendaftar_data_siswa.nama',
                'pengguna.email',
                'jurusan.nama as nama_jurusan',
                'gelombang.nama as nama_gelombang',
                'pendaftar.status',
                'pendaftar.created_at',
                'pendaftar.tgl_verifikasi_adm'
            )
            ->whereIn('pendaftar.status', ['SUBMIT', 'ADM_PASS', 'ADM_REJECT']);
        
        if ($request->jurusan_id) {
            $query->where('pendaftar.jurusan_id', $request->jurusan_id);
        }
        
        if ($request->gelombang_id) {
            $query->where('pendaftar.gelombang_id', $request->gelombang_id);
        }
        
        if ($request->status) {
            $query->where('pendaftar.status', $request->status);
        }
        
        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $query->whereBetween('pendaftar.created_at', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }
        
        $data = $query->get();
        
        // Tentukan format export
        $format = $request->format ?? 'excel';
        
        if ($format === 'excel') {
            $filename = 'laporan_verifikasi_' . date('Y-m-d_H-i-s') . '.csv';
            
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
                    'Status Verifikasi',
                    'Tanggal Daftar',
                    'Tanggal Verifikasi Administrasi'
                ]);
                
                // Data rows
                foreach ($data as $pendaftar) {
                    fputcsv($file, [
                        $pendaftar->no_pendaftaran,
                        $pendaftar->nama ?? '-',
                        $pendaftar->email,
                        $pendaftar->nama_jurusan ?? '-',
                        $pendaftar->nama_gelombang ?? '-',
                        $pendaftar->status,
                        date('Y-m-d H:i:s', strtotime($pendaftar->created_at)),
                        $pendaftar->tgl_verifikasi_adm ? date('Y-m-d H:i:s', strtotime($pendaftar->tgl_verifikasi_adm)) : '-'
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
        }
        
        // Untuk format PDF, redirect kembali dengan pesan
        return redirect()->back()->with('info', 'Export PDF akan segera tersedia.');
    }
}