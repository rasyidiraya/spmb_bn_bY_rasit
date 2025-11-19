<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background: linear-gradient(180deg, #003b73 0%, #0074b7 100%);">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('keuangan.dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Keuangan</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item active">
        <a class="nav-link" href="{{ route('keuangan.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('keuangan.verifikasi-pembayaran.index') }}">
            <i class="fas fa-fw fa-credit-card"></i>
            <span>Verifikasi Pembayaran</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('keuangan.riwayat') }}">
            <i class="fas fa-fw fa-history"></i>
            <span>Riwayat Verifikasi</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">Laporan</div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('keuangan.rekap.index') }}">
            <i class="fas fa-fw fa-chart-bar"></i>
            <span>Rekap Keuangan</span></a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <li class="nav-item">
        <a class="nav-link" href="{{ route('logout') }}">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Logout</span></a>
    </li>

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>