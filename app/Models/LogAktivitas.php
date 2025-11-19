<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';

    protected $fillable = [
        'user_id',
        'aksi',
        'objek',
        'objek_data',
        'waktu',
        'ip'
    ];

    protected $casts = [
        'objek_data' => 'array',
        'waktu' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\Pendaftar\Pengguna::class, 'user_id');
    }
}