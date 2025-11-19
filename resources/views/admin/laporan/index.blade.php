@extends('main-admin')
@section('content-admin')

<div id="content">
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
        <h1 class="h3 mb-0 text-gray-800">Laporan Pendaftar</h1>
    </nav>

    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.laporan.export') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <label>Jurusan</label>
                            <select name="jurusan_id" class="form-control">
                                <option value="">Semua Jurusan</option>
                                @foreach($jurusan as $j)
                                <option value="{{ $j->id }}">{{ $j->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Gelombang</label>
                            <select name="gelombang_id" class="form-control">
                                <option value="">Semua Gelombang</option>
                                @foreach($gelombang as $g)
                                <option value="{{ $g->id }}">{{ $g->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="SUBMIT">Menunggu Verifikasi</option>
                                <option value="ADM_PASS">Lulus Verifikasi</option>
                                <option value="ADM_REJECT">Ditolak</option>
                                <option value="PAYMENT_PENDING">Menunggu Konfirmasi</option>
                                <option value="PAID">Lunas</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Format</label>
                            <select name="format" class="form-control" required>
                                <option value="excel">Excel</option>
                                <option value="pdf">PDF</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label>Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-download"></i> Export Laporan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection