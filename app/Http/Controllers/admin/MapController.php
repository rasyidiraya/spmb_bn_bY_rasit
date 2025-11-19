<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pendaftar\Gelombang;
use App\Models\Pendaftar\Jurusan;

class MapController extends Controller
{
    public function index()
    {
        $gelombang = Gelombang::all() ?? collect();
        $jurusan = Jurusan::all() ?? collect();
        
        return view('admin.map', compact('gelombang', 'jurusan'));
    }
    
    public function mapData(Request $request)
    {
        $query = DB::table('pendaftar')
            ->leftJoin('pendaftar_data_siswa', 'pendaftar.id', '=', 'pendaftar_data_siswa.pendaftar_id')
            ->leftJoin('wilayah', 'pendaftar_data_siswa.wilayah_id', '=', 'wilayah.id')
            ->select(
                DB::raw('COALESCE(wilayah.provinsi, "Unknown") as provinsi'),
                DB::raw('COALESCE(wilayah.kabupaten, "Unknown") as kabupaten'),
                DB::raw('COALESCE(wilayah.kecamatan, "Unknown") as kecamatan'),
                'pendaftar.status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('wilayah.provinsi', 'wilayah.kabupaten', 'wilayah.kecamatan', 'pendaftar.status');
            
        if ($request->gelombang) {
            $query->where('pendaftar.gelombang_id', $request->gelombang);
        }
        
        if ($request->jurusan) {
            $query->where('pendaftar.jurusan_id', $request->jurusan);
        }
        
        if ($request->status) {
            $query->where('pendaftar.status', $request->status);
        }
        
        $data = $query->get();
        
        $grouped = [];
        foreach ($data as $item) {
            $key = $item->provinsi . '|' . $item->kabupaten . '|' . $item->kecamatan;
            
            if (!isset($grouped[$key])) {
                $coordinates = $this->getCoordinates($item->kabupaten);
                
                $grouped[$key] = [
                    'provinsi' => $item->provinsi,
                    'kabupaten' => $item->kabupaten,
                    'kecamatan' => $item->kecamatan,
                    'total' => 0,
                    'draft' => 0,
                    'submit' => 0,
                    'adm_pass' => 0,
                    'paid' => 0,
                    'lat' => $coordinates['lat'],
                    'lng' => $coordinates['lng']
                ];
            }
            
            $grouped[$key]['total'] += $item->total;
            $statusKey = strtolower(str_replace('_', '_', $item->status));
            if (isset($grouped[$key][$statusKey])) {
                $grouped[$key][$statusKey] += $item->total;
            }
        }
        
        $markers = array_values($grouped);
        $detailedStats = $this->getDetailedStats($request);
        
        return response()->json([
            'markers' => $markers,
            'stats' => $detailedStats
        ]);
    }
    
    private function getCoordinates($kabupaten)
    {
        $coordinates = [
            'Kota Jakarta Pusat' => ['lat' => -6.1745, 'lng' => 106.8227],
            'Kota Jakarta Selatan' => ['lat' => -6.2615, 'lng' => 106.8106],
            'Kota Jakarta Timur' => ['lat' => -6.2250, 'lng' => 106.9004],
            'Kota Jakarta Barat' => ['lat' => -6.1352, 'lng' => 106.7511],
            'Kota Jakarta Utara' => ['lat' => -6.1384, 'lng' => 106.8759],
            'Kota Bandung' => ['lat' => -6.9175, 'lng' => 107.6191],
            'Kota Bekasi' => ['lat' => -6.2383, 'lng' => 106.9756],
            'Kota Bogor' => ['lat' => -6.5971, 'lng' => 106.8060],
            'Kota Cirebon' => ['lat' => -6.7063, 'lng' => 108.5570],
            'Kota Depok' => ['lat' => -6.4025, 'lng' => 106.7942],
            'Kota Sukabumi' => ['lat' => -6.9271, 'lng' => 106.9265],
            'Kota Tasikmalaya' => ['lat' => -7.3274, 'lng' => 108.2207],
            'Kota Semarang' => ['lat' => -6.9667, 'lng' => 110.4167],
            'Kota Surakarta' => ['lat' => -7.5667, 'lng' => 110.8167],
            'Kota Yogyakarta' => ['lat' => -7.7956, 'lng' => 110.3695],
            'Kota Surabaya' => ['lat' => -7.2492, 'lng' => 112.7508],
            'Kota Malang' => ['lat' => -7.9797, 'lng' => 112.6304],
            'Kota Kediri' => ['lat' => -7.8167, 'lng' => 112.0167],
            'Kota Serang' => ['lat' => -6.1200, 'lng' => 106.1500],
            'Kota Tangerang' => ['lat' => -6.1783, 'lng' => 106.6319],
            'Kota Tangerang Selatan' => ['lat' => -6.2875, 'lng' => 106.7142],
            'Kota Denpasar' => ['lat' => -8.6500, 'lng' => 115.2167],
            'Kota Medan' => ['lat' => 3.5952, 'lng' => 98.6722],
            'Kota Padang' => ['lat' => -0.9471, 'lng' => 100.4172],
            'Kota Pekanbaru' => ['lat' => 0.5333, 'lng' => 101.4500],
            'Kota Batam' => ['lat' => 1.1306, 'lng' => 104.0528],
            'Kota Jambi' => ['lat' => -1.6000, 'lng' => 103.6167],
            'Kota Palembang' => ['lat' => -2.9167, 'lng' => 104.7458],
            'Kota Bengkulu' => ['lat' => -3.8004, 'lng' => 102.2655],
            'Kota Bandar Lampung' => ['lat' => -5.4292, 'lng' => 105.2619],
            'Kota Pontianak' => ['lat' => -0.0263, 'lng' => 109.3425],
            'Kota Palangka Raya' => ['lat' => -2.2069, 'lng' => 113.9264],
            'Kota Banjarmasin' => ['lat' => -3.3194, 'lng' => 114.5906],
            'Kota Samarinda' => ['lat' => -0.5017, 'lng' => 117.1536],
            'Kota Balikpapan' => ['lat' => -1.2379, 'lng' => 116.8289],
            'Kota Manado' => ['lat' => 1.4748, 'lng' => 124.8421],
            'Kota Palu' => ['lat' => -0.8917, 'lng' => 119.8708],
            'Kota Makassar' => ['lat' => -5.1477, 'lng' => 119.4327],
            'Kota Kendari' => ['lat' => -3.9450, 'lng' => 122.5986],
            'Kota Gorontalo' => ['lat' => 0.5435, 'lng' => 123.0681],
            'Kota Ambon' => ['lat' => -3.6954, 'lng' => 128.1814],
            'Kota Ternate' => ['lat' => 0.7833, 'lng' => 127.3667],
            'Kota Sorong' => ['lat' => -0.8833, 'lng' => 131.2500],
            'Kota Jayapura' => ['lat' => -2.5333, 'lng' => 140.7167],
            'Kota Mataram' => ['lat' => -8.5833, 'lng' => 116.1167],
            'Kota Kupang' => ['lat' => -10.1667, 'lng' => 123.5833],
            'Kota Banda Aceh' => ['lat' => 5.5483, 'lng' => 95.3238]
        ];
        
        $kabupaten = trim($kabupaten);
        
        if (isset($coordinates[$kabupaten])) {
            return $coordinates[$kabupaten];
        }
        
        $cleanName = preg_replace('/^(Kota|Kabupaten)\s+/i', '', $kabupaten);
        foreach ($coordinates as $key => $coord) {
            if (stripos($key, $cleanName) !== false) {
                return $coord;
            }
        }
        
        return ['lat' => -6.2088, 'lng' => 106.8456];
    }
    
    private function getDetailedStats($request)
    {
        $query = DB::table('pendaftar')
            ->join('pendaftar_data_siswa', 'pendaftar.id', '=', 'pendaftar_data_siswa.pendaftar_id')
            ->leftJoin('wilayah', 'pendaftar_data_siswa.wilayah_id', '=', 'wilayah.id')
            ->select(
                'pendaftar_data_siswa.nama as nama_lengkap',
                'pendaftar_data_siswa.alamat',
                DB::raw('COALESCE(wilayah.kelurahan, "-") as kelurahan'),
                DB::raw('COALESCE(wilayah.kecamatan, "-") as kecamatan'),
                DB::raw('COALESCE(wilayah.kabupaten, "-") as kabupaten'),
                DB::raw('COALESCE(wilayah.provinsi, "-") as provinsi'),
                DB::raw('COALESCE(wilayah.kodepos, "-") as kodepos'),
                'pendaftar.status'
            )
            ->orderBy('pendaftar_data_siswa.nama');
            
        if ($request->gelombang) {
            $query->where('pendaftar.gelombang_id', $request->gelombang);
        }
        
        if ($request->jurusan) {
            $query->where('pendaftar.jurusan_id', $request->jurusan);
        }
        
        if ($request->status) {
            $query->where('pendaftar.status', $request->status);
        }
        
        return $query->get();
    }
}