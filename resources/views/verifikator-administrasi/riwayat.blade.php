@extends('main-verifikator')
@section('content-verifikator')

<div id="content">
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
        <h1 class="h3 mb-0 text-gray-800">Riwayat Verifikasi</h1>
    </nav>

    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Data Pendaftar yang Sudah Diverifikasi</h6>
                <div class="dropdown no-arrow">
                    <form method="GET" action="{{ route('verifikator.riwayat') }}" class="d-flex">
                        <input type="text" name="search" class="form-control form-control-sm mr-2" 
                               placeholder="Cari no pendaftaran atau nama..." 
                               value="{{ request('search') }}" style="width: 250px;">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(request('search'))
                            <a href="{{ route('verifikator.riwayat') }}" class="btn btn-secondary btn-sm ml-1">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No Pendaftaran</th>
                                <th>Nama</th>
                                <th>Jurusan</th>
                                <th>Gelombang</th>
                                <th>Status</th>
                                <th>Tanggal Verifikasi</th>
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
                                <td>
                                    @if($p->status == 'ADM_PASS')
                                        <span class="badge badge-info">Menunggu Pembayaran</span>
                                    @elseif($p->status == 'ADM_REJECT')
                                        <span class="badge badge-danger">Tidak Lolos</span>
                                    @elseif($p->status == 'PAYMENT_PENDING')
                                        <span class="badge badge-warning">Menunggu Konfirmasi Bayar</span>
                                    @elseif($p->status == 'PAID')
                                        <span class="badge badge-success">Selesai</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $p->status }}</span>
                                    @endif
                                </td>
                                <td>{{ date('d/m/Y H:i', strtotime($p->tgl_verifikasi_adm)) }}</td>
                                <td>
                                    <a href="{{ route('verifikator.detail', $p->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    @if(request('search'))
                                        Tidak ada data yang sesuai dengan pencarian "{{ request('search') }}"
                                    @else
                                        Belum ada data verifikasi
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($pendaftar->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $pendaftar->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection