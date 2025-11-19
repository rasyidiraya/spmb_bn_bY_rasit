<?php

namespace App\Models\Pendaftar;

use Illuminate\Database\Eloquent\Model;

class PendaftarDataOrtu extends Model
{
    protected $table = 'pendaftar_data_ortu';
    protected $primaryKey = 'pendaftar_id';
    public $incrementing = false;
    
    protected $fillable = [
        'pendaftar_id', 'nama_ayah', 'pekerjaan_ayah', 'hp_ayah',
        'nama_ibu', 'pekerjaan_ibu', 'hp_ibu', 'wali_nama', 'wali_hp'
    ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}