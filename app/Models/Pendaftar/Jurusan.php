<?php

namespace App\Models\Pendaftar;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $table = 'jurusan';
    
    protected $fillable = [
        'kode', 'nama', 'kuota'
    ];

    public function pendaftar()
    {
        return $this->hasMany(Pendaftar::class);
    }
}