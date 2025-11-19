@extends('main-verifikator')
@section('content-verifikator')

<div id="content">
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>
        
        <h1 class="h3 mb-0 text-gray-800">Detail Verifikasi - {{ $pendaftar->no_pendaftaran }}</h1>
        
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">Verifikator</span>
                    <img class="img-profile rounded-circle" src="{{ asset('assets2/img/undraw_profile.svg') }}" style="width: 40px; height: 40px;">
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
            <!-- Data Pendaftar -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data Pendaftar</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr><td><strong>No Pendaftaran</strong></td><td>{{ $pendaftar->no_pendaftaran }}</td></tr>
                                    <tr><td><strong>Nama Lengkap</strong></td><td>{{ $pendaftar->nama }}</td></tr>
                                    <tr><td><strong>NIK</strong></td><td>{{ $pendaftar->nik }}</td></tr>
                                    <tr><td><strong>Tempat, Tgl Lahir</strong></td><td>{{ $pendaftar->tmp_lahir }}, {{ date('d/m/Y', strtotime($pendaftar->tgl_lahir)) }}</td></tr>
                                    <tr><td><strong>Jenis Kelamin</strong></td><td>{{ $pendaftar->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr><td><strong>Jurusan Pilihan</strong></td><td>{{ $pendaftar->nama }}</td></tr>
                                    <tr><td><strong>Gelombang</strong></td><td>{{ $pendaftar->nama }}</td></tr>
                                    <tr><td><strong>Nama Ayah</strong></td><td>{{ $pendaftar->nama_ayah }}</td></tr>
                                    <tr><td><strong>Nama Ibu</strong></td><td>{{ $pendaftar->nama_ibu }}</td></tr>
                                    <tr><td><strong>Asal Sekolah</strong></td><td>{{ $pendaftar->nama_sekolah }}</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Berkas -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Berkas Persyaratan</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Jenis Berkas</th>
                                        <th>Nama File</th>
                                        <th>Ukuran</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($berkas as $file)
                                    <tr>
                                        <td>{{ $file->jenis }}</td>
                                        <td>{{ $file->nama_file }}</td>
                                        <td>{{ $file->ukuran_kb }} KB</td>
                                        <td>
                                            <span class="badge badge-{{ $file->valid ? 'success' : 'warning' }}">
                                                {{ $file->valid ? 'Valid' : 'Belum Diverifikasi' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ asset('storage/'.$file->url) }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                            @if(!$file->valid)
                                            <form method="POST" action="{{ route('verifikator.verifikasi-berkas', $file->id) }}" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="valid" value="1">
                                                <button type="submit" class="btn btn-sm btn-success" title="Validasi Berkas">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            @else
                                            <form method="POST" action="{{ route('verifikator.verifikasi-berkas', $file->id) }}" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="valid" value="0">
                                                <button type="submit" class="btn btn-sm btn-warning" title="Batalkan Validasi">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada berkas yang diupload</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Verifikasi -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Verifikasi Administrator</h6>
                    </div>
                    <div class="card-body">
                        @if($pendaftar->status == 'SUBMIT')
                            @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                            @endif
                            
                            <form method="POST" action="{{ route('verifikator.verifikasi', $pendaftar->id) }}">
                                @csrf
                                <div class="form-group">
                                    <label><strong>Keputusan Verifikasi <span class="text-danger">*</span></strong></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="ADM_PASS" id="lulus" required>
                                        <label class="form-check-label text-success" for="lulus">
                                            <i class="fas fa-check-circle"></i> Lulus 
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="ADM_REJECT" id="tolak" required>
                                        <label class="form-check-label text-danger" for="tolak">
                                            <i class="fas fa-times-circle"></i> Tolak
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-save"></i> Simpan Verifikasi
                                    </button>
                                    <a href="{{ route('verifikator.index') }}" class="btn btn-secondary btn-block">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle"></i><br>
                                <strong>Data Sudah Diverifikasi</strong><br>
                                <small>Tidak dapat mengubah verifikasi yang sudah selesai</small>
                            </div>
                            <a href="{{ route('verifikator.riwayat') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Log Status -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Status Saat Ini</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            @if($pendaftar->status == 'SUBMIT')
                                <span class="badge badge-lg badge-warning">{{ $pendaftar->status }}</span>
                                <p class="mt-2 text-muted">Menunggu Verifikasi Administrator</p>
                            @elseif($pendaftar->status == 'ADM_PASS')
                                <span class="badge badge-lg badge-info">Menunggu Pembayaran</span>
                                <p class="mt-2 text-muted">Verifikasi Diterima</p>
                            @elseif($pendaftar->status == 'ADM_REJECT')
                                <span class="badge badge-lg badge-danger">Tidak Lolos</span>
                                <p class="mt-2 text-muted">Verifikasi Ditolak</p>
                            @elseif($pendaftar->status == 'PAYMENT_PENDING')
                                <span class="badge badge-lg badge-warning">Menunggu Konfirmasi Bayar</span>
                                <p class="mt-2 text-muted">Sudah Upload Bukti Bayar</p>
                            @elseif($pendaftar->status == 'PAID')
                                <span class="badge badge-lg badge-success">Selesai</span>
                                <p class="mt-2 text-muted">Pembayaran Terverifikasi</p>
                            @else
                                <span class="badge badge-lg badge-secondary">{{ $pendaftar->status }}</span>
                                <p class="mt-2 text-muted">Status Tidak Dikenal</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = document.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function(e) {
        const statusRadios = document.querySelectorAll('input[name="status"]');
        let isChecked = false;
        
        statusRadios.forEach(function(radio) {
            if (radio.checked) {
                isChecked = true;
            }
        });
        
        if (!isChecked) {
            e.preventDefault();
            alert('Silakan pilih keputusan verifikasi!');
            return false;
        }
    });
});
</script>

@endsection