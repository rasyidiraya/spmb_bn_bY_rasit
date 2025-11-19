@extends('main-admin')
@section('content-admin')

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Monitoring Berkas</h1>
                <p class="text-muted mb-0">Lihat daftar pendaftar & kelengkapan berkas (Read Only)</p>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Pendaftar & Status Berkas</h6>
                    <a href="{{ route('admin.monitoring.export') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-download"></i> Export Data
                    </a>
                </div>
                <div class="card-body">
                    <form method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="SUBMIT" {{ request('status') == 'SUBMIT' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                    <option value="ADM_PASS" {{ request('status') == 'ADM_PASS' ? 'selected' : '' }}>Diterima</option>
                                    <option value="ADM_REJECT" {{ request('status') == 'ADM_REJECT' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="PAYMENT_REJECT" {{ request('status') == 'PAYMENT_REJECT' ? 'selected' : '' }}>Pembayaran Ditolak</option>
                                    <option value="PAID" {{ request('status') == 'PAID' ? 'selected' : '' }}>Terbayar</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No Pendaftaran</th>
                                    <th>Nama</th>
                                    <th>Jurusan</th>
                                    <th>Gelombang</th>
                                    <th>Status Berkas</th>
                                    <th>Status Verifikasi</th>
                                    <th>Status Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendaftar as $item)
                                <tr>
                                    <td>{{ $item->no_pendaftaran }}</td>
                                    <td>{{ $item->nama_siswa }}</td>
                                    <td>{{ $item->nama_jurusan }}</td>
                                    <td>{{ $item->nama_gelombang }}</td>
                                    <td>
                                        @php
                                            $berkasAda = DB::table('pendaftar_berkas')
                                                ->where('pendaftar_id', $item->id)
                                                ->whereNotNull('url')
                                                ->where('url', '!=', '')
                                                ->count();
                                        @endphp
                                        
                                        @if($berkasAda > 0)
                                            <span class="badge badge-success">Terima</span>
                                        @else
                                            <span class="badge badge-warning">Menunggu</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(in_array($item->status, ['SUBMIT']))
                                            <span class="badge badge-warning">Menunggu</span>
                                        @elseif(in_array($item->status, ['ADM_PASS', 'PAYMENT_PENDING', 'PAID']))
                                            <span class="badge badge-success">Diterima</span>
                                        @elseif($item->status == 'ADM_REJECT')
                                            <span class="badge badge-danger">Ditolak</span>
                                        @else
                                            <span class="badge badge-secondary">{{ $item->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status == 'PAID')
                                            <span class="badge badge-success">Lunas</span>
                                        @elseif($item->status == 'PAYMENT_PENDING')
                                            <span class="badge badge-warning">Menunggu Konfirmasi</span>
                                        @elseif($item->status == 'PAYMENT_REJECT')
                                            <span class="badge badge-danger">Ditolak</span>
                                        @elseif($item->status == 'ADM_PASS')
                                            <span class="badge badge-warning">Menunggu Pembayaran</span>
                                        @else
                                            <span class="badge badge-secondary">Belum Bayar</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data pendaftar</td>
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
</div>

@endsection