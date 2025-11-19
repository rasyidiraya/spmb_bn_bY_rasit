@unless(request()->routeIs('login') || request()->is('registrasi'))
<footer id="footer" class="footer position-relative" style="background: linear-gradient(135deg, #1e40af 0%, #334155 100%); color: #f8fafc;">
  <div class="container">
    <div class="row py-4">
      <div class="col-md-6">
        <div class="d-flex align-items-center mb-3">
          <i class="bi bi-mortarboard me-2" style="font-size: 1.5rem; color: #3b82f6;"></i>
          <h5 class="mb-0" style="color: #f8fafc;">SPMB666</h5>
        </div>
        <p class="mb-2" style="color: #e2e8f0;">Sistem Penerimaan Mahasiswa Baru Online</p>
        <p class="mb-0" style="color: #e2e8f0;">
          <i class="bi bi-telephone me-2"></i>(021) 123-4567
          <span class="mx-2">|</span>
          <i class="bi bi-envelope me-2"></i>BN@gmail.sch.id
        </p>
      </div>
      <div class="col-md-6 text-md-end">
        <div class="mb-3">
          {{-- <a href="/login" class="me-3" style="color: #e2e8f0; text-decoration: none;">Login</a> --}}
          <a href="{{ route('pendaftar.register') }}" class="me-3" style="color: #e2e8f0; text-decoration: none;">Daftar</a>
          <a href="/status" class="me-3" style="color: #e2e8f0; text-decoration: none;">Cek Status</a>
          {{-- <a href="#" style="color: #e2e8f0; text-decoration: none;">Bantuan</a> --}}
        </div>
        <p class="mb-0" style="color: #e2e8f0;">© 2024 SPMB Online. All rights reserved.</p>
      </div>
    </div>
  </div>
</footer>
@endunless