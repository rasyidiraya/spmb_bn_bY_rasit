@extends('welcome')

@section('content')

@if(!$pendaftar)
<script>
  document.addEventListener('DOMContentLoaded', function() {
    alert('Silakan isi data pendaftaran terlebih dahulu!');
    window.location.href = '{{ route("pendaftar.pendaftaran") }}';
  });
</script>
@elseif($pendaftar && !in_array($pendaftar->status, ['ADM_REJECT', 'PAYMENT_REJECT']))
<script>
  document.addEventListener('DOMContentLoaded', function() {
    @if($pendaftar->status == 'SUBMIT')
      alert('Pendaftaran sedang diverifikasi! Cek status pendaftaran.');
      window.location.href = '{{ route("pendaftar.status") }}';
    @elseif($pendaftar->status == 'ADM_PASS')
      alert('Berkas sudah diverifikasi! Silakan lanjut ke pembayaran.');
      window.location.href = '{{ route("pendaftar.pembayaran") }}';
    @elseif($pendaftar->status == 'PAYMENT_PENDING')
      alert('Menunggu konfirmasi pembayaran! Cek status pendaftaran.');
      window.location.href = '{{ route("pendaftar.status") }}';
    @elseif($pendaftar->status == 'PAID')
      alert('Anda sudah terdaftar sebagai siswa dan tidak dapat mengubah berkas.');
      window.location.href = '{{ route("pendaftar.status") }}';
    @endif
  });
</script>
@endif

<section class="section">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="text-center mb-5">
          <h2>Upload Berkas Pendaftaran</h2>
          <p class="text-muted">Upload dokumen persyaratan pendaftaran (Format: PDF/JPG, Max: 2MB)</p>
        </div>
      </div>
    </div>
    
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card shadow">
          <div class="card-body p-4">
            <form action="{{ route('pendaftar.upload-berkas.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              
              @if($errors->any())
                <div class="alert alert-danger">
                  <ul class="mb-0">
                    @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif
              
              @if(session('success'))
                <div class="alert alert-success">
                  {{ session('success') }}
                </div>
              @endif
            <div class="row">
              <!-- Ijazah/Rapor -->
              <div class="col-md-6 mb-4">
                <div class="upload-item border rounded p-3">
                  <div class="text-center mb-3">
                    <i class="bi bi-file-earmark-text text-primary" style="font-size: 2rem;"></i>
                    <h6 class="mt-2">Ijazah/Rapor</h6>
                    <small class="text-muted">Wajib</small>
                  </div>
                  <input type="file" name="ijazah" class="form-control mb-2" accept=".pdf,.jpg,.jpeg,.png" required>
                  <div class="status-badge">
                    <span class="badge bg-warning">Belum Upload</span>
                  </div>
                </div>
              </div>

              <!-- KIP/KKS -->
              <div class="col-md-6 mb-4">
                <div class="upload-item border rounded p-3">
                  <div class="text-center mb-3">
                    <i class="bi bi-card-text text-primary" style="font-size: 2rem;"></i>
                    <h6 class="mt-2">KIP/KKS</h6>
                    <small class="text-muted">Opsional</small>
                  </div>
                  <input type="file" name="kip" class="form-control mb-2" accept=".pdf,.jpg,.jpeg,.png">
                  <div class="status-badge">
                    <span class="badge bg-secondary">Tidak Wajib</span>
                  </div>
                </div>
              </div>

              <!-- Akta Kelahiran -->
              <div class="col-md-6 mb-4">
                <div class="upload-item border rounded p-3">
                  <div class="text-center mb-3">
                    <i class="bi bi-file-person text-primary" style="font-size: 2rem;"></i>
                    <h6 class="mt-2">Akta Kelahiran</h6>
                    <small class="text-muted">Wajib</small>
                  </div>
                  <input type="file" name="akta" class="form-control mb-2" accept=".pdf,.jpg,.jpeg,.png" required>
                  <div class="status-badge">
                    <span class="badge bg-warning">Belum Upload</span>
                  </div>
                </div>
              </div>

              <!-- Kartu Keluarga -->
              <div class="col-md-6 mb-4">
                <div class="upload-item border rounded p-3">
                  <div class="text-center mb-3">
                    <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                    <h6 class="mt-2">Kartu Keluarga</h6>
                    <small class="text-muted">Wajib</small>
                  </div>
                  <input type="file" name="kk" class="form-control mb-2" accept=".pdf,.jpg,.jpeg,.png" required>
                  <div class="status-badge">
                    <span class="badge bg-warning">Belum Upload</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Progress -->
            <div class="mt-4">
              <h6>Progress Upload: <span class="text-primary">0/3 Berkas Wajib</span></h6>
              <div class="progress mb-3">
                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
              </div>
            </div>



            <div class="text-center mt-4">
              <button type="submit" class="btn btn-primary">Upload Berkas & Lanjutkan</button>
              <p class="text-muted mt-2"><small>Pastikan semua berkas wajib sudah dipilih</small></p>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Add spacing to push footer down -->
<div style="min-height: 200px;"></div>
@endsection