@extends('welcome')

@section('content')
<style>
.auth-wrapper {
  min-height: 100vh;
  padding-bottom: 100px;
}
</style>

<div class="auth-wrapper">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8">
        <div class="card auth-card shadow-lg">
          <div class="auth-header">
            <i class="bi bi-person-plus-fill"></i>
            <h2 class="fw-bold mb-2 ucapan">Daftar Akun Baru</h2>
            <p class="mb-0 opacity-75">Mulai perjalanan pendidikan Anda bersama kami</p>
          </div>
          
          <div class="card-body p-4 p-md-5">
            <!-- Tab Navigation -->

            
            @if($errors->any())
              <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                  <div>{{ $error }}</div>
                @endforeach
              </div>
            @endif
            
            <div class="tab-content">
              <div class="tab-pane fade show active" id="register">
                <!-- Step 1: Registration Form -->
                <div id="step1" style="display: block;">
                  <form id="registerForm">
                    @csrf
                    <div class="mb-3">
                      <label class="form-label fw-semibold">Nama Lengkap</label>
                      <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan nama lengkap" value="{{ old('nama') }}" required>
                      </div>
                    </div>
                    
                    <div class="mb-3">
                      <label class="form-label fw-semibold">Email</label>
                      <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                        <input type="email" name="email" id="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required>
                      </div>
                    </div>
                    
                    <div class="mb-3">
                      <label class="form-label fw-semibold">No. HP</label>
                      <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                        <input type="text" name="hp" id="hp" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('hp') }}" required>
                      </div>
                    </div>
                    
                    <div class="mb-3">
                      <label class="form-label fw-semibold">Password</label>
                      <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                      </div>
                    </div>
                    
                    <div class="mb-3">
                      <label class="form-label fw-semibold">Konfirmasi Password</label>
                      <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-shield-lock-fill"></i></span>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="••••••••" required>
                      </div>
                    </div>
                    
                    <div class="mb-3 form-check">
                      <input type="checkbox" class="form-check-input" id="terms" required>
                      <label class="form-check-label" for="terms">
                        Saya setuju dengan <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal" class="text-decoration-none" style="color: #0074b7;">syarat dan ketentuan</a>
                      </label>
                    </div>
                    
                    <button type="button" id="sendOtpBtn" class="btn btn-gradient w-100 mb-3">
                      <span id="sendOtpText"><i class="bi bi-send-fill me-2"></i>Kirim Kode OTP</span>
                      <span id="sendOtpSpinner" class="spinner-border spinner-border-sm ms-2" style="display: none;"></span>
                    </button>
                    
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100 mb-3">
                      <i class="bi bi-arrow-left me-2"></i>Kembali ke Beranda
                    </a>
                    
                    <div class="text-center">
                      <p class="text-muted mb-0">Sudah punya akun? 
                        <a href="{{ route('login') }}" class="fw-semibold text-decoration-none" style="color: #0074b7;">Login di sini</a>
                      </p>
                    </div>
                  </form>
                </div>
                
                <!-- Step 2: OTP Verification -->
                <div id="step2" style="display: none;">
                  <div class="text-center mb-4">
                    <div class="mb-3">
                      <i class="bi bi-envelope-check-fill" style="font-size: 4rem; color: #0074b7;"></i>
                    </div>
                    <h4 class="fw-bold">Verifikasi Email</h4>
                    <p class="text-muted mb-2">Kode OTP telah dikirim ke:</p>
                    <p class="fw-bold" style="color: #0074b7;" id="emailDisplay"></p>
                  </div>
                  
                  <form id="otpForm">
                    @csrf
                    <div class="mb-3">
                      <label class="form-label text-center d-block">Masukkan Kode OTP (6 digit)</label>
                      <div class="d-flex justify-content-center gap-2 mb-3">
                        <input type="text" class="form-control text-center otp-input" maxlength="1" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <input type="text" class="form-control text-center otp-input" maxlength="1" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <input type="text" class="form-control text-center otp-input" maxlength="1" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <input type="text" class="form-control text-center otp-input" maxlength="1" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <input type="text" class="form-control text-center otp-input" maxlength="1" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <input type="text" class="form-control text-center otp-input" maxlength="1" style="width: 50px; height: 50px; font-size: 1.5rem;">
                      </div>
                      <input type="hidden" name="otp" id="otpValue">
                    </div>
                    
                    <button type="button" id="verifyOtpBtn" class="btn btn-gradient w-100 mb-3">
                      <span id="verifyOtpText"><i class="bi bi-check-circle-fill me-2"></i>Verifikasi & Daftar</span>
                      <span id="verifyOtpSpinner" class="spinner-border spinner-border-sm ms-2" style="display: none;"></span>
                    </button>
                    
                    <div class="text-center">
                      <p class="text-muted mb-2">Tidak menerima kode?</p>
                      <button type="button" id="resendOtpBtn" class="btn" style="border: 2px solid #0074b7; color: #0074b7;">
                        <i class="bi bi-arrow-clockwise me-2"></i>
                        <span id="resendText">Kirim Ulang</span>
                        <span id="resendCountdown" style="display: none;"></span>
                      </button>
                      <div class="mt-3">
                        <button type="button" id="backToStep1" class="btn btn-link" style="color: #0074b7;">
                          <i class="bi bi-arrow-left me-2"></i>Kembali ke form
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Info Bantuan -->
        <div class="card info-card mt-4 shadow-sm">
          <div class="card-body text-center p-4">
            <i class="bi bi-headset" style="font-size: 2.5rem; color: #0074b7;"></i>
            <h6 class="mt-3 fw-bold">Butuh Bantuan?</h6>
            <p class="text-muted mb-3">Tim kami siap membantu Anda</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
              <a href="tel:02112345678" class="btn btn-sm" style="background: linear-gradient(135deg, #0074b7 0%, #60a3d9 100%); color: white; border: none;">
                <i class="bi bi-telephone-fill me-2"></i>(021) 123-4567
              </a>
              <a href="mailto:spmb@sekolah.sch.id" class="btn btn-sm" style="background: linear-gradient(135deg, #0074b7 0%, #60a3d9 100%); color: white; border: none;">
                <i class="bi bi-envelope-fill me-2"></i>Email Kami
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Modal Syarat dan Ketentuan -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-gradient text-white border-0" style="background: linear-gradient(135deg, #003b73 0%, #0074b7 100%);">
        <h5 class="modal-title fw-bold" id="termsModalLabel">
          <i class="bi bi-shield-check me-2"></i>Syarat dan Ketentuan Pendaftaran
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4" style="background-color: #f8f9fa;">
        <div class="alert alert-info border-0 mb-4">
          <i class="bi bi-info-circle-fill me-2"></i>
          <strong>Penting!</strong> Harap baca dengan teliti sebelum melanjutkan pendaftaran.
        </div>

        <!-- Card 1 -->
        <div class="card border-0 shadow-sm mb-3">
          <div class="card-body">
            <h6 class="card-title fw-bold" style="color: #0074b7;">
              <i class="bi bi-1-circle-fill me-2"></i>Ketentuan Umum
            </h6>
            <p class="text-muted mb-2">Dengan mendaftar di sistem SPMB ini, Anda menyetujui untuk:</p>
            <ul class="list-unstyled ms-3">
              <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #0074b7;"></i>Memberikan data yang benar dan valid</li>
              <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #0074b7;"></i>Bertanggung jawab atas kebenaran data yang diinput</li>
              <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #0074b7;"></i>Menjaga kerahasiaan akun dan password</li>
            </ul>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="card border-0 shadow-sm mb-3">
          <div class="card-body">
            <h6 class="card-title fw-bold" style="color: #0074b7;">
              <i class="bi bi-2-circle-fill me-2"></i>Persyaratan Pendaftaran
            </h6>
            <ul class="list-unstyled ms-3">
              <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #0074b7;"></i>Calon peserta didik baru harus memiliki ijazah/SKHUN yang sah</li>
              <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #0074b7;"></i>Melengkapi seluruh dokumen yang dipersyaratkan</li>
              <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #0074b7;"></i>Mengikuti seluruh tahapan pendaftaran yang ditentukan</li>
            </ul>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="card border-0 shadow-sm mb-3">
          <div class="card-body">
            <h6 class="card-title fw-bold" style="color: #0074b7;">
              <i class="bi bi-3-circle-fill me-2"></i>Biaya Pendaftaran
            </h6>
            <ul class="list-unstyled ms-3">
              <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #0074b7;"></i>Biaya pendaftaran yang telah dibayarkan tidak dapat dikembalikan</li>
              <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #0074b7;"></i>Pembayaran dilakukan sesuai dengan nominal yang tertera</li>
              <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #0074b7;"></i>Bukti pembayaran harus diupload dengan jelas dan benar</li>
            </ul>
          </div>
        </div>

        <!-- Card 4 -->
        <div class="card border-0 shadow-sm mb-3">
          <div class="card-body">
            <h6 class="card-title fw-bold" style="color: #0074b7;">
              <i class="bi bi-4-circle-fill me-2"></i>Verifikasi Data
            </h6>
            <ul class="list-unstyled ms-3">
              <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #0074b7;"></i>Panitia berhak memverifikasi kebenaran data dan dokumen</li>
              <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #0074b7;"></i>Data yang tidak valid dapat menyebabkan pembatalan pendaftaran</li>
              <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #0074b7;"></i>Keputusan panitia bersifat mutlak dan tidak dapat diganggu gugat</li>
            </ul>
          </div>
        </div>

        <!-- Card 5 -->
        <div class="card border-0 shadow-sm mb-3">
          <div class="card-body">
            <h6 class="card-title fw-bold" style="color: #0074b7;">
              <i class="bi bi-5-circle-fill me-2"></i>Privasi dan Keamanan Data
            </h6>
            <ul class="list-unstyled ms-3">
              <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #0074b7;"></i>Data pribadi akan dijaga kerahasiaannya</li>
              <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #0074b7;"></i>Data hanya digunakan untuk keperluan pendaftaran</li>
              <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #0074b7;"></i>Sekolah tidak akan membagikan data ke pihak ketiga tanpa izin</li>
            </ul>
          </div>
        </div>

        <div class="alert alert-warning border-0 mt-4">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          <strong>Perhatian:</strong> Dengan mencentang kotak persetujuan, Anda dianggap telah membaca dan menyetujui seluruh syarat dan ketentuan di atas.
        </div>
      </div>
      <div class="modal-footer border-0 bg-white">
        <button type="button" class="btn px-4" style="background: linear-gradient(135deg, #0074b7 0%, #60a3d9 100%); color: white; border: none;" data-bs-dismiss="modal">
          <i class="bi bi-check-lg me-2"></i>Saya Mengerti
        </button>
      </div>
    </div>
  </div>
</div>

<script>
let otpTimer = 0;
let otpInterval;

// Send OTP
document.getElementById('sendOtpBtn').addEventListener('click', function() {
    const form = document.getElementById('registerForm');
    const formData = new FormData(form);
    
    // Validate form
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Show loading
    document.getElementById('sendOtpText').style.display = 'none';
    document.getElementById('sendOtpSpinner').style.display = 'inline-block';
    this.disabled = true;
    
    fetch('/register/send-otp', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('emailDisplay').textContent = formData.get('email');
            document.getElementById('step1').style.display = 'none';
            document.getElementById('step2').style.display = 'block';
            startResendTimer();
        } else {
            alert(data.message || 'Gagal mengirim OTP');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
    })
    .finally(() => {
        document.getElementById('sendOtpText').style.display = 'inline';
        document.getElementById('sendOtpSpinner').style.display = 'none';
        this.disabled = false;
    });
});

// OTP Input handling
document.querySelectorAll('.otp-input').forEach((input, index) => {
    input.addEventListener('input', function() {
        if (this.value.length === 1 && index < 5) {
            document.querySelectorAll('.otp-input')[index + 1].focus();
        }
        updateOtpValue();
    });
    
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' && this.value === '' && index > 0) {
            document.querySelectorAll('.otp-input')[index - 1].focus();
        }
    });
});

function updateOtpValue() {
    const otp = Array.from(document.querySelectorAll('.otp-input')).map(input => input.value).join('');
    document.getElementById('otpValue').value = otp;
}

// Verify OTP
document.getElementById('verifyOtpBtn').addEventListener('click', function() {
    const otp = document.getElementById('otpValue').value;
    
    if (otp.length !== 6) {
        alert('Masukkan kode OTP 6 digit');
        return;
    }
    
    // Show loading
    document.getElementById('verifyOtpText').style.display = 'none';
    document.getElementById('verifyOtpSpinner').style.display = 'inline-block';
    this.disabled = true;
    
    const registerData = new FormData(document.getElementById('registerForm'));
    registerData.append('otp', otp);
    
    fetch('/register/verify-otp', {
        method: 'POST',
        body: registerData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Registrasi berhasil!');
            window.location.href = data.redirect || '/home';
        } else {
            alert(data.message || 'Kode OTP salah');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
    })
    .finally(() => {
        document.getElementById('verifyOtpText').style.display = 'inline';
        document.getElementById('verifyOtpSpinner').style.display = 'none';
        this.disabled = false;
    });
});

// Resend OTP
document.getElementById('resendOtpBtn').addEventListener('click', function() {
    const formData = new FormData(document.getElementById('registerForm'));
    
    fetch('/register/resend-otp', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Kode OTP baru telah dikirim');
            startResendTimer();
        } else {
            alert(data.message || 'Gagal mengirim ulang OTP');
        }
    });
});

// Back to step 1
document.getElementById('backToStep1').addEventListener('click', function() {
    document.getElementById('step2').style.display = 'none';
    document.getElementById('step1').style.display = 'block';
    clearInterval(otpInterval);
});

// Resend timer
function startResendTimer() {
    otpTimer = 60;
    document.getElementById('resendOtpBtn').disabled = true;
    document.getElementById('resendText').style.display = 'none';
    document.getElementById('resendCountdown').style.display = 'inline';
    
    otpInterval = setInterval(() => {
        document.getElementById('resendCountdown').textContent = `Kirim ulang dalam ${otpTimer}s`;
        otpTimer--;
        
        if (otpTimer < 0) {
            clearInterval(otpInterval);
            document.getElementById('resendOtpBtn').disabled = false;
            document.getElementById('resendText').style.display = 'inline';
            document.getElementById('resendCountdown').style.display = 'none';
        }
    }, 1000);
}
</script>
@endsection