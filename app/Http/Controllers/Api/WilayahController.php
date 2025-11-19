<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WilayahController extends Controller
{
    public function getProvinsi()
    {
        $provinsi = DB::table('wilayah')
            ->select('provinsi')
            ->distinct()
            ->orderBy('provinsi')
            ->get();
            
        return response()->json($provinsi);
    }
    
    public function getKabupaten($provinsi)
    {
        $kabupaten = DB::table('wilayah')
            ->select('kabupaten')
            ->where('provinsi', $provinsi)
            ->distinct()
            ->orderBy('kabupaten')
            ->get();
            
        return response()->json($kabupaten);
    }
    
    public function getKecamatan($provinsi, $kabupaten)
    {
        $kecamatan = DB::table('wilayah')
            ->select('kecamatan')
            ->where('provinsi', $provinsi)
            ->where('kabupaten', $kabupaten)
            ->distinct()
            ->orderBy('kecamatan')
            ->get();
            
        return response()->json($kecamatan);
    }
    
    public function getKelurahan($provinsi, $kabupaten, $kecamatan)
    {
        $kelurahan = DB::table('wilayah')
            ->select('id', 'kelurahan', 'kodepos')
            ->where('provinsi', $provinsi)
            ->where('kabupaten', $kabupaten)
            ->where('kecamatan', $kecamatan)
            ->orderBy('kelurahan')
            ->get();
            
        return response()->json($kelurahan);
    }
}