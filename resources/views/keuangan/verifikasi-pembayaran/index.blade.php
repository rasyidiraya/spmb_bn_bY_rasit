@extends('main-keuangan')
@section('content-keuangan')

<div id="content">
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>
        
        <h1 class="h3 mb-0 text-gray-800">Verifikasi Pembayaran</h1>
        
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
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Pembayaran</h6>
                
                <form method="GET" class="mt-3">
                    <div class="row">
                        <div class="col-md-3">
                            <select name="status" class="form-control form-control-sm">
                                <option value="">Semua Status</option>
                                <option value="PAYMENT_PENDING" {{ request('status') == 'PAYMENT_PENDING' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                <option value="PAID" {{ request('status') == 'PAID' ? 'selected' : '' }}>Lunas</option>
                                <option value="ADM_PASS" {{ request('status') == 'ADM_PASS' ? 'selected' : '' }}>Belum Bayar</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control form-control-sm" 
                                   placeholder="Cari No. Pendaftaran / Nama" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No Pendaftaran</th>
                                <th>Nama</th>
                                <th>Jurusan</th>
                                <th>Gelombang</th>
                                <th>Biaya</th>
                                <th>Tgl Bayar</th>
                                <th>Bukti Bayar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendaftar as $item)
                            <tr>
                                <td>{{ $item->no_pendaftaran }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->nama_jurusan }}</td>
                                <td>{{ $item->nama_gelombang }}</td>
                                <td>Rp {{ number_format($item->biaya_daftar) }}</td>
                                <td>
                                    @if($item->tanggal_pembayaran)
                                        {{ date('d/m/Y', strtotime($item->tanggal_pembayaran)) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->bukti_bayar)
                                        <a href="{{ asset('storage/'.$item->bukti_bayar) }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    @else
                                        <span class="text-muted">Belum upload</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusText = match($item->status) {
                                            'PAYMENT_PENDING' => 'Menunggu Konfirmasi',
                                            'PAID' => 'Lunas',
                                            'PAYMENT_REJECT' => 'Ditolak',
                                            'ADM_PASS' => 'Belum Bayar',
                                            'SUBMIT' => 'Menunggu Verifikasi',
                                            'ADM_REJECT' => 'Ditolak Admin',
                                            default => $item->status
                                        };
                                        $badgeClass = match($item->status) {
                                            'PAYMENT_PENDING' => 'badge-warning',
                                            'PAID' => 'badge-success',
                                            'PAYMENT_REJECT' => 'badge-danger',
                                            'ADM_PASS' => 'badge-info',
                                            'SUBMIT' => 'badge-secondary',
                                            'ADM_REJECT' => 'badge-danger',
                                            default => 'badge-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('keuangan.verifikasi-pembayaran.detail', $item->id) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-check"></i> Verifikasi
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data pembayaran</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $pendaftar->links() }}
            </div>
        </div>
    </div>
</div>

@endsection