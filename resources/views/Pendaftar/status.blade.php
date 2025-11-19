@extends('welcome')

@section('content')
<style>
.section {
  min-height: 100vh;
  padding-bottom: 100px;
}
</style>

<section class="section">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="text-center mb-5">
          <h2>Status Pendaftaran</h2>
          <p class="text-muted">Pantau perkembangan pendaftaran Anda</p>
        </div>
      </div>
    </div>
    
    <div class="row justify-content-center">
      <div class="col-lg-10">
        @if($pendaftar && $pendaftar->status == 'ADM_PASS')
        <div class="alert status-alert-success" role="alert">
            <i class="bi bi-check-circle-fill"></i> <strong>Selamat!</strong> Verifikator administrator Anda telah DITERIMA. Silakan lanjutkan ke tahap pembayaran.
        </div>
        @elseif($pendaftar && $pendaftar->status == 'ADM_REJECT')
        <div class="alert alert-danger" role="alert">
            <i class="bi bi-x-circle-fill"></i> <strong>Maaf,</strong> verifikator administrator Anda DITOLAK. 
            <br><small>Silakan perbaiki berkas dan daftar ulang dengan data yang benar.</small>
            <div class="mt-3">
                <a href="{{ route('pendaftar.pendaftaran') }}" class="btn btn-sm btn-daftar-ulang">
                    <i class="bi bi-arrow-clockwise"></i> Daftar Ulang
                </a>
            </div>
        </div>
        @elseif($pendaftar && $pendaftar->status == 'PAYMENT_REJECT')
        <div class="alert alert-danger" role="alert">
            <i class="bi bi-x-circle-fill"></i> <strong>Maaf,</strong> pembayaran Anda DITOLAK. 
            <br><small>Silakan perbaiki bukti pembayaran dan daftar ulang dengan data yang benar.</small>
            <div class="mt-3">
                <a href="{{ route('pendaftar.pendaftaran') }}" class="btn btn-sm btn-daftar-ulang">
                    <i class="bi bi-arrow-clockwise"></i> Daftar Ulang
                </a>
            </div>
        </div>
        @endif
        @php
          $user = Auth::guard('pengguna')->user();
        @endphp
        
        @if($pendaftar)
        <!-- Info Pendaftar -->
        <div class="card shadow mb-4">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h5 class="mb-1">{{ $pendaftar->nama ?? $user->nama }}</h5>
                <p class="text-muted mb-1">No. Pendaftaran: <strong>{{ $pendaftar->no_pendaftaran }}</strong></p>
                <p class="text-muted mb-0">Pilihan Jurusan: <strong>{{ $pendaftar->nama_jurusan ?? 'Belum dipilih' }}</strong></p>
              </div>
              <div class="col-md-4 text-md-end">
                @php
                  $statusBadge = match($pendaftar->status) {
                    'SUBMIT' => ['class' => 'bg-warning', 'text' => 'Menunggu Verifikasi'],
                    'ADM_PASS' => ['class' => 'bg-info', 'text' => 'Menunggu Pembayaran'],
                    'ADM_REJECT' => ['class' => 'bg-danger', 'text' => 'Ditolak'],
                    'PAYMENT_PENDING' => ['class' => 'bg-warning', 'text' => 'Menunggu Konfirmasi Pembayaran'],
                    'PAYMENT_REJECT' => ['class' => 'bg-danger', 'text' => 'Pembayaran Ditolak'],
                    'PAID' => ['class' => 'bg-success', 'text' => 'Terbayar'],
                    default => ['class' => 'bg-secondary', 'text' => $pendaftar->status ?? 'Unknown']
                  };
                @endphp
                <span class="badge {{ $statusBadge['class'] }} fs-6">{{ $statusBadge['text'] }}</span>
              </div>
            </div>
          </div>
        </div>
        @else
        <!-- Belum Mendaftar -->
        <div class="card shadow mb-4">
          <div class="card-body text-center">
            <i class="bi bi-exclamation-circle text-warning" style="font-size: 3rem;"></i>
            <h5 class="mt-3">Belum Ada Pendaftaran</h5>
            <p class="text-muted">Anda belum melakukan pendaftaran SPMB</p>
            <a href="{{ route('pendaftar.pendaftaran') }}" class="btn btn-mulai-pendaftaran">Mulai Pendaftaran</a>
          </div>
        </div>
        @endif

        <!-- Timeline Status -->
        <div class="card shadow">
          <div class="card-body p-4">
            <h5 class="mb-4">Timeline Pendaftaran</h5>
            
            @if($pendaftar)
            <div class="timeline">
              <!-- Submit -->
              <div class="timeline-item {{ in_array($pendaftar->status, ['SUBMIT', 'ADM_PASS', 'ADM_REJECT', 'PAYMENT_PENDING', 'PAID']) ? 'completed' : 'pending' }}">
                <div class="timeline-marker">
                  <i class="bi bi-{{ in_array($pendaftar->status, ['SUBMIT', 'ADM_PASS', 'ADM_REJECT', 'PAYMENT_PENDING', 'PAID']) ? 'check-circle-fill' : 'circle text-muted' }}" style="{{ in_array($pendaftar->status, ['SUBMIT', 'ADM_PASS', 'ADM_REJECT', 'PAYMENT_PENDING', 'PAID']) ? 'color: #0074b7;' : '' }}"></i>
                </div>
                <div class="timeline-content">
                  <h6>Pendaftaran Dikirim</h6>
                  <p class="text-muted mb-1">Formulir pendaftaran telah dikirim</p>
                  <small style="color: {{ in_array($pendaftar->status, ['SUBMIT', 'ADM_PASS', 'ADM_REJECT', 'PAYMENT_PENDING', 'PAID']) ? '#0074b7' : '#6c757d' }};">{{ in_array($pendaftar->status, ['SUBMIT', 'ADM_PASS', 'ADM_REJECT', 'PAYMENT_PENDING', 'PAID']) ? 'Selesai - ' . \Carbon\Carbon::parse($pendaftar->created_at)->setTimezone('Asia/Jakarta')->format('d M Y, H:i') : 'Menunggu' }}</small>
                </div>
              </div>

              <!-- Verifikator Administrator -->
              <div class="timeline-item {{ in_array($pendaftar->status, ['ADM_PASS', 'PAYMENT_PENDING', 'PAID']) ? 'completed' : ($pendaftar->status == 'ADM_REJECT' ? 'rejected' : ($pendaftar->status == 'SUBMIT' ? 'active' : 'pending')) }}">
                <div class="timeline-marker">
                  @if(in_array($pendaftar->status, ['ADM_PASS', 'PAYMENT_PENDING', 'PAID']))
                    <i class="bi bi-check-circle-fill" style="color: #0074b7;"></i>
                  @elseif($pendaftar->status == 'ADM_REJECT')
                    <i class="bi bi-x-circle-fill text-danger"></i>
                  @elseif($pendaftar->status == 'SUBMIT')
                    <i class="bi bi-clock-fill" style="color: #60a3d9;"></i>
                  @else
                    <i class="bi bi-circle text-muted"></i>
                  @endif
                </div>
                <div class="timeline-content">
                  <h6>Verifikator Administrator</h6>
                  <p class="text-muted mb-1">{{ $pendaftar->status == 'ADM_REJECT' ? 'Berkas administrator ditolak' : 'Verifikasi berkas administrator' }}</p>
                  @if(in_array($pendaftar->status, ['ADM_PASS', 'PAYMENT_PENDING', 'PAID']))
                    <small style="color: #0074b7;">Selesai - {{ $pendaftar->tgl_verifikasi_adm ? \Carbon\Carbon::parse($pendaftar->tgl_verifikasi_adm)->setTimezone('Asia/Jakarta')->format('d M Y, H:i') : \Carbon\Carbon::parse($pendaftar->updated_at)->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}</small>
                  @elseif($pendaftar->status == 'ADM_REJECT')
                    <small class="text-danger">Ditolak - {{ $pendaftar->tgl_verifikasi_adm ? \Carbon\Carbon::parse($pendaftar->tgl_verifikasi_adm)->setTimezone('Asia/Jakarta')->format('d M Y, H:i') : \Carbon\Carbon::parse($pendaftar->updated_at)->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}</small>
                  @elseif($pendaftar->status == 'SUBMIT')
                    <small style="color: #60a3d9;">Dalam Proses</small>
                  @else
                    <small class="text-muted">Menunggu</small>
                  @endif
                </div>
              </div>

              <!-- Pembayaran -->
              <div class="timeline-item {{ $pendaftar->status == 'PAID' ? 'completed' : (in_array($pendaftar->status, ['ADM_PASS', 'PAYMENT_PENDING']) ? 'active' : 'pending') }}">
                <div class="timeline-marker">
                  @if($pendaftar->status == 'PAID')
                    <i class="bi bi-check-circle-fill" style="color: #0074b7;"></i>
                  @elseif(in_array($pendaftar->status, ['ADM_PASS', 'PAYMENT_PENDING']))
                    <i class="bi bi-clock-fill" style="color: #60a3d9;"></i>
                  @else
                    <i class="bi bi-circle text-muted"></i>
                  @endif
                </div>
                <div class="timeline-content">
                  <h6>Pembayaran</h6>
                  <p class="text-muted mb-1">Pembayaran biaya pendaftaran</p>
                  @if($pendaftar->status == 'PAID')
                    <small style="color: #0074b7;">Selesai - {{ \Carbon\Carbon::parse($pendaftar->updated_at)->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}</small>
                  @elseif($pendaftar->status == 'PAYMENT_PENDING')
                    <small style="color: #60a3d9;">Menunggu Konfirmasi</small>
                  @elseif($pendaftar->status == 'ADM_PASS')
                    <small style="color: #60a3d9;">Menunggu Pembayaran</small>
                    <div class="mt-2">
                      <a href="{{ route('pendaftar.pembayaran') }}" class="btn btn-sm btn-bayar-sekarang">Bayar Sekarang</a>
                    </div>
                  @else
                    <small class="text-muted">Menunggu</small>
                  @endif
                </div>
              </div>
            </div>
            @else
            <div class="text-center">
              <p class="text-muted">Belum ada data pendaftaran</p>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Add spacing to push footer down -->
<div class="min-height-365"></div>


@endsection