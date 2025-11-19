@extends('main-admin')

@section('title', 'Peta Sebaran Pendaftar')

@section('content-admin')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Peta Sebaran Pendaftar</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select id="filterGelombang" class="form-control">
                                <option value="">Semua Gelombang</option>
                                @foreach($gelombang as $g)
                                    <option value="{{ $g->id }}">{{ $g->nama }} - {{ $g->tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="filterJurusan" class="form-control">
                                <option value="">Semua Jurusan</option>
                                @foreach($jurusan as $j)
                                    <option value="{{ $j->id }}">{{ $j->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="filterStatus" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="DRAFT">Draft</option>
                                <option value="SUBMIT">Submit</option>
                                <option value="ADM_PASS">Lolos Administrasi</option>
                                <option value="PAID">Sudah Bayar</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button id="refreshMap" class="btn btn-primary">Refresh Peta</button>
                        </div>
                    </div>
                    
                    <div id="map" style="height: 600px; width: 100%; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);"></div>
                    
                    <div class="mt-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <div style="width: 20px; height: 20px; background: #007bff; border-radius: 50%; display: inline-block; border: 2px solid #0056b3;"></div>
                                <small class="ms-2">Lokasi Pendaftar (ukuran = jumlah siswa)</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Statistik Wilayah</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="wilayahStats">
                                            <thead>
                                                <tr>
                                                    <th>Nama</th>
                                                    <th>Alamat Lengkap</th>
                                                    <th>Kelurahan</th>
                                                    <th>Kecamatan</th>
                                                    <th>Kabupaten/Kota</th>
                                                    <th>Provinsi</th>
                                                    <th>Kode Pos</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
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
    </div>
</div>

<script>
let map;
let markers = [];

function initMap() {
    map = L.map('map').setView([-6.2088, 106.8456], 8);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    loadMapData();
}

function loadMapData() {
    const filters = {
        gelombang: document.getElementById('filterGelombang').value,
        jurusan: document.getElementById('filterJurusan').value,
        status: document.getElementById('filterStatus').value
    };
    
    fetch('/admin/map-data?' + new URLSearchParams(filters))
        .then(response => response.json())
        .then(data => {
            clearMarkers();
            addMarkers(data.markers);
            updateStats(data.stats);
        })
        .catch(error => console.error('Error:', error));
}

function clearMarkers() {
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];
}

function addMarkers(data) {
    data.forEach(item => {
        if (item.lat && item.lng && item.total > 0) {
            const popup = `<strong>${item.kabupaten}</strong><br>Total: ${item.total}`;
            const marker = L.circleMarker([parseFloat(item.lat), parseFloat(item.lng)], {
                radius: Math.min(item.total * 2, 20),
                fillColor: '#007bff',
                color: '#0056b3',
                weight: 2,
                opacity: 0.8,
                fillOpacity: 0.7
            }).bindPopup(popup).addTo(map);
            
            markers.push(marker);
        }
    });
}

function updateStats(stats) {
    const tbody = document.querySelector('#wilayahStats tbody');
    tbody.innerHTML = '';
    
    stats.forEach(item => {
        const statusBadge = {
            'DRAFT': 'bg-secondary',
            'SUBMIT': 'bg-info',
            'ADM_PASS': 'bg-warning',
            'PAID': 'bg-success'
        };
        
        const row = `
            <tr>
                <td>${item.nama_lengkap}</td>
                <td>${item.alamat || '-'}</td>
                <td>${item.kelurahan || '-'}</td>
                <td>${item.kecamatan || '-'}</td>
                <td>${item.kabupaten}</td>
                <td>${item.provinsi}</td>
                <td>${item.kodepos || '-'}</td>
                <td><span class="badge ${statusBadge[item.status] || 'bg-secondary'}">${item.status}</span></td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

document.addEventListener('DOMContentLoaded', function() {
    initMap();
    document.getElementById('refreshMap').addEventListener('click', loadMapData);
    document.getElementById('filterGelombang').addEventListener('change', loadMapData);
    document.getElementById('filterJurusan').addEventListener('change', loadMapData);
    document.getElementById('filterStatus').addEventListener('change', loadMapData);
});
</script>
@endsection
