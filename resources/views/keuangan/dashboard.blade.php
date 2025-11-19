@extends('main-keuangan')
@section('content-keuangan')

<div id="content">
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Keuangan</h1>
    </nav>

    <div class="container-fluid">
        <!-- Statistik Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow h-100 py-2" style="border-left: 4px solid #0074b7;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #0074b7;">Total Pendaftar</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPendaftar }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x" style="color: #60a3d9;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow h-100 py-2" style="border-left: 4px solid #60a3d9;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #60a3d9;">Menunggu Verifikasi</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $menungguVerifikasi }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x" style="color: #0074b7;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow h-100 py-2" style="border-left: 4px solid #003b73;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #003b73;">Sudah Bayar</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $sudahBayar }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x" style="color: #0074b7;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow h-100 py-2" style="border-left: 4px solid #0074b7;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #0074b7;">Total Pemasukan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPemasukan) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x" style="color: #60a3d9;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3" style="background: linear-gradient(135deg, #0074b7 0%, #60a3d9 100%); color: white;">
                        <h6 class="m-0 font-weight-bold">Menu Utama</h6>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('keuangan.verifikasi-pembayaran.index') }}" class="btn btn-block mb-3" style="background: linear-gradient(135deg, #0074b7 0%, #60a3d9 100%); border: none; color: white;">
                            <i class="fas fa-credit-card"></i> Verifikasi Pembayaran
                        </a>
                        <a href="{{ route('keuangan.rekap.index') }}" class="btn btn-block mb-3" style="background: linear-gradient(135deg, #003b73 0%, #0074b7 100%); border: none; color: white;">
                            <i class="fas fa-chart-bar"></i> Rekap Keuangan
                        </a>
                        <a href="{{ route('keuangan.laporan') }}" class="btn btn-block" style="background: linear-gradient(135deg, #60a3d9 0%, #0074b7 100%); border: none; color: white;">
                            <i class="fas fa-file-export"></i> Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection