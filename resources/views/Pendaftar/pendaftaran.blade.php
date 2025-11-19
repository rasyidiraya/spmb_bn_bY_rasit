@extends('welcome')

@section('content')
<style>
.section {
  min-height: 100vh;
  padding-bottom: 100px;
}
</style>

@php
  $userId = auth('pengguna')->id();
  $existingPendaftar = \App\Models\Pendaftar\Pendaftar::where('user_id', $userId)->first();
@endphp

@if($existingPendaftar)
<script>
  document.addEventListener('DOMContentLoaded', function() {
    @if($existingPendaftar->status == 'SUBMIT')
      alert('Pendaftaran sedang diverifikasi! Cek status pendaftaran.');
      window.location.href = '{{ route("pendaftar.status") }}';
    @elseif($existingPendaftar->status == 'ADM_PASS')
      alert('Pendaftaran sudah diverifikasi! Silakan lanjut ke pembayaran.');
      window.location.href = '{{ route("pendaftar.pembayaran") }}';
    @elseif($existingPendaftar->status == 'PAYMENT_PENDING')
      alert('Menunggu konfirmasi pembayaran! Cek status pendaftaran.');
      window.location.href = '{{ route("pendaftar.status") }}';
    @elseif($existingPendaftar->status == 'PAID')
      alert('Anda sudah terdaftar sebagai siswa dan tidak dapat mengubah data pendaftaran!');
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
          <h2>Formulir Pendaftaran SPMB</h2>
          <p class="text-muted">Lengkapi data diri untuk melanjutkan pendaftaran</p>
        </div>
      </div>
    </div>
    
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card shadow">
          <div class="card-body p-4">
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
            
            <form action="{{ route('pendaftar.pendaftaran.store') }}" method="POST">
              @csrf
              <!-- Data Siswa -->
              <div class="mb-4">
                <h5 class="text-primary mb-3">Data Siswa</h5>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $oldData->nama_lengkap ?? '') }}" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">NIK</label>
                    <input type="text" name="nik" class="form-control" value="{{ old('nik', $oldData->nik ?? '') }}" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">NISN</label>
                    <input type="text" name="nisn" class="form-control" value="{{ old('nisn', $oldData->nisn ?? '') }}" placeholder="Opsional">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $oldData->tempat_lahir ?? '') }}" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $oldData->tanggal_lahir ?? '') }}" max="{{ date('Y-m-d') }}" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control" required>
                      <option value="">Pilih</option>
                      <option value="L" {{ old('jenis_kelamin', $oldData->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                      <option value="P" {{ old('jenis_kelamin', $oldData->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Agama</label>
                    <select name="agama" class="form-control" required>
                      <option value="">Pilih</option>
                      <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                      <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                      <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                      <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                      <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                      <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- Data Orang Tua -->
              <div class="mb-4">
                <h5 class="text-primary mb-3">Data Orang Tua/Wali</h5>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Ayah</label>
                    <input type="text" name="nama_ayah" class="form-control" value="{{ old('nama_ayah', $oldData->nama_ayah ?? '') }}" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Ibu</label>
                    <input type="text" name="nama_ibu" class="form-control" value="{{ old('nama_ibu', $oldData->nama_ibu ?? '') }}" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Pekerjaan Ayah</label>
                    <input type="text" name="pekerjaan_ayah" class="form-control" value="{{ old('pekerjaan_ayah', $oldData->pekerjaan_ayah ?? '') }}" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Pekerjaan Ibu</label>
                    <input type="text" name="pekerjaan_ibu" class="form-control" value="{{ old('pekerjaan_ibu', $oldData->pekerjaan_ibu ?? '') }}" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">No. HP Ayah</label>
                    <input type="tel" name="hp_ayah" class="form-control" value="{{ old('hp_ayah', $oldData->hp_ayah ?? '') }}" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">No. HP Ibu</label>
                    <input type="tel" name="hp_ibu" class="form-control" value="{{ old('hp_ibu', $oldData->hp_ibu ?? '') }}" required>
                  </div>
                </div>
              </div>

              <!-- Asal Sekolah -->
              <div class="mb-4">
                <h5 class="text-primary mb-3">Asal Sekolah</h5>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Sekolah</label>
                    <input type="text" name="nama_sekolah" class="form-control" value="{{ old('nama_sekolah', $oldData->nama_sekolah ?? '') }}" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">NPSN</label>
                    <input type="text" name="npsn" class="form-control" value="{{ old('npsn', $oldData->npsn ?? '') }}" required>
                  </div>
                  <div class="col-md-12 mb-3">
                    <label class="form-label">Alamat Sekolah</label>
                    <textarea name="alamat_sekolah" class="form-control" rows="2" required>{{ old('alamat_sekolah') }}</textarea>
                  </div>
                </div>
              </div>

              <!-- Alamat Domisili -->
              <div class="mb-4">
                <h5 class="text-primary mb-3">Alamat Domisili</h5>
                <div class="row">
                  <div class="col-md-12 mb-3">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="alamat_lengkap" class="form-control" rows="3" required>{{ old('alamat_lengkap') }}</textarea>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Provinsi</label>
                    <select name="provinsi" id="provinsi" class="form-control" required>
                      <option value="">Pilih Provinsi</option>
                    </select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Kabupaten/Kota</label>
                    <select name="kabupaten" id="kabupaten" class="form-control" required disabled>
                      <option value="">Pilih Kabupaten/Kota</option>
                    </select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Kecamatan</label>
                    <select name="kecamatan" id="kecamatan" class="form-control" required disabled>
                      <option value="">Pilih Kecamatan</option>
                    </select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Kelurahan</label>
                    <select name="wilayah_id" id="kelurahan" class="form-control" required disabled>
                      <option value="">Pilih Kelurahan</option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- Pilihan Jurusan -->
              <div class="mb-4">
                <h5 class="text-primary mb-3">Pilihan Jurusan</h5>
                <div class="row">
                  @php
                    $jurusanList = \App\Models\Pendaftar\Jurusan::all();
                    $gelombangAktif = \App\Models\Pendaftar\Gelombang::where('status', 'aktif')
                        ->first();
                  @endphp
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Pilihan Jurusan</label>
                    <select name="jurusan_id" class="form-control" required>
                      <option value="">Pilih Jurusan</option>
                      @foreach($jurusanList as $jurusan)
                        <option value="{{ $jurusan->id }}" {{ old('jurusan_id', $oldData->jurusan_id ?? '') == $jurusan->id ? 'selected' : '' }}>{{ $jurusan->nama }} ({{ $jurusan->kode }})</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Gelombang Pendaftaran</label>
                    @if($gelombangAktif)
                      <input type="hidden" name="gelombang_id" value="{{ $gelombangAktif->id }}">
                      <div class="form-control" style="background: #e9ecef; cursor: not-allowed;">
                        {{ $gelombangAktif->nama }} - {{ $gelombangAktif->tahun }} (Rp {{ number_format($gelombangAktif->biaya_daftar, 0, ',', '.') }})
                      </div>
                      <small class="text-success">Gelombang aktif saat ini</small>
                    @else
                      <div class="form-control" style="background: #f8d7da; color: #721c24;">
                        Tidak ada gelombang aktif
                      </div>
                      <small class="text-danger">Pendaftaran sedang ditutup</small>
                    @endif
                  </div>
                </div>
              </div>



              <div class="text-center">
                <button type="submit" class="btn btn-primary">Kirim Pendaftaran</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Modal Syarat dan Ketentuan -->
<style>
.modal {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1055;
  width: 100%;
  height: 100%;
  overflow-x: hidden;
  overflow-y: auto;
  outline: 0;
}
.modal.show {
  display: block !important;
}
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1050;
  width: 100vw;
  height: 100vh;
  background-color: #000;
  opacity: 0.5;
}
</style>
<div class="modal fade" id="syaratKetentuanModal" tabindex="-1" aria-labelledby="syaratKetentuanModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="syaratKetentuanModalLabel">Syarat dan Ketentuan Pendaftaran SPMB</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
        <div class="terms-content">
          <h6>1. Ketentuan Umum</h6>
          <ul>
            <li>Pendaftar wajib mengisi formulir pendaftaran dengan data yang benar dan lengkap</li>
            <li>Setiap pendaftar hanya diperbolehkan mendaftar satu kali dalam satu gelombang</li>
            <li>Pendaftaran yang sudah disubmit tidak dapat dibatalkan</li>
          </ul>
          
          <h6>2. Persyaratan Pendaftaran</h6>
          <ul>
            <li>Lulusan SMP/MTs atau sederajat</li>
            <li>Memiliki ijazah atau surat keterangan lulus</li>
            <li>Berusia maksimal 21 tahun pada saat pendaftaran</li>
            <li>Sehat jasmani dan rohani</li>
          </ul>
          
          <h6>3. Dokumen yang Diperlukan</h6>
          <ul>
            <li>Fotokopi ijazah atau surat keterangan lulus yang dilegalisir</li>
            <li>Fotokopi rapor semester 1-5 yang dilegalisir</li>
            <li>Fotokopi akta kelahiran</li>
            <li>Fotokopi kartu keluarga</li>
            <li>Pas foto 3x4 sebanyak 3 lembar</li>
          </ul>
          
          <h6>4. Biaya Pendaftaran</h6>
          <ul>
            <li>Biaya pendaftaran sesuai dengan gelombang yang dipilih</li>
            <li>Biaya yang sudah dibayar tidak dapat dikembalikan</li>
            <li>Pembayaran dilakukan setelah verifikasi administrasi</li>
          </ul>
          
          <h6>5. Proses Seleksi</h6>
          <ul>
            <li>Verifikasi berkas administrasi</li>
            <li>Tes akademik (jika diperlukan)</li>
            <li>Wawancara (jika diperlukan)</li>
            <li>Pengumuman hasil seleksi</li>
          </ul>
          
          <h6>6. Ketentuan Lain</h6>
          <ul>
            <li>Keputusan panitia seleksi bersifat final dan tidak dapat diganggu gugat</li>
            <li>Pendaftar yang memberikan data palsu akan didiskualifikasi</li>
            <li>Sekolah berhak menolak pendaftaran yang tidak memenuhi syarat</li>
          </ul>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary">Tutup</button>
        <button type="button" class="btn btn-primary">Saya Setuju</button>
      </div>
    </div>
  </div>
</div>

<script>
// Auto save form data to localStorage
const form = document.querySelector('form');
const formData = {};

// Load saved data on page load
window.addEventListener('load', function() {
    const saved = localStorage.getItem('pendaftaran_draft');
    if (saved) {
        const data = JSON.parse(saved);
        Object.keys(data).forEach(key => {
            const field = form.querySelector(`[name="${key}"]`);
            if (field) {
                field.value = data[key];
            }
        });
    }
});

// Save data on input change
form.addEventListener('input', function(e) {
    if (e.target.name) {
        formData[e.target.name] = e.target.value;
        localStorage.setItem('pendaftaran_draft', JSON.stringify(formData));
    }
});



// Clear draft on successful submit
form.addEventListener('submit', function() {
    localStorage.removeItem('pendaftaran_draft');
});

// Dropdown Wilayah Cascade
const provinsiSelect = document.getElementById('provinsi');
const kabupatenSelect = document.getElementById('kabupaten');
const kecamatanSelect = document.getElementById('kecamatan');
const kelurahanSelect = document.getElementById('kelurahan');

// Load provinsi on page load
fetch('/api/wilayah/provinsi')
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            provinsiSelect.innerHTML += `<option value="${item.provinsi}">${item.provinsi}</option>`;
        });
    });

// Provinsi change event
provinsiSelect.addEventListener('change', function() {
    const provinsi = this.value;
    kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
    kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
    kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
    
    if (provinsi) {
        kabupatenSelect.disabled = false;
        fetch(`/api/wilayah/kabupaten/${encodeURIComponent(provinsi)}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(item => {
                    kabupatenSelect.innerHTML += `<option value="${item.kabupaten}">${item.kabupaten}</option>`;
                });
            });
    } else {
        kabupatenSelect.disabled = true;
        kecamatanSelect.disabled = true;
        kelurahanSelect.disabled = true;
    }
});

// Kabupaten change event
kabupatenSelect.addEventListener('change', function() {
    const provinsi = provinsiSelect.value;
    const kabupaten = this.value;
    kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
    kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
    
    if (kabupaten) {
        kecamatanSelect.disabled = false;
        fetch(`/api/wilayah/kecamatan/${encodeURIComponent(provinsi)}/${encodeURIComponent(kabupaten)}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(item => {
                    kecamatanSelect.innerHTML += `<option value="${item.kecamatan}">${item.kecamatan}</option>`;
                });
            });
    } else {
        kecamatanSelect.disabled = true;
        kelurahanSelect.disabled = true;
    }
});

// Kecamatan change event
kecamatanSelect.addEventListener('change', function() {
    const provinsi = provinsiSelect.value;
    const kabupaten = kabupatenSelect.value;
    const kecamatan = this.value;
    kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
    
    if (kecamatan) {
        kelurahanSelect.disabled = false;
        fetch(`/api/wilayah/kelurahan/${encodeURIComponent(provinsi)}/${encodeURIComponent(kabupaten)}/${encodeURIComponent(kecamatan)}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(item => {
                    kelurahanSelect.innerHTML += `<option value="${item.id}">${item.kelurahan} (${item.kodepos})</option>`;
                });
            });
    } else {
        kelurahanSelect.disabled = true;
    }
});

// Simple modal functions
function showModal() {
    const modal = document.getElementById('syaratKetentuanModal');
    modal.style.display = 'block';
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
    
    // Create backdrop
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop fade show';
    backdrop.id = 'customBackdrop';
    document.body.appendChild(backdrop);
}

function closeModal() {
    const modal = document.getElementById('syaratKetentuanModal');
    modal.style.display = 'none';
    modal.classList.remove('show');
    document.body.style.overflow = '';
    
    const backdrop = document.getElementById('customBackdrop');
    if (backdrop) {
        backdrop.remove();
    }
}

function agreeTerms() {
    document.getElementById('syarat_ketentuan').checked = true;
    closeModal();
}

// Setup modal buttons after DOM loads
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('syaratKetentuanModal');
    const setujuBtn = modal.querySelector('.btn-primary');
    const closeBtn = modal.querySelector('.btn-secondary');
    const xBtn = modal.querySelector('.btn-close');
    
    if (setujuBtn) setujuBtn.onclick = agreeTerms;
    if (closeBtn) closeBtn.onclick = closeModal;
    if (xBtn) xBtn.onclick = closeModal;
    
    // Close on backdrop click
    document.onclick = function(e) {
        if (e.target && e.target.id === 'customBackdrop') {
            closeModal();
        }
    };
});
</script>
@endsection