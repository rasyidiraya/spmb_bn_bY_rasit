<?php

namespace App\Models\Pendaftar;

use Illuminate\Database\Eloquent\Model;

class PendaftarBerkas extends Model
{
    protected $table = 'pendaftar_berkas';
    
    protected $fillable = [
        'pendaftar_id', 'jenis', 'nama_file', 'url', 'ukuran_kb', 'valid', 'catatan'
    ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}