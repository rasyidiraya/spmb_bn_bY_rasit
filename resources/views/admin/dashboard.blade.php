@extends('main-admin')
@section('content-admin')

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>
            
            <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn" type="button" style="background: linear-gradient(135deg, #0074b7 0%, #60a3d9 100%); border: none; color: white;">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>
            
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin Panitia</span>
                        <img class="img-profile rounded-circle" src="https://via.placeholder.com/60x60">
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Dashboard Operasional</h1>
            </div>

            <!-- Filter -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form method="GET" class="row">
                        <div class="col-md-3">
                            <select name="jurusan_id" class="form-control">
                                <option value="">Semua Jurusan</option>
                                @foreach($jurusanList as $jurusan)
                                <option value="{{ $jurusan->id }}" {{ request('jurusan_id') == $jurusan->id ? 'selected' : '' }}>
                                    {{ $jurusan->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="gelombang_id" class="form-control">
                                <option value="">Semua Gelombang</option>
                                @foreach($gelombangList as $gelombang)
                                <option value="{{ $gelombang->id }}" {{ request('gelombang_id') == $gelombang->id ? 'selected' : '' }}>
                                    {{ $gelombang->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn" style="background: linear-gradient(135deg, #0074b7 0%, #60a3d9 100%); border: none; color: white;">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Ringkasan Harian -->
            <div class="row">
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2" style="border-left: 4px solid #0074b7;">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #0074b7;">Pendaftar Hari Ini</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendaftarHariIni }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-plus fa-2x" style="color: #60a3d9;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2" style="border-left: 4px solid #60a3d9;">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #60a3d9;">Terverifikasi Hari Ini</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $terverifikasiHariIni }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x" style="color: #0074b7;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2" style="border-left: 4px solid #003b73;">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #003b73;">Terbayar Hari Ini</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $terbayarHariIni }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-money-bill-wave fa-2x" style="color: #0074b7;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik dan Tabel -->
            <div class="row">
                <!-- Grafik -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3" style="background: linear-gradient(135deg, #0074b7 0%, #60a3d9 100%); color: white;">
                            <h6 class="m-0 font-weight-bold">Grafik Pendaftaran 7 Hari Terakhir</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-area">
                                <canvas id="myAreaChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3" style="background: linear-gradient(135deg, #0074b7 0%, #60a3d9 100%); color: white;">
                            <h6 class="m-0 font-weight-bold">Status Pendaftar</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-pie pt-4 pb-2">
                                <canvas id="myPieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Per Jurusan -->
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3" style="background: linear-gradient(135deg, #0074b7 0%, #60a3d9 100%); color: white;">
                            <h6 class="m-0 font-weight-bold">Ringkasan Per Jurusan</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Jurusan</th>
                                            <th>Pendaftar</th>
                                            <th>Terverifikasi</th>
                                            <th>Terbayar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dataPerJurusan as $data)
                                        <tr>
                                            <td>{{ $data->nama }}</td>
                                            <td>{{ $data->total_pendaftar }}</td>
                                            <td>{{ $data->terverifikasi }}</td>
                                            <td>{{ $data->terbayar }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Per Gelombang -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3" style="background: linear-gradient(135deg, #0074b7 0%, #60a3d9 100%); color: white;">
                            <h6 class="m-0 font-weight-bold">Ringkasan Per Gelombang</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Gelombang</th>
                                            <th>Pendaftar</th>
                                            <th>Terverifikasi</th>
                                            <th>Terbayar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dataPerGelombang as $data)
                                        <tr>
                                            <td>{{ $data->nama }}</td>
                                            <td>{{ $data->total_pendaftar }}</td>
                                            <td>{{ $data->terverifikasi }}</td>
                                            <td>{{ $data->terbayar }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data untuk grafik
    var grafikData = @json($grafikData);

    // Area Chart
    var ctx = document.getElementById("myAreaChart");
    
    if (ctx && typeof Chart !== 'undefined') {
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: grafikData.map(item => item.hari),
                datasets: [{
                    label: "Jumlah Siswa",
                    backgroundColor: "rgba(0, 116, 183, 0.1)",
                    borderColor: "rgba(0, 116, 183, 1)",
                    borderWidth: 2,
                    fill: true,
                    data: grafikData.map(item => item.pendaftar),
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Pie Chart
    var totalPendaftar = {{ $dataPerJurusan->sum('total_pendaftar') }};
    var totalTerverifikasi = {{ $dataPerJurusan->sum('terverifikasi') }};
    var totalTerbayar = {{ $dataPerJurusan->sum('terbayar') }};

    var ctx2 = document.getElementById("myPieChart");
    if (ctx2) {
        var myPieChart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ["Terbayar", "Terverifikasi", "Pending"],
                datasets: [{
                    data: [totalTerbayar, totalTerverifikasi - totalTerbayar, totalPendaftar - totalTerverifikasi],
                    backgroundColor: ['#0074b7', '#60a3d9', '#003b73'],
                }],
            },
            options: {
                maintainAspectRatio: false,
            },
        });
    }
});
</script>

@endsection