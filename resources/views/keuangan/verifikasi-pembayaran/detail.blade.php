@extends('main-keuangan')
@section('content-keuangan')

<div id="content">
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>
        
        <h1 class="h3 mb-0 text-gray-800">Detail Verifikasi Pembayaran</h1>
        
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">Keuangan</span>
                    <img class="img-profile rounded-circle" src="https://via.placeholder.com/60x60">
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow">
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data Pendaftar</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="200">No. Pendaftaran</td>
                                <td>: {{ $pendaftar->no_pendaftaran }}</td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td>: {{ $pendaftar->nama }}</td>
                            </tr>
                            <tr>
                                <td>Jurusan</td>
                                <td>: {{ $pendaftar->nama_jurusan }}</td>
                            </tr>
                            <tr>
                                <td>Gelombang</td>
                                <td>: {{ $pendaftar->nama_gelombang }}</td>
                            </tr>
                            <tr>
                                <td>Biaya Pendaftaran</td>
                                <td>: <strong>Rp {{ number_format($pendaftar->biaya_daftar) }}</strong></td>
                            </tr>
                            @if($pendaftar->tanggal_pembayaran)
                            <tr>
                                <td>Tanggal Pembayaran</td>
                                <td>: <strong>{{ date('d F Y', strtotime($pendaftar->tanggal_pembayaran)) }}</strong></td>
                            </tr>
                            @endif
                            <tr>
                                <td>Status</td>
                                <td>: <span class="badge badge-warning">{{ $pendaftar->status }}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($pendaftar->bukti_bayar)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Bukti Pembayaran</h6>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset('storage/'.$pendaftar->bukti_bayar) }}" 
                             class="img-fluid" style="max-height: 500px;">
                        <div class="mt-3">
                            <a href="{{ asset('storage/'.$pendaftar->bukti_bayar) }}" 
                               target="_blank" class="btn btn-info">
                                <i class="fas fa-external-link-alt"></i> Buka di Tab Baru
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Verifikasi Pembayaran</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('keuangan.verifikasi-pembayaran.verifikasi', $pendaftar->id) }}">
                            @csrf
                            <div class="form-group">
                                <label>Status Verifikasi</label>
                                <select name="status" class="form-control" required>
                                    <option value="">Pilih Status</option>
                                    <option value="PAID">Terverifikasi (PAID)</option>
                                    <option value="PAYMENT_REJECT">Ditolak</option>
                                </select>
                            </div>
                            

                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fas fa-check"></i> Simpan Verifikasi
                                </button>
                                <a href="{{ route('keuangan.verifikasi-pembayaran.index') }}" 
                                   class="btn btn-secondary btn-block">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection