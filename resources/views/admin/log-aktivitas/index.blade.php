@extends('main-admin')

@section('content-admin')
<div id="content">
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Log Aktivitas</h1>
        
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Riwayat Aktivitas Pengguna</h6>
            </div>
            <div class="card-body">
                
                <!-- Filter -->
                <form method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <select name="aksi" class="form-control form-control-sm">
                                <option value="">Semua Aksi</option>
                                @foreach($aksiList as $aksi)
                                    <option value="{{ $aksi }}" {{ request('aksi') == $aksi ? 'selected' : '' }}>
                                        {{ ucfirst($aksi) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="user" class="form-control form-control-sm" placeholder="Cari nama user..." value="{{ request('user') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                            <a href="{{ route('admin.log-aktivitas') }}" class="btn btn-secondary btn-sm">Reset</a>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>User</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr>
                                <td id="waktu-{{ $log->id }}" data-time="{{ $log->waktu->toISOString() }}">{{ $log->waktu->diffForHumans() }}</td>
                                <td>
                                    <strong>{{ $log->user->nama ?? 'User Dihapus' }}</strong><br>
                                    <small class="text-muted">{{ $log->user->role ?? '-' }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-{{ 
                                        $log->aksi == 'login' ? 'success' : 
                                        ($log->aksi == 'logout' ? 'warning' : 'secondary') 
                                    }}">
                                        {{ ucfirst($log->aksi) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                {{ $logs->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>


<script>
// Update waktu real-time setiap detik
setInterval(function() {
    document.querySelectorAll('[data-time]').forEach(function(element) {
        const waktu = new Date(element.getAttribute('data-time'));
        const now = new Date();
        const diff = Math.floor((now - waktu) / 1000);
        
        let timeText = '';
        if (diff < 60) {
            timeText = diff + ' detik yang lalu';
        } else if (diff < 3600) {
            timeText = Math.floor(diff / 60) + ' menit yang lalu';
        } else if (diff < 86400) {
            timeText = Math.floor(diff / 3600) + ' jam yang lalu';
        } else {
            const days = Math.floor(diff / 86400);
            timeText = days + ' hari yang lalu';
        }
        
        element.innerHTML = timeText;
    });
}, 1000);
</script>
@endsection