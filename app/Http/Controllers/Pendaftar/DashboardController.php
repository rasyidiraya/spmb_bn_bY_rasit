<?php

namespace App\Http\Controllers\Pendaftar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pendaftar\Pendaftar;
use App\Models\Pendaftar\PendaftarDataSiswa;
use App\Models\Pendaftar\PendaftarDataOrtu;
use App\Models\Pendaftar\PendaftarAsalSekolah;

class DashboardController extends Controller
{
    public function index()
    {
        return view('Pendaftar.home');
    }

    public function pendaftaran()
    {
        $userId = auth('pengguna')->id();
        $oldData = null;
        
        // Cek apakah ada data lama yang ditolak
        $existingPendaftar = DB::table('pendaftar')
            ->where('user_id', $userId)
            ->whereIn('status', ['ADM_REJECT', 'PAYMENT_REJECT'])
            ->first();
            
        if ($existingPendaftar) {
            // Ambil data lama untuk pre-fill form
            $oldData = DB::table('pendaftar')
                ->join('pendaftar_data_siswa', 'pendaftar.id', '=', 'pendaftar_data_siswa.pendaftar_id')
                ->join('pendaftar_data_ortu', 'pendaftar.id', '=', 'pendaftar_data_ortu.pendaftar_id')
                ->join('pendaftar_asal_sekolah', 'pendaftar.id', '=', 'pendaftar_asal_sekolah.pendaftar_id')
                ->where('pendaftar.id', $existingPendaftar->id)
                ->select(
                    'pendaftar_data_siswa.nama as nama_lengkap',
                    'pendaftar_data_siswa.nik',
                    'pendaftar_data_siswa.nisn',
                    'pendaftar_data_siswa.tmp_lahir as tempat_lahir',
                    'pendaftar_data_siswa.tgl_lahir as tanggal_lahir',
                    'pendaftar_data_siswa.jk as jenis_kelamin',
                    'pendaftar_data_ortu.nama_ayah',
                    'pendaftar_data_ortu.nama_ibu',
                    'pendaftar_data_ortu.pekerjaan_ayah',
                    'pendaftar_data_ortu.pekerjaan_ibu',
                    'pendaftar_data_ortu.hp_ayah',
                    'pendaftar_data_ortu.hp_ibu',
                    'pendaftar_asal_sekolah.nama_sekolah',
                    'pendaftar_asal_sekolah.npsn',
                    'pendaftar.jurusan_id',
                    'pendaftar.gelombang_id'
                )
                ->first();
        }
        
        return view('Pendaftar.pendaftaran', compact('oldData'));
    }

    public function storePendaftaran(Request $request)
    {
        // Validasi data
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string',
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'pekerjaan_ayah' => 'required|string|max:255',
            'pekerjaan_ibu' => 'required|string|max:255',
            'hp_ayah' => 'required|string|max:15',
            'hp_ibu' => 'required|string|max:15',
            'nama_sekolah' => 'required|string|max:255',
            'npsn' => 'required|string|max:20',
            'alamat_sekolah' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'wilayah_id' => 'required|exists:wilayah,id',
            'jurusan_id' => 'required|exists:jurusan,id',
            'gelombang_id' => 'required|exists:gelombang,id',
        ]);

        try {
            $userId = auth('pengguna')->id();
            
            DB::beginTransaction();
            
            // Generate nomor pendaftaran unik
            do {
                $noPendaftaran = 'SPMB' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            } while (Pendaftar::where('no_pendaftaran', $noPendaftaran)->exists());
            
            // Cek apakah user sudah pernah daftar
            $existingPendaftar = Pendaftar::where('user_id', $userId)->first();
            
            if ($existingPendaftar && !in_array($existingPendaftar->status, ['ADM_REJECT', 'PAYMENT_REJECT'])) {
                return back()->withErrors(['error' => 'Anda sudah terdaftar dan tidak dapat mendaftar ulang.'])->withInput();
            }
            
            // Hapus data lama jika ditolak, buat baru
            if ($existingPendaftar && in_array($existingPendaftar->status, ['ADM_REJECT', 'PAYMENT_REJECT'])) {
                // Hapus data terkait
                DB::table('pendaftar_berkas')->where('pendaftar_id', $existingPendaftar->id)->delete();
                DB::table('pendaftar_data_siswa')->where('pendaftar_id', $existingPendaftar->id)->delete();
                DB::table('pendaftar_data_ortu')->where('pendaftar_id', $existingPendaftar->id)->delete();
                DB::table('pendaftar_asal_sekolah')->where('pendaftar_id', $existingPendaftar->id)->delete();
                $existingPendaftar->delete();
            }
            
            // Simpan data pendaftar baru
            $pendaftar = Pendaftar::create([
                'user_id' => $userId,
                'jurusan_id' => $request->jurusan_id,
                'gelombang_id' => $request->gelombang_id,
                'status' => 'DRAFT',
                'tanggal_daftar' => now()->toDateString(),
                'no_pendaftaran' => $noPendaftaran
            ]);
            
            // Pastikan ada data wilayah default
            $wilayahId = DB::table('wilayah')->first()->id ?? null;
            if (!$wilayahId) {
                $wilayahId = DB::table('wilayah')->insertGetId([
                    'provinsi' => 'Jawa Barat',
                    'kabupaten' => 'Bandung',
                    'kecamatan' => 'Default',
                    'kelurahan' => 'Default',
                    'kodepos' => '40000',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            // Simpan data siswa
            PendaftarDataSiswa::updateOrCreate(
                ['pendaftar_id' => $pendaftar->id],
                [
                    'pendaftar_id' => $pendaftar->id,
                    'nama' => $request->nama_lengkap,
                    'nik' => $request->nik,
                    'nisn' => $request->nisn ?? '-',
                    'tmp_lahir' => $request->tempat_lahir,
                    'tgl_lahir' => $request->tanggal_lahir,
                    'jk' => $request->jenis_kelamin,
                    'alamat' => $request->alamat_lengkap,
                    'wilayah_id' => $request->wilayah_id
                ]
            );
            
            // Simpan data orang tua
            PendaftarDataOrtu::updateOrCreate(
                ['pendaftar_id' => $pendaftar->id],
                [
                    'pendaftar_id' => $pendaftar->id,
                    'nama_ayah' => $request->nama_ayah,
                    'nama_ibu' => $request->nama_ibu,
                    'pekerjaan_ayah' => $request->pekerjaan_ayah,
                    'pekerjaan_ibu' => $request->pekerjaan_ibu,
                    'hp_ayah' => $request->hp_ayah,
                    'hp_ibu' => $request->hp_ibu
                ]
            );
            
            // Simpan data asal sekolah
            PendaftarAsalSekolah::updateOrCreate(
                ['pendaftar_id' => $pendaftar->id],
                [
                    'pendaftar_id' => $pendaftar->id,
                    'nama_sekolah' => $request->nama_sekolah,
                    'npsn' => $request->npsn,
                    'kabupaten' => $request->kabupaten,
                    'nilai_rata' => 0.00 // Default nilai rata-rata
                ]
            );
            
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()])->withInput();
        }
        
        // Redirect ke halaman upload berkas
        return redirect()->route('pendaftar.upload-berkas')
            ->with('success', 'Data pendaftaran berhasil disimpan. Silakan upload berkas persyaratan.');
    }

    public function uploadBerkas()
    {
        $userId = auth('pengguna')->id();
        $pendaftar = Pendaftar::where('user_id', $userId)->first();
        
        return view('Pendaftar.upload-berkas', compact('pendaftar'));
    }

    public function storeUploadBerkas(Request $request)
    {
        // Validasi file upload
        $request->validate([
            'ijazah' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB
            'akta' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'kk' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'kip' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'ijazah.required' => 'File ijazah wajib diupload',
            'ijazah.mimes' => 'File ijazah harus berformat PDF, JPG, JPEG, atau PNG',
            'akta.required' => 'File akta kelahiran wajib diupload',
            'akta.mimes' => 'File akta kelahiran harus berformat PDF, JPG, JPEG, atau PNG',
            'kk.required' => 'File kartu keluarga wajib diupload',
            'kk.mimes' => 'File kartu keluarga harus berformat PDF, JPG, JPEG, atau PNG',
            'kip.mimes' => 'File KIP harus berformat PDF, JPG, JPEG, atau PNG',
            '*.max' => 'Ukuran file maksimal 5MB'
        ]);

        try {
            $userId = auth('pengguna')->id();
            $pendaftar = Pendaftar::where('user_id', $userId)->first();
            
            if (!$pendaftar) {
                return back()->withErrors(['error' => 'Data pendaftaran tidak ditemukan.']);
            }

            // Simpan file ke storage/app/public/berkas
            $berkasData = [];
            
            // Upload Ijazah
            if ($request->hasFile('ijazah') && $request->file('ijazah')->isValid()) {
                $ijazah = $request->file('ijazah');
                $ijazahName = 'ijazah_' . $pendaftar->id . '_' . time() . '.' . $ijazah->getClientOriginalExtension();
                if ($ijazah->storeAs('public/berkas', $ijazahName)) {
                    $berkasData[] = [
                        'pendaftar_id' => $pendaftar->id,
                        'jenis' => 'IJAZAH',
                        'nama_file' => $ijazah->getClientOriginalName(),
                        'url' => 'berkas/' . $ijazahName,
                        'ukuran_kb' => round($ijazah->getSize() / 1024),
                        'valid' => 0,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
            
            // Upload Akta
            if ($request->hasFile('akta') && $request->file('akta')->isValid()) {
                $akta = $request->file('akta');
                $aktaName = 'akta_' . $pendaftar->id . '_' . time() . '.' . $akta->getClientOriginalExtension();
                if ($akta->storeAs('public/berkas', $aktaName)) {
                    $berkasData[] = [
                        'pendaftar_id' => $pendaftar->id,
                        'jenis' => 'AKTA',
                        'nama_file' => $akta->getClientOriginalName(),
                        'url' => 'berkas/' . $aktaName,
                        'ukuran_kb' => round($akta->getSize() / 1024),
                        'valid' => 0,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
            
            // Upload KK
            if ($request->hasFile('kk') && $request->file('kk')->isValid()) {
                $kk = $request->file('kk');
                $kkName = 'kk_' . $pendaftar->id . '_' . time() . '.' . $kk->getClientOriginalExtension();
                if ($kk->storeAs('public/berkas', $kkName)) {
                    $berkasData[] = [
                        'pendaftar_id' => $pendaftar->id,
                        'jenis' => 'KK',
                        'nama_file' => $kk->getClientOriginalName(),
                        'url' => 'berkas/' . $kkName,
                        'ukuran_kb' => round($kk->getSize() / 1024),
                        'valid' => 0,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
            
            // Upload KIP (opsional)
            if ($request->hasFile('kip') && $request->file('kip')->isValid()) {
                $kip = $request->file('kip');
                $kipName = 'kip_' . $pendaftar->id . '_' . time() . '.' . $kip->getClientOriginalExtension();
                if ($kip->storeAs('public/berkas', $kipName)) {
                    $berkasData[] = [
                        'pendaftar_id' => $pendaftar->id,
                        'jenis' => 'KIP',
                        'nama_file' => $kip->getClientOriginalName(),
                        'url' => 'berkas/' . $kipName,
                        'ukuran_kb' => round($kip->getSize() / 1024),
                        'valid' => 0,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
            
            // Hapus berkas lama jika ada
            DB::table('pendaftar_berkas')->where('pendaftar_id', $pendaftar->id)->delete();
            
            // Insert berkas baru
            if (!empty($berkasData)) {
                DB::table('pendaftar_berkas')->insert($berkasData);
                
                // Update status ke SUBMIT setelah berkas diupload
                $pendaftar->update(['status' => 'SUBMIT']);
            }
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal upload berkas: ' . $e->getMessage()])->withInput();
        }
        
        return redirect()->route('pendaftar.status')
            ->with('success', 'Berkas berhasil diupload. Menunggu verifikasi administrator.');
    }

    public function status()
    {
        $userId = auth('pengguna')->id();
        $pendaftar = DB::table('pendaftar')
            ->join('pendaftar_data_siswa', 'pendaftar.id', '=', 'pendaftar_data_siswa.pendaftar_id')
            ->join('jurusan', 'pendaftar.jurusan_id', '=', 'jurusan.id')
            ->where('pendaftar.user_id', $userId)
            ->select(
                'pendaftar.*',
                'pendaftar_data_siswa.nama',
                'jurusan.nama as nama_jurusan'
            )
            ->first();
            
        return view('Pendaftar.status', compact('pendaftar'));
    }

    public function pembayaran()
    {
        $userId = auth('pengguna')->id();
        $pendaftar = DB::table('pendaftar')
            ->leftJoin('gelombang', 'pendaftar.gelombang_id', '=', 'gelombang.id')
            ->where('pendaftar.user_id', $userId)
            ->select('pendaftar.*', 'gelombang.nama as nama_gelombang', 'gelombang.biaya_daftar', 'gelombang.tgl_selesai')
            ->first();
            
        $berkasCount = 0;
        if ($pendaftar) {
            $berkasCount = DB::table('pendaftar_berkas')
                ->where('pendaftar_id', $pendaftar->id)
                ->whereIn('jenis', ['IJAZAH', 'AKTA', 'KK'])
                ->count();
        }
            
        return view('Pendaftar.pembayaran', compact('pendaftar', 'berkasCount'));
    }

    public function storePembayaran(Request $request)
    {
        \Log::info('storePembayaran called', $request->all());
        
        $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'tanggal_pembayaran' => 'required|date',
            'metode_pembayaran' => 'required|string',
            'nominal' => 'required|numeric',
        ]);

        try {
            $userId = auth('pengguna')->id();
            $pendaftar = Pendaftar::where('user_id', $userId)->first();
            
            if (!$pendaftar) {
                return back()->withErrors(['error' => 'Data pendaftaran tidak ditemukan.']);
            }

            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $fileName = 'pembayaran_' . $pendaftar->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/pembayaran', $fileName);
                
                // Update status pembayaran ke pending
                $pendaftar->update(['status' => 'PAYMENT_PENDING']);
                
                // Simpan bukti pembayaran ke tabel berkas
                DB::table('pendaftar_berkas')->insert([
                    'pendaftar_id' => $pendaftar->id,
                    'jenis' => 'BUKTI_BAYAR',
                    'nama_file' => $file->getClientOriginalName(),
                    'url' => 'pembayaran/' . $fileName,
                    'ukuran_kb' => round($file->getSize() / 1024),
                    'valid' => 0,
                    'catatan' => 'Metode: ' . $request->metode_pembayaran . ' | Nominal: Rp ' . number_format($request->nominal),
                    'tanggal_pembayaran' => $request->tanggal_pembayaran,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            return redirect()->route('pendaftar.status')
                ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal upload bukti pembayaran: ' . $e->getMessage()]);
        }
    }

    public function cetakKartu()
    {
        $userId = auth('pengguna')->id();
        $pendaftar = Pendaftar::where('user_id', $userId)->first();
        
        return view('Pendaftar.cetak-kartu', compact('pendaftar'));
    }
}