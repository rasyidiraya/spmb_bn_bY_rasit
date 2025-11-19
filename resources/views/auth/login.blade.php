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
      <div class="col-lg-5 col-md-7">
        <div class="card auth-card shadow-lg">
          <div class="auth-header">
            <i class="bi bi-shield-lock-fill"></i>
            <h2 class="fw-bold mb-2 ucapan" >Selamat Datang!</h2>
            <p class="mb-0 opacity-75">Sistem Penerimaan Mahasiswa Baru</p>
          </div>
          
          <div class="card-body p-4 p-md-5">
            @if($errors->any())
              <div class="alert alert-danger border-0 shadow-sm">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}
              </div>
            @endif
            
            <form method="POST" action="{{ route('login.post') }}">
              @csrf
              <div class="mb-4">
                <label class="form-label fw-semibold">Email</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                  <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                </div>
              </div>
              
              <div class="mb-4">
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                  <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
              </div>
              
              <button type="submit" class="btn btn-gradient w-100 mb-3">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk Sekarang
              </button>
              
              <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100 mb-4">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Beranda
              </a>
              
              <div class="text-center">
                <p class="text-muted mb-0">Belum punya akun? 
                  <a href="{{ route('pendaftar.register') }}" class="fw-semibold text-decoration-none" style="color: #0074b7;">Daftar di sini</a>
                </p>
              </div>
            </form>
          </div>
          
          <div class="card-footer bg-light text-center py-3 border-0">
            <small class="text-muted">
              <i class="bi bi-shield-check me-1"></i>Sistem Aman & Terpercaya
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


@endsection