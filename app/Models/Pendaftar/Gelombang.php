<?php

namespace App\Models\Pendaftar;

use Illuminate\Database\Eloquent\Model;

class Gelombang extends Model
{
    protected $table = 'gelombang';
    
    protected $fillable = [
        'nama', 'tahun', 'tgl_mulai', 'tgl_selesai', 'biaya_daftar', 'status'
    ];

    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date'
    ];

    public function pendaftar()
    {
        return $this->hasMany(Pendaftar::class);
    }

    public function isActive()
    {
        return $this->status === 'aktif';
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}