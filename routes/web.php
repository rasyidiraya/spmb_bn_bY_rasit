<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Pendaftar\DashboardController as PendaftarDashboard;

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Login routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/registrasi', [AuthController::class, 'showRegistrasi'])->name('pendaftar.register');
Route::post('/registrasi', [AuthController::class, 'registrasi'])->name('pendaftar.register.post');

// OTP routes
Route::post('/register/send-otp', [App\Http\Controllers\Auth\OtpController::class, 'sendOtp']);
Route::post('/register/verify-otp', [App\Http\Controllers\Auth\OtpController::class, 'verifyOtp']);
Route::post('/register/resend-otp', [App\Http\Controllers\Auth\OtpController::class, 'resendOtp']);

// Logout route
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Pendaftar routes (protected)
Route::middleware(['auth:pengguna', 'role:pendaftar', 'prevent.back'])->name('pendaftar.')->group(function () {
    Route::get('/home', [PendaftarDashboard::class, 'index'])->name('dashboard');
    
    // Pendaftaran
    Route::get('/pendaftaran', [PendaftarDashboard::class, 'pendaftaran'])->name('pendaftaran');
    Route::post('/pendaftaran', [PendaftarDashboard::class, 'storePendaftaran'])->name('pendaftaran.store');
    
    // Upload Berkas
    Route::get('/upload-berkas', [PendaftarDashboard::class, 'uploadBerkas'])->name('upload-berkas');
    Route::post('/upload-berkas', [PendaftarDashboard::class, 'storeUploadBerkas'])->name('upload-berkas.store');
    
    // Pembayaran
    Route::get('/pembayaran', [PendaftarDashboard::class, 'pembayaran'])->name('pembayaran');
    Route::post('/pembayaran', [PendaftarDashboard::class, 'storePembayaran'])->name('pembayaran.store');
    
    Route::get('/status', [PendaftarDashboard::class, 'status'])->name('status');
    Route::get('/cetak-kartu', [PendaftarDashboard::class, 'cetakKartu'])->name('cetak-kartu');
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth:admin', 'role:admin', 'prevent.back'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Master Data
    Route::get('/master/jurusan', [App\Http\Controllers\Admin\MasterDataController::class, 'jurusan'])->name('master.jurusan');
    Route::post('/master/jurusan', [App\Http\Controllers\Admin\MasterDataController::class, 'storeJurusan'])->name('master.jurusan.store');
    Route::put('/master/jurusan/{id}', [App\Http\Controllers\Admin\MasterDataController::class, 'updateJurusan'])->name('master.jurusan.update');
    Route::delete('/master/jurusan/{id}', [App\Http\Controllers\Admin\MasterDataController::class, 'deleteJurusan'])->name('master.jurusan.delete');
    Route::get('/master/gelombang', [App\Http\Controllers\Admin\MasterDataController::class, 'gelombang'])->name('master.gelombang');
    Route::post('/master/gelombang', [App\Http\Controllers\Admin\MasterDataController::class, 'storeGelombang'])->name('master.gelombang.store');
    Route::patch('/master/gelombang/{id}/toggle-status', [App\Http\Controllers\Admin\MasterDataController::class, 'toggleStatusGelombang'])->name('master.gelombang.toggle-status');
    
    // Monitoring
    Route::get('/monitoring/berkas', [App\Http\Controllers\Admin\MonitoringController::class, 'berkas'])->name('monitoring.berkas');
    Route::get('/monitoring/export', [App\Http\Controllers\Admin\MonitoringController::class, 'export'])->name('monitoring.export');
    
    // Peta Sebaran
    Route::get('/peta', [App\Http\Controllers\Admin\MapController::class, 'index'])->name('peta');
    Route::get('/map-data', [App\Http\Controllers\Admin\MapController::class, 'mapData'])->name('map_data');
    
    // Laporan
    Route::get('/laporan', [App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan');
    Route::post('/laporan/export', [App\Http\Controllers\Admin\LaporanController::class, 'export'])->name('laporan.export');
    
    // Log Aktivitas
    Route::get('/log-aktivitas', [App\Http\Controllers\Admin\LogAktivitasController::class, 'index'])->name('log-aktivitas');
});

// Verifikator Administrasi routes
Route::prefix('verifikator')->name('verifikator.')->middleware(['auth:verifikator', 'role:verifikator_adm', 'prevent.back'])->group(function () {
    Route::get('/', [App\Http\Controllers\VerifikatorAdministrasi\VerifikatorController::class, 'index'])->name('index');
    Route::get('/detail/{id}', [App\Http\Controllers\VerifikatorAdministrasi\VerifikatorController::class, 'detail'])->name('detail');
    Route::post('/verifikasi/{id}', [App\Http\Controllers\VerifikatorAdministrasi\VerifikatorController::class, 'verifikasi'])->name('verifikasi');
    Route::post('/verifikasi-berkas/{id}', [App\Http\Controllers\VerifikatorAdministrasi\VerifikatorController::class, 'verifikasiBerkas'])->name('verifikasi-berkas');
    Route::get('/riwayat', [App\Http\Controllers\VerifikatorAdministrasi\VerifikatorController::class, 'riwayat'])->name('riwayat');
    
    // Laporan
    Route::get('/laporan', [App\Http\Controllers\VerifikatorAdministrasi\LaporanController::class, 'index'])->name('laporan');
    Route::post('/laporan/export', [App\Http\Controllers\VerifikatorAdministrasi\LaporanController::class, 'export'])->name('laporan.export');
});

// Keuangan routes
Route::prefix('keuangan')->name('keuangan.')->middleware(['auth:keuangan', 'role:keuangan', 'prevent.back'])->group(function () {
    // Dashboard
    Route::get('/', [App\Http\Controllers\Keuangan\DashboardController::class, 'index'])->name('dashboard');
    
    // Verifikasi Pembayaran
    Route::get('/verifikasi-pembayaran', [App\Http\Controllers\Keuangan\VerifikasiPembayaranController::class, 'index'])->name('verifikasi-pembayaran.index');
    Route::get('/verifikasi-pembayaran/detail/{id}', [App\Http\Controllers\Keuangan\VerifikasiPembayaranController::class, 'detail'])->name('verifikasi-pembayaran.detail');
    Route::post('/verifikasi-pembayaran/verifikasi/{id}', [App\Http\Controllers\Keuangan\VerifikasiPembayaranController::class, 'verifikasi'])->name('verifikasi-pembayaran.verifikasi');
    
    // Rekap Keuangan
    Route::get('/rekap', [App\Http\Controllers\Keuangan\RekapKeuanganController::class, 'index'])->name('rekap.index');
    Route::get('/rekap/export', [App\Http\Controllers\Keuangan\RekapKeuanganController::class, 'exportExcel'])->name('rekap.export');
    
    // Riwayat
    Route::get('/riwayat', [App\Http\Controllers\Keuangan\VerifikasiPembayaranController::class, 'riwayat'])->name('riwayat');
    
    // Laporan
    Route::get('/laporan', [App\Http\Controllers\keuangan\LaporanController::class, 'index'])->name('laporan');
    Route::post('/laporan/export', [App\Http\Controllers\keuangan\LaporanController::class, 'export'])->name('laporan.export');
});

// Kepala Sekolah routes
Route::prefix('kepsek')->name('kepsek.')->middleware(['auth:kepsek', 'role:kepsek', 'prevent.back'])->group(function () {
    Route::get('/', [App\Http\Controllers\Kepsek\DashboardController::class, 'index'])->name('dashboard');
});

// API Routes untuk dropdown wilayah
Route::prefix('api/wilayah')->group(function () {
    Route::get('/provinsi', [App\Http\Controllers\Api\WilayahController::class, 'getProvinsi']);
    Route::get('/kabupaten/{provinsi}', [App\Http\Controllers\Api\WilayahController::class, 'getKabupaten']);
    Route::get('/kecamatan/{provinsi}/{kabupaten}', [App\Http\Controllers\Api\WilayahController::class, 'getKecamatan']);
    Route::get('/kelurahan/{provinsi}/{kabupaten}/{kecamatan}', [App\Http\Controllers\Api\WilayahController::class, 'getKelurahan']);
});
