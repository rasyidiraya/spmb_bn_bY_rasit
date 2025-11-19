@extends('main-kepsek')
@section('content-kepsek')

<div id="content">
    <!-- Topbar -->
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Kepala Sekolah - KPI SPMB</h1>
        
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">Kepala Sekolah</span>
                    <img class="img-profile rounded-circle" src="{{ asset('assets2/img/undraw_profile.svg') }}" style="width: 40px; height: 40px;">
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow">
                    <a class="dropdown-item" href="{{ route('logout') }}">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        <!-- KPI Cards Section -->
        <div class="row mb-4">
            <!-- Total Pendaftar -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow h-100 py-2" style="border-left: 4px solid #0074b7;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #0074b7;">Total Pendaftar</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalPendaftar) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x" style="color: #60a3d9;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rasio vs Kuota -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow h-100 py-2" style="border-left: 4px solid #60a3d9;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #60a3d9;">Rasio vs Kuota</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $persentaseKuota }}%</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-percentage fa-2x" style="color: #0074b7;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terverifikasi -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow h-100 py-2" style="border-left: 4px solid #003b73;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #003b73;">Terverifikasi</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($rasioTerverifikasi) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x" style="color: #0074b7;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Kuota -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow h-100 py-2" style="border-left: 4px solid #0074b7;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #0074b7;">Total Kuota</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalKuota) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x" style="color: #60a3d9;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row mb-4">
            <!-- Tren Harian -->
            <div class="col-xl-8 col-lg-7 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3" style="background: linear-gradient(135deg, #0074b7 0%, #60a3d9 100%); color: white;">
                        <h6 class="m-0 font-weight-bold">Tren Pendaftaran Harian (7 Hari Terakhir)</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="trenHarianChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Verifikasi -->
            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3" style="background: linear-gradient(135deg, #0074b7 0%, #60a3d9 100%); color: white;">
                        <h6 class="m-0 font-weight-bold">Status Verifikasi</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Tables Section -->
        <div class="row mb-4">
            <!-- Komposisi Jurusan -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3" style="background: linear-gradient(135deg, #0074b7 0%, #60a3d9 100%); color: white;">
                        <h6 class="m-0 font-weight-bold">Komposisi per Jurusan</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Jurusan</th>
                                        <th>Pendaftar</th>
                                        <th>Kuota</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($komposisiJurusan as $item)
                                    <tr>
                                        <td>{{ $item['nama'] }}</td>
                                        <td>{{ $item['pendaftar'] }}</td>
                                        <td>{{ $item['kuota'] }}</td>
                                        <td>
                                            <span class="badge badge-{{ $item['persentase'] > 80 ? 'success' : ($item['persentase'] > 50 ? 'warning' : 'secondary') }}">
                                                {{ $item['persentase'] }}%
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Asal Sekolah -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3" style="background: linear-gradient(135deg, #0074b7 0%, #60a3d9 100%); color: white;">
                        <h6 class="m-0 font-weight-bold">Top 5 Asal Sekolah</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Nama Sekolah</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($asalSekolah as $sekolah)
                                    <tr>
                                        <td>{{ $sekolah->nama_sekolah }}</td>
                                        <td><span class="badge badge-primary">{{ $sekolah->jumlah }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wilayah Section -->
        <div class="row">
            <!-- Tabel Wilayah -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3" style="background: linear-gradient(135deg, #0074b7 0%, #60a3d9 100%); color: white;">
                        <h6 class="m-0 font-weight-bold">Komposisi Wilayah</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Wilayah</th>
                                        <th>Jumlah</th>
                                        <th>Persentase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($wilayah as $item)
                                    <tr>
                                        <td>{{ $item->wilayah }}</td>
                                        <td><span class="badge badge-info">{{ $item->jumlah }}</span></td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar" style="width: {{ $totalPendaftar > 0 ? round(($item->jumlah / $totalPendaftar) * 100, 1) : 0 }}%">
                                                    {{ $totalPendaftar > 0 ? round(($item->jumlah / $totalPendaftar) * 100, 1) : 0 }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik Wilayah -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3" style="background: linear-gradient(135deg, #0074b7 0%, #60a3d9 100%); color: white;">
                        <h6 class="m-0 font-weight-bold">Distribusi Wilayah</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="wilayahChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Wait for Chart.js to load
document.addEventListener('DOMContentLoaded', function() {
    // Check if Chart.js is loaded
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded');
        return;
    }

    try {
        // Tren Harian Chart
        const trenCtx = document.getElementById('trenHarianChart');
        if (trenCtx) {
            new Chart(trenCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($trenHarian->pluck('tanggal')->map(function($date) { return date('d/m', strtotime($date)); })) !!},
                    datasets: [{
                        label: 'Pendaftar',
                        data: {!! json_encode($trenHarian->pluck('jumlah')) !!},
                        borderColor: '#0074b7',
                        backgroundColor: 'rgba(0, 116, 183, 0.1),',
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        // Status Chart
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
            const statusData = {!! json_encode($statusVerifikasi->values()) !!};
            const statusLabels = {!! json_encode($statusVerifikasi->keys()) !!};

            // Filter out zero values for better visualization
            const filteredData = [];
            const filteredLabels = [];
            const filteredColors = [];
            const colors = ['#0074b7', '#60a3d9', '#003b73', '#0074b7', '#60a3d9', '#003b73'];

            statusData.forEach((value, index) => {
                if (value > 0) {
                    filteredData.push(value);
                    filteredLabels.push(statusLabels[index]);
                    filteredColors.push(colors[index % colors.length]);
                }
            });

            // Show message if no data
            if (filteredData.length === 0) {
                statusCtx.parentElement.innerHTML = '<p class="text-center text-muted">Belum ada data pendaftar</p>';
            } else {
                new Chart(statusCtx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: filteredLabels,
                        datasets: [{
                            data: filteredData,
                            backgroundColor: filteredColors
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }
        }

        // Wilayah Chart
        const wilayahCtx = document.getElementById('wilayahChart');
        if (wilayahCtx) {
            const wilayahData = {!! json_encode($wilayah->pluck('jumlah')) !!};
            const wilayahLabels = {!! json_encode($wilayah->pluck('wilayah')) !!};
            
            if (wilayahData.length > 0) {
                new Chart(wilayahCtx.getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: wilayahLabels,
                        datasets: [{
                            data: wilayahData,
                            backgroundColor: ['#0074b7', '#60a3d9', '#003b73', '#0074b7', '#60a3d9']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            } else {
                wilayahCtx.parentElement.innerHTML = '<p class="text-center text-muted">Belum ada data wilayah</p>';
            }
        }
    } catch (error) {
        console.error('Error creating charts:', error);
    }
});
</script>

@endsection