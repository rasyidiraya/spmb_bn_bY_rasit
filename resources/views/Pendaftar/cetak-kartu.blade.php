@extends('welcome')

@section('content')

@if(!$pendaftar || $pendaftar->status != 'PAID')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    alert('Kartu hanya dapat dicetak setelah pembayaran dikonfirmasi!');
    window.location.href = '{{ route("pendaftar.status") }}';
  });
</script>
@endif

<style>
.cetak-kartu {
  min-height: 100vh;
  padding-bottom: 100px;
}
</style>

<section class="section cetak-kartu">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="text-center mb-5">
          <h2>Cetak Kartu Pendaftaran</h2>
          <p class="text-muted">Download dan cetak kartu pendaftaran serta bukti pembayaran</p>
        </div>
      </div>
    </div>
    
    <div class="row justify-content-center">
      <div class="col-lg-8">
        @php
          $user = Auth::guard('pengguna')->user();
          $pendaftar = \App\Models\Pendaftar\Pendaftar::where('user_id', $user->id)->first();
        @endphp
        
        @if($pendaftar && $pendaftar->status == 'PAID')
        <!-- Status Verifikasi -->
        <div class="alert alert-success mb-4">
          <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-3" style="font-size: 1.5rem;"></i>
            <div>
              <h6 class="mb-1">Pembayaran Terverifikasi</h6>
              <small>Anda dapat mencetak kartu pendaftaran dan bukti pembayaran</small>
            </div>
          </div>
        </div>
        @else
        <!-- Belum Bisa Cetak -->
        <div class="alert alert-warning mb-4">
          <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-3" style="font-size: 1.5rem;"></i>
            <div>
              <h6 class="mb-1">Kartu Belum Tersedia</h6>
              @if(!$pendaftar)
                <small>Anda belum melakukan pendaftaran SPMB</small>
              @elseif($pendaftar->status != 'PAID')
                <small>Selesaikan pembayaran terlebih dahulu untuk mencetak kartu</small>
              @endif
            </div>
          </div>
        </div>
        @endif

        <!-- Kartu Pendaftaran -->
        <div class="card shadow mb-4">
          <div class="card-header text-white">
            <h5 class="mb-0"><i class="bi bi-card-text me-2"></i>Kartu Pendaftaran</h5>
          </div>
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h6>Kartu Pendaftaran SPMB 2025/2026</h6>
                <p class="text-muted mb-2">Kartu ini diperlukan saat mengikuti tes seleksi</p>
                @if($pendaftar)
                <ul class="list-unstyled mb-0">
                  <li><small><i class="bi bi-person"></i> {{ $user->nama }}</small></li>
                  <li><small><i class="bi bi-hash"></i> {{ $pendaftar->no_pendaftaran }}</small></li>
                  <li><small><i class="bi bi-calendar"></i> {{ $pendaftar->gelombang->nama ?? 'Belum dipilih' }} - {{ $pendaftar->gelombang->tahun ?? date('Y') }}</small></li>
                </ul>
                @endif
              </div>
              <div class="col-md-4 text-center">
                @if($pendaftar && $pendaftar->status == 'PAID')
                <button class="btn btn-lg mb-2 btn-cetak" onclick="printCard()">
                  <i class="bi bi-printer"></i> Cetak Kartu
                </button>
                <br>
                <a href="#" class="btn btn-sm btn-download">
                  <i class="bi bi-download"></i> Download PDF
                </a>
                @else
                <button class="btn btn-secondary btn-lg mb-2" disabled>
                  <i class="bi bi-printer"></i> Cetak Kartu
                </button>
                <br>
                <small class="text-muted">Selesaikan pembayaran dulu</small>
                @endif
              </div>
            </div>
          </div>
        </div>



        <!-- Preview Kartu -->
        <div class="card shadow kartu-preview" id="kartu-preview">
          <div class="card-header">
            <h6 class="mb-0">Preview Kartu Pendaftaran</h6>
          </div>
          <div class="card-body">
            <div class="kartu-container border p-4">
              <div class="text-center mb-3">
                <h5 class="mb-1 text-primary">SMK BAKTI NUSANTARA 666</h5>
                <h6 class="mb-0">KARTU PENDAFTARAN SPMB 2025/2026</h6>
                <hr>
              </div>
              
              <div class="row">
                <div class="col-8">
                  <table class="table table-borderless table-sm">
                    @if($pendaftar)
                    <tr>
                      <td width="120">No. Pendaftaran</td>
                      <td>: <strong>{{ $pendaftar->no_pendaftaran }}</strong></td>
                    </tr>
                    <tr>
                      <td>Nama Lengkap</td>
                      <td>: {{ $pendaftar->dataSiswa->nama ?? $user->nama }}</td>
                    </tr>
                    <tr>
                      <td>Tempat, Tgl Lahir</td>
                      <td>: {{ $pendaftar->dataSiswa->tmp_lahir ?? 'Belum diisi' }}, {{ $pendaftar->dataSiswa->tgl_lahir ? \Carbon\Carbon::parse($pendaftar->dataSiswa->tgl_lahir)->format('d F Y') : 'Belum diisi' }}</td>
                    </tr>
                    <tr>
                      <td>Asal Sekolah</td>
                      <td>: {{ $pendaftar->asalSekolah->nama_sekolah ?? 'Belum diisi' }}</td>
                    </tr>
                    <tr>
                      <td>Pilihan Jurusan</td>
                      <td>: {{ $pendaftar->jurusan->nama ?? 'Belum dipilih' }}</td>
                    </tr>
                    <tr>
                      <td>Gelombang</td>
                      <td>: {{ $pendaftar->gelombang->nama ?? 'Belum dipilih' }}</td>
                    </tr>
                    @endif
                  </table>
                </div>
                <div class="col-4 text-center">
                  <div class="border" style="width: 100px; height: 120px; margin: 0 auto; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
                    <small class="text-muted">Pas Foto<br>3x4</small>
                  </div>
                </div>
              </div>
              
              <div class="mt-3">
                <div class="row">
                  <div class="col-6">
                    <small>
                      <strong>Jadwal Tes:</strong><br>
                      Tanggal: Akan diinformasikan<br>
                      Waktu: 08:00 - 12:00 WIB<br>
                      Tempat: SMK Bakti Nusantara 666
                    </small>
                  </div>
                  <div class="col-6 text-end">
                    <small>
                      Jakarta, {{ now()->format('d F Y') }}<br><br>
                      <strong>SMK Bakti Nusantara 666</strong>
                    </small>
                  </div>
                </div>
              </div>
              
              <div class="mt-3 pt-2 border-top">
                <small class="text-muted">
                  <strong>Catatan:</strong> Kartu ini wajib dibawa saat mengikuti tes seleksi. 
                  Pastikan data yang tertera sudah benar.
                </small>
              </div>
            </div>
          </div>
        </div>

        <!-- Informasi Penting -->
        <div class="alert alert-info mt-4">
          <h6><i class="bi bi-info-circle"></i> Informasi Penting:</h6>
          <ul class="mb-0">
            <li>Kartu pendaftaran wajib dibawa saat daftar ulang</li>
            <li>Pastikan semua data pada kartu sudah benar sebelum mencetak</li>
            <li>Jika ada kesalahan data, segera hubungi panitia SPMB</li>
            <li>Simpan kartu pendaftaran sebagai bukti kelulusan</li>
            <li>Jadwal daftar ulang akan diinformasikan kemudian</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
function printCard() {
  document.getElementById('kartu-preview').style.display = 'block';
  setTimeout(() => {
    window.print();
  }, 500);
}



// Hide preview after print
window.addEventListener('afterprint', function() {
  document.getElementById('kartu-preview').style.display = 'none';
});
</script>


@endsection