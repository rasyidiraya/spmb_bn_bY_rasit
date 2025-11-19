@extends('main-verifikator')
@section('content-verifikator')

<div id="content">
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>
        
        <h1 class="h3 mb-0 text-gray-800">Verifikasi Administrator</h1>
        
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">Verifikator</span>
                    <img class="img-profile rounded-circle" src="https://via.placeholder.com/60x60">
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow">
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>

    <div class="container-fluid">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        @endif
        
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Pendaftar Menunggu Verifikasi</h6>
                
                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm" 
                           placeholder="Cari No. Pendaftaran / Nama" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary btn-sm ml-2">Cari</button>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No Pendaftaran</th>
                                <th>Nama</th>
                                <th>Jurusan</th>
                                <th>Gelombang</th>
                                <th>Tanggal Daftar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendaftar as $item)
                            <tr>
                                <td>{{ $item->no_pendaftaran }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->nama_jurusan }}</td>
                                <td>{{ $item->nama_gelombang }}</td>
                                <td>{{ date('d/m/Y H:i', strtotime($item->created_at)) }}</td>
                                <td>
                                    <span class="badge badge-warning">{{ $item->status }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('verifikator.detail', $item->id) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Detail & Verifikasi
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data pendaftar</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $pendaftar->links() }}
            </div>
        </div>
    </div>
</div>

@endsection