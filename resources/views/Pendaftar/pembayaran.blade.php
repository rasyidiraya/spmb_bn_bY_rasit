@extends('welcome')

@section('content')
<style>
.section {
  min-height: 100vh;
  padding-bottom: 100px;
}
</style>

@if(!$pendaftar)
<script>
  document.addEventListener('DOMContentLoaded', function() {
    alert('Silakan isi data pendaftaran terlebih dahulu!');
    window.location.href = '{{ route("pendaftar.pendaftaran") }}';
  });
</script>
@elseif($pendaftar && $pendaftar->status == 'DRAFT')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    alert('Silakan upload berkas persyaratan terlebih dahulu!');
    window.location.href = '{{ route("pendaftar.upload-berkas") }}';
  });
</script>
@elseif($berkasCount < 3)
<script>
  document.addEventListener('DOMContentLoaded', function() {
    alert('Silakan upload berkas persyaratan terlebih dahulu!');
    window.location.href = '{{ route("pendaftar.upload-berkas") }}';
  });
</script>
@elseif($pendaftar && $pendaftar->status == 'SUBMIT')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    alert('Menunggu verifikasi administrator terlebih dahulu!');
    window.location.href = '{{ route("pendaftar.status") }}';
  });
</script>
@elseif($pendaftar && $pendaftar->status == 'ADM_REJECT')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    alert('Pendaftaran ditolak. Silakan hubungi admin atau daftar ulang.');
    window.location.href = '{{ route("pendaftar.status") }}';
  });
</script>
@elseif($pendaftar && $pendaftar->status == 'PAYMENT_PENDING')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    alert('Bukti pembayaran sudah diupload, menunggu konfirmasi keuangan.');
    window.location.href = '{{ route("pendaftar.status") }}';
  });
</script>
@elseif($pendaftar && $pendaftar->status == 'PAID')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    alert('Anda sudah terdaftar sebagai siswa dan tidak dapat mengubah pembayaran.');
    window.location.href = '{{ route("pendaftar.status") }}';
  });
</script>
@endif

<section class="section">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="text-center mb-5">
          <h2>Pembayaran Biaya Pendaftaran</h2>
          <p class="text-muted">Lakukan pembayaran untuk melanjutkan proses pendaftaran</p>
        </div>
      </div>
    </div>
    
    <div class="row justify-content-center">
      <div class="col-lg-8">
        @php
          $user = Auth::guard('pengguna')->user();
        @endphp
        
        @if($pendaftar && in_array($pendaftar->status, ['ADM_PASS', 'PAYMENT_PENDING']))
        <!-- Info Pembayaran -->
        <div class="card shadow mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-md-8">
                <h5>Detail Pembayaran</h5>
                <table class="table table-borderless">
                  <tr>
                    <td>No. Pendaftaran</td>
                    <td>: {{ $pendaftar->no_pendaftaran }}</td>
                  </tr>
                  <tr>
                    <td>Nama Pendaftar</td>
                    <td>: {{ $user->nama }}</td>
                  </tr>
                  <tr>
                    <td>Gelombang</td>
                    <td>: {{ $pendaftar->nama_gelombang ?? 'Belum dipilih' }}</td>
                  </tr>
                  <tr>
                    <td>Biaya Pendaftaran</td>
                    <td>: <strong class="text-primary">Rp {{ number_format($pendaftar->biaya_daftar ?? 0, 0, ',', '.') }}</strong></td>
                  </tr>
                  <tr>
                    <td>Batas Pembayaran</td>
                    <td>: <strong class="text-danger">{{ $pendaftar->tgl_selesai ? date('d F Y', strtotime($pendaftar->tgl_selesai)) : 'Belum ditentukan' }}</strong></td>
                  </tr>
                </table>
              </div>
              <div class="col-md-4 text-center">
                <div class="border rounded p-3">
                  <h3 class="text-primary mb-0">Rp {{ number_format($pendaftar->biaya_daftar ?? 0, 0, ',', '.') }}</h3>
                  <small class="text-muted">Total Pembayaran</small>
                </div>
              </div>
            </div>
          </div>
        </div>
        @else
        <!-- Tidak Bisa Bayar -->
        <div class="card shadow mb-4">
          <div class="card-body text-center">
            <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
            <h5 class="mt-3">Pembayaran Tidak Tersedia</h5>
            @if(!$pendaftar)
              <p class="text-muted">Anda belum melakukan pendaftaran SPMB</p>
              <a href="{{ route('pendaftar.pendaftaran') }}" class="btn btn-primary">Mulai Pendaftaran</a>
            @elseif($pendaftar->status == 'DRAFT')
              <p class="text-muted">Silakan upload berkas persyaratan terlebih dahulu</p>
              <a href="{{ route('pendaftar.upload-berkas') }}" class="btn btn-primary">Upload Berkas</a>
            @elseif($pendaftar->status == 'SUBMIT')
              <p class="text-muted">Menunggu verifikator administrator terlebih dahulu</p>
              <a href="{{ route('pendaftar.status') }}" class="btn btn-info">Cek Status</a>
            @elseif($pendaftar->status == 'ADM_REJECT')
              <p class="text-muted">Pendaftaran ditolak. Silakan hubungi admin</p>
            @elseif($pendaftar->status == 'PAYMENT_PENDING')
              <p class="text-muted">Bukti pembayaran sudah diupload, menunggu konfirmasi keuangan</p>
              <a href="{{ route('pendaftar.status') }}" class="btn btn-info">Cek Status</a>
            @elseif($pendaftar->status == 'PAID')
              <p class="text-muted">Pembayaran sudah selesai</p>
              <a href="{{ route('pendaftar.status') }}" class="btn btn-success">Lihat Status</a>
            @endif
          </div>
        </div>
        @endif

        <!-- Metode Pembayaran -->
        <div class="card shadow">
          <div class="card-body">
            <h5 class="mb-4">Pilih Metode Pembayaran</h5>
            
            <!-- Tab Navigation -->
            <ul class="nav nav-tabs mb-4" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#transfer">Transfer Bank</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#qris">QRIS</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#manual">Upload Bukti</a>
              </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content">
              <!-- Transfer Bank -->
              <div class="tab-pane fade show active" id="transfer">
                <div class="row">
                  <div class="col-md-6">
                    <div class="bank-option border rounded p-3 mb-3">
                      <div class="d-flex align-items-center">
                        <input type="radio" name="bank" class="form-check-input me-3">
                        <div>
                          <h6 class="mb-1">Bank BCA</h6>
                          <p class="text-muted mb-0">1234567890</p>
                          <small>a.n. SMK Bakti Nusantara 666</small>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="bank-option border rounded p-3 mb-3">
                      <div class="d-flex align-items-center">
                        <input type="radio" name="bank" class="form-check-input me-3">
                        <div>
                          <h6 class="mb-1">Bank Mandiri</h6>
                          <p class="text-muted mb-0">0987654321</p>
                          <small>a.n. SMK Bakti Nusantara 666</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="alert alert-info">
                  <h6><i class="bi bi-info-circle"></i> Instruksi Transfer:</h6>
                  <ol class="mb-0">
                    <li>Transfer sesuai nominal yang tertera</li>
                    <li>Gunakan kode unik: <strong>001234</strong></li>
                    <li>Simpan bukti transfer</li>
                    <li>Upload bukti transfer di tab "Upload Bukti"</li>
                  </ol>
                </div>
              </div>

              <!-- QRIS -->
              <div class="tab-pane fade" id="qris">
                <div class="text-center">
                  <div class="qr-code border rounded p-4 d-inline-block mb-3">
                    <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICA8cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2ZmZiIvPgogIDx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LXNpemU9IjE0Ij5RUiBDb2RlPC90ZXh0Pgo8L3N2Zz4K" alt="QR Code" style="width: 200px; height: 200px;">
                  </div>
                  <h6>Scan QR Code dengan Aplikasi Pembayaran</h6>
                  <p class="text-muted">Gunakan aplikasi seperti GoPay, OVO, DANA, atau ShopeePay</p>
                  <div class="alert alert-warning">
                    <strong>Nominal: Rp {{ number_format($pendaftar->biaya_daftar ?? 0, 0, ',', '.') }}</strong><br>
                    QR Code berlaku selama 15 menit
                  </div>
                </div>
              </div>

              <!-- Upload Bukti -->
              <div class="tab-pane fade" id="manual">
                @if($errors->any())
                <div class="alert alert-danger">
                  @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                  @endforeach
                </div>
                @endif
                
                <form method="POST" action="{{ route('pendaftar.pembayaran.store') }}" enctype="multipart/form-data">
                  @method('POST')
                  @csrf
                  <div class="mb-3">
                    <label class="form-label">Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="form-control" required>
                      <option value="">Pilih Metode</option>
                      <option value="transfer">Transfer Bank</option>
                      <option value="cash">Tunai (di sekolah)</option>
                      <option value="ewallet">E-Wallet</option>
                    </select>
                  </div>
                  
                  <div class="mb-3">
                    <label class="form-label">Tanggal Pembayaran</label>
                    <input type="date" name="tanggal_pembayaran" class="form-control" required>
                  </div>
                  
                  <div class="mb-3">
                    <label class="form-label">Nominal Pembayaran</label>
                    <input type="number" name="nominal" class="form-control" value="{{ $pendaftar->biaya_daftar ?? 0 }}" readonly>
                  </div>
                  
                  <div class="mb-3">
                    <label class="form-label">Upload Bukti Pembayaran</label>
                    <input type="file" name="bukti_pembayaran" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                    <small class="text-muted">Format: PDF, JPG, PNG (Max: 2MB)</small>
                  </div>
                  

                  
                  <button type="submit" class="btn btn-primary">Upload Bukti Pembayaran</button>
                </form>
              </div>
            </div>
          </div>
        </div>

        @if($pendaftar && $pendaftar->status == 'ADM_PASS')
        <!-- Status Pembayaran -->
        <div class="card shadow mt-4">
          <div class="card-body">
            <h6>Status Pembayaran</h6>
            <div class="d-flex align-items-center">
              @if($pendaftar->status == 'ADM_PASS')
                <span class="badge bg-warning me-2">Menunggu Pembayaran</span>
                <small class="text-muted">Pembayaran belum diterima</small>
              @elseif($pendaftar->status == 'PAYMENT_PENDING')
                <span class="badge bg-info me-2">Menunggu Konfirmasi</span>
                <small class="text-muted">Bukti pembayaran sedang diverifikasi</small>
              @endif
            </div>
            <div class="mt-3">
              <small class="text-muted">
                Setelah melakukan pembayaran, status akan berubah dalam 1x24 jam (hari kerja).
                Untuk pertanyaan hubungi: (021) 123-4567
              </small>
            </div>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</section>


@endsection