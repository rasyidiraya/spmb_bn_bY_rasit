@extends('main-keuangan')
@section('content-keuangan')

<div id="content">
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
        <h1 class="h3 mb-0 text-gray-800">Riwayat Verifikasi Pembayaran</h1>
    </nav>

    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Filter & Pencarian</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('keuangan.riwayat') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Cari berdasarkan nama atau no. pendaftaran..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="PAID" {{ request('status') == 'PAID' ? 'selected' : '' }}>Terverifikasi</option>
                                <option value="PAYMENT_REJECT" {{ request('status') == 'PAYMENT_REJECT' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Riwayat Verifikasi Pembayaran</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No. Pendaftaran</th>
                                <th>Nama</th>
                                <th>Jurusan</th>
                                <th>Gelombang</th>
                                <th>Biaya</th>
                                <th>Tanggal Bayar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendaftar as $p)
                            <tr>
                                <td>{{ $p->no_pendaftaran }}</td>
                                <td>{{ $p->nama }}</td>
                                <td>{{ $p->nama_jurusan }}</td>
                                <td>{{ $p->nama_gelombang }}</td>
                                <td>Rp {{ number_format($p->biaya_daftar) }}</td>
                                <td>
                                    @if($p->tanggal_pembayaran)
                                        {{ date('d/m/Y', strtotime($p->tanggal_pembayaran)) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($p->status == 'PAID')
                                        <span class="badge badge-success">Terverifikasi</span>
                                    @elseif($p->status == 'PAYMENT_REJECT')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('keuangan.verifikasi-pembayaran.detail', $p->id) }}" 
                                       class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data riwayat verifikasi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center">
                    {{ $pendaftar->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection