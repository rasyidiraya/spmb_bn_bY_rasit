<?php

namespace App\Models\Pendaftar;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pendaftar\Pengguna;

class Pendaftar extends Model
{
    protected $table = 'pendaftar';
    
    protected $fillable = [
        'user_id', 'tanggal_daftar', 'no_pendaftaran', 
        'gelombang_id', 'jurusan_id', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }

    public function dataSiswa()
    {
        return $this->hasOne(PendaftarDataSiswa::class);
    }

    public function dataOrtu()
    {
        return $this->hasOne(PendaftarDataOrtu::class);
    }

    public function asalSekolah()
    {
        return $this->hasOne(PendaftarAsalSekolah::class);
    }

    public function berkas()
    {
        return $this->hasMany(PendaftarBerkas::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    public function gelombang()
    {
        return $this->belongsTo(Gelombang::class, 'gelombang_id');
    }
}