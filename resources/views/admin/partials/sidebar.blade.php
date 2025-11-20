<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background: linear-gradient(180deg, #003b73 0%, #0074b7 100%);">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="sidebar-brand-text mx-3">SPMB Admin</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Manajemen Data
    </div>

    <!-- Nav Item - Monitoring -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.monitoring.berkas') }}">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Monitoring Berkas</span>
        </a>
    </li>

    <!-- Nav Item - Master Data -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.master.jurusan') }}">
            <i class="fas fa-fw fa-graduation-cap"></i>
            <span>Master Jurusan</span>
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.master.gelombang') }}">
            <i class="fas fa-fw fa-calendar-alt"></i>
            <span>Master Gelombang</span>
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.kelola-user') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Kelola User</span>
        </a>
    </li>



    <!-- Nav Item - Peta Sebaran -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.peta') }}">
            <i class="fas fa-fw fa-map-marked-alt"></i>
            <span>Peta Sebaran</span></a>
    </li>
    
    <!-- Nav Item - Laporan -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.laporan') }}">
            <i class="fas fa-fw fa-file-export"></i>
            <span>Laporan</span></a>
    </li>
    
    <!-- Nav Item - Log Aktivitas -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.log-aktivitas') }}">
            <i class="fas fa-fw fa-history"></i>
            <span>Log Aktivitas</span></a>
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