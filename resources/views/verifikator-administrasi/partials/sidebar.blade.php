<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background: linear-gradient(180deg, #003b73 0%, #0074b7 100%);">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('verifikator.index') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-user-check"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Verifikator</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Verifikasi -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('verifikator.index') }}">
            <i class="fas fa-fw fa-clipboard-check"></i>
            <span>Verifikator Administrator</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Menu Verifikator
    </div>

    <!-- Nav Item - Riwayat -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('verifikator.riwayat') }}">
            <i class="fas fa-fw fa-history"></i>
            <span>Riwayat Aktivitas</span></a>
    </li>
    
    <!-- Nav Item - Laporan -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('verifikator.laporan') }}">
            <i class="fas fa-fw fa-file-export"></i>
            <span>Laporan Verifikasi</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Nav Item - Logout -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('logout') }}">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Logout</span></a>
    </li>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>