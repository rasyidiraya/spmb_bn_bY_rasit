@unless(request()->routeIs('login') || request()->is('registrasi'))
  <header id="header" class="header d-flex align-items-center sticky-top" style="background: linear-gradient(135deg, #003b73 0%, #0074b7 100%);">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="/" class="logo d-flex align-items-center">
                <img src="{{ asset('assets/img/bn.png') }}" alt="Logo" style="height: 40px; margin-right: 10px;">
        <h1 class="sitename" style="color: white;">SPMB666</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          @auth('pengguna')
            <li><a href="{{ route('pendaftar.dashboard') }}" class="active" style="color: white;">Dashboard</a></li>
            <li><a href="{{ route('pendaftar.pendaftaran') }}" style="color: white;">Formulir Pendaftaran</a></li>
            <li><a href="{{ route('pendaftar.upload-berkas') }}" style="color: white;">Upload Berkas</a></li>
            <li><a href="{{ route('pendaftar.status') }}" style="color: white;">Status Pendaftaran</a></li>
            <li><a href="{{ route('pendaftar.pembayaran') }}" style="color: white;">Pembayaran</a></li>
            <li><a href="{{ route('pendaftar.cetak-kartu') }}" style="color: white;">Cetak Kartu</a></li>
            <li><span style="color: white; margin-right: 10px;">{{ Auth::guard('pengguna')->user()->nama }}</span></li>
            <li><a href="{{ route('logout') }}" class="btn-login" style="background: #dc2626; color: white;">Logout</a></li>
          @else
            <li><a href="{{ route('home') }}" class="active" style="color: white;">Beranda</a></li>
            <li><a href="{{ route('pendaftar.register') }}" style="color: white;">Registrasi Akun</a></li>
            <li><a href="{{ route('login') }}" class="btn-login" style="background: #0074b7; color: white;">Login</a></li>
          @endauth
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list" style="color: white;"></i>
      </nav>

    </div>
  </header>
@endunless