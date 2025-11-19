<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikasiPembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('pendaftar')
            ->join('pendaftar_data_siswa', 'pendaftar.id', '=', 'pendaftar_data_siswa.pendaftar_id')
            ->join('jurusan', 'pendaftar.jurusan_id', '=', 'jurusan.id')
            ->join('gelombang', 'pendaftar.gelombang_id', '=', 'gelombang.id')
            ->leftJoin('pendaftar_berkas', function($join) {
                $join->on('pendaftar.id', '=', 'pendaftar_berkas.pendaftar_id')
                     ->where('pendaftar_berkas.jenis', '=', 'BUKTI_BAYAR');
            })
            ->select(
                'pendaftar.id',
                'pendaftar.no_pendaftaran',
                'pendaftar_data_siswa.nama',
                'jurusan.nama as nama_jurusan',
                'gelombang.nama as nama_gelombang',
                'gelombang.biaya_daftar',
                'pendaftar.status',
                'pendaftar_berkas.url as bukti_bayar',
                'pendaftar_berkas.tanggal_pembayaran',
                'pendaftar.created_at'
            )
            ->whereIn('pendaftar.status', ['ADM_PASS', 'PAYMENT_PENDING', 'PAID', 'PAYMENT_REJECT'])
            ->orderBy('pendaftar.created_at', 'desc');

        if ($request->status) {
            $query->where('pendaftar.status', $request->status);
        }
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('pendaftar.no_pendaftaran', 'like', '%'.$request->search.'%')
                  ->orWhere('pendaftar_data_siswa.nama', 'like', '%'.$request->search.'%');
            });
        }

        $pendaftar = $query->paginate(20);
        
        return view('keuangan.verifikasi-pembayaran.index', compact('pendaftar'));
    }

    public function detail($id)
    {
        $pendaftar = DB::table('pendaftar')
            ->join('pendaftar_data_siswa', 'pendaftar.id', '=', 'pendaftar_data_siswa.pendaftar_id')
            ->join('jurusan', 'pendaftar.jurusan_id', '=', 'jurusan.id')
            ->join('gelombang', 'pendaftar.gelombang_id', '=', 'gelombang.id')
            ->leftJoin('pendaftar_berkas', function($join) {
                $join->on('pendaftar.id', '=', 'pendaftar_berkas.pendaftar_id')
                     ->where('pendaftar_berkas.jenis', '=', 'BUKTI_BAYAR');
            })
            ->select(
                'pendaftar.id',
                'pendaftar.no_pendaftaran',
                'pendaftar_data_siswa.nama',
                'jurusan.nama as nama_jurusan',
                'gelombang.nama as nama_gelombang',
                'gelombang.biaya_daftar',
                'pendaftar.status',
                'pendaftar_berkas.url as bukti_bayar',
                'pendaftar_berkas.tanggal_pembayaran'
            )
            ->where('pendaftar.id', $id)
            ->first();

        return view('keuangan.verifikasi-pembayaran.detail', compact('pendaftar'));
    }

    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:PAID,PAYMENT_REJECT',
            'catatan' => 'nullable|string|max:500'
        ]);

        DB::table('pendaftar')
            ->where('id', $id)
            ->update(['status' => $request->status]);

        return redirect()->route('keuangan.verifikasi-pembayaran.index')
            ->with('success', 'Verifikasi pembayaran berhasil disimpan');
    }

    public function riwayat(Request $request)
    {
        $query = DB::table('pendaftar')
            ->join('pendaftar_data_siswa', 'pendaftar.id', '=', 'pendaftar_data_siswa.pendaftar_id')
            ->join('jurusan', 'pendaftar.jurusan_id', '=', 'jurusan.id')
            ->join('gelombang', 'pendaftar.gelombang_id', '=', 'gelombang.id')
            ->leftJoin('pendaftar_berkas', function($join) {
                $join->on('pendaftar.id', '=', 'pendaftar_berkas.pendaftar_id')
                     ->where('pendaftar_berkas.jenis', '=', 'BUKTI_BAYAR');
            })
            ->select(
                'pendaftar.id',
                'pendaftar.no_pendaftaran',
                'pendaftar_data_siswa.nama',
                'jurusan.nama as nama_jurusan',
                'gelombang.nama as nama_gelombang',
                'gelombang.biaya_daftar',
                'pendaftar.status',
                'pendaftar_berkas.url as bukti_bayar',
                'pendaftar_berkas.tanggal_pembayaran',
                'pendaftar.created_at'
            )
            ->whereIn('pendaftar.status', ['PAID', 'PAYMENT_REJECT'])
            ->orderBy('pendaftar.created_at', 'desc');

        if ($request->status) {
            $query->where('pendaftar.status', $request->status);
        }
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('pendaftar.no_pendaftaran', 'like', '%'.$request->search.'%')
                  ->orWhere('pendaftar_data_siswa.nama', 'like', '%'.$request->search.'%');
            });
        }

        $pendaftar = $query->paginate(20);
        
        return view('keuangan.riwayat', compact('pendaftar'));
    }
}