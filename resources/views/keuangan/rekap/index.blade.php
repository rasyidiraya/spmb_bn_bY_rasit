@extends('main-keuangan')
@section('content-keuangan')

<div id="content">
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>
        
        <h1 class="h3 mb-0 text-gray-800">Rekap Keuangan</h1>
        
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
        <!-- Total Keseluruhan -->
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card border-left-success shadow">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pemasukan Keseluruhan</div>
                                <div class="h4 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalKeseluruhan->total_pemasukan ?? 0) }}</div>
                                <div class="text-xs text-gray-500">{{ $totalKeseluruhan->total_terbayar ?? 0 }} dari {{ $totalKeseluruhan->total_pendaftar ?? 0 }} pendaftar</div>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('keuangan.rekap.export') }}" class="btn btn-success">
                                    <i class="fas fa-download"></i> Export Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Rekap Per Gelombang -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Rekap Per Gelombang</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Gelombang</th>
                                        <th>Biaya</th>
                                        <th>Pendaftar</th>
                                        <th>Terbayar</th>
                                        <th>Pemasukan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rekapGelombang as $item)
                                    <tr>
                                        <td>{{ $item->nama }}</td>
                                        <td>Rp {{ number_format($item->biaya_daftar) }}</td>
                                        <td>{{ $item->total_pendaftar }}</td>
                                        <td>{{ $item->terbayar }}</td>
                                        <td><strong>Rp {{ number_format($item->total_pemasukan) }}</strong></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rekap Per Jurusan -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Rekap Per Jurusan</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Jurusan</th>
                                        <th>Pendaftar</th>
                                        <th>Terbayar</th>
                                        <th>Pemasukan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rekapJurusan as $item)
                                    <tr>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->total_pendaftar }}</td>
                                        <td>{{ $item->terbayar }}</td>
                                        <td><strong>Rp {{ number_format($item->total_pemasukan) }}</strong></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection