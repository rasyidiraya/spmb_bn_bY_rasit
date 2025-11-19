@extends('main-admin')
@section('content-admin')

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <h1 class="h3 mb-0 text-gray-800">Master Data Jurusan</h1>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Daftar Jurusan</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama Jurusan</th>
                                            <th>Kuota</th>
                                            <th>Jumlah Pendaftar</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($jurusan as $item)
                                        @php
                                            $jumlahPendaftar = \App\Models\Pendaftar\Pendaftar::where('jurusan_id', $item->id)
                                                ->whereIn('status', ['ADM_PASS', 'PAYMENT_PENDING', 'PAID'])
                                                ->count();
                                        @endphp
                                        <tr>
                                            <td>{{ $item->kode }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ $item->kuota }}</td>
                                            <td>{{ $jumlahPendaftar }} siswa</td>
                                            <td>
                                                <button class="btn btn-sm btn-warning" onclick="editJurusan({{ $item->id }}, '{{ $item->kode }}', '{{ $item->nama }}', {{ $item->kuota }})">Edit</button>
                                                <form method="POST" action="{{ route('admin.master.jurusan.delete', $item->id) }}" class="d-inline" onsubmit="return confirm('Yakin hapus jurusan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Tambah Jurusan</h6>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    @foreach($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            @endif
                            
                            <form id="jurusanForm" method="POST" action="{{ route('admin.master.jurusan.store') }}">
                                @csrf
                                <input type="hidden" id="method" name="_method" value="">
                                <input type="hidden" id="jurusan_id" name="jurusan_id" value="">
                                
                                <div class="form-group">
                                    <label>Kode Jurusan</label>
                                    <input type="text" id="kode" name="kode" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Nama Jurusan</label>
                                    <input type="text" id="nama" name="nama" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Kuota</label>
                                    <input type="number" id="kuota" name="kuota" class="form-control" required>
                                </div>
                                <button type="submit" id="submitBtn" class="btn btn-primary">Simpan</button>
                                <button type="button" id="cancelBtn" class="btn btn-secondary" onclick="resetForm()" style="display:none">Batal</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editJurusan(id, kode, nama, kuota) {
    document.getElementById('jurusan_id').value = id;
    document.getElementById('kode').value = kode;
    document.getElementById('nama').value = nama;
    document.getElementById('kuota').value = kuota;
    document.getElementById('method').value = 'PUT';
    document.getElementById('jurusanForm').action = '/admin/master/jurusan/' + id;
    document.getElementById('submitBtn').textContent = 'Update';
    document.getElementById('cancelBtn').style.display = 'inline-block';
    document.querySelector('.card-header h6').textContent = 'Edit Jurusan';
}

function resetForm() {
    document.getElementById('jurusanForm').reset();
    document.getElementById('method').value = '';
    document.getElementById('jurusan_id').value = '';
    document.getElementById('jurusanForm').action = '{{ route('admin.master.jurusan.store') }}';
    document.getElementById('submitBtn').textContent = 'Simpan';
    document.getElementById('cancelBtn').style.display = 'none';
    document.querySelector('.card-header h6').textContent = 'Tambah Jurusan';
}
</script>

@endsection