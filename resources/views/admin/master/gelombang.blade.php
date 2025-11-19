@extends('main-admin')

@section('content-admin')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Master Data Gelombang</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Gelombang Pendaftaran</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Gelombang</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Biaya Daftar</th>
                            <th>Tahun</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gelombang as $index => $item)
                        @php
                            $today = now()->toDateString();
                            $isActive = $item->tgl_mulai <= $today && $item->tgl_selesai >= $today;
                            $isPast = $item->tgl_selesai < $today;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tgl_mulai)->format('d F Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tgl_selesai)->format('d F Y') }}</td>
                            <td>Rp {{ number_format($item->biaya_daftar, 0, ',', '.') }}</td>
                            <td>{{ $item->tahun }}</td>
                            <td>
                                @if($item->status === 'aktif')
                                    <span class="badge badge-success">AKTIF</span>
                                @else
                                    <span class="badge badge-secondary">NONAKTIF</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.master.gelombang.toggle-status', $item->id) }}" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $item->status === 'aktif' ? 'btn-warning' : 'btn-success' }}">
                                        {{ $item->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection