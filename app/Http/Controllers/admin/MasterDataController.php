<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar\Jurusan;
use App\Models\Pendaftar\Gelombang;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function jurusan()
    {
        $jurusan = Jurusan::all();
        return view('admin.master.jurusan', compact('jurusan'));
    }

    public function storeJurusan(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:jurusan',
            'nama' => 'required|string|max:100',
            'kuota' => 'required|integer|min:1'
        ]);

        Jurusan::create($request->only(['kode', 'nama', 'kuota']));
        return back()->with('success', 'Jurusan berhasil ditambahkan');
    }

    public function updateJurusan(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:jurusan,kode,' . $id,
            'nama' => 'required|string|max:100',
            'kuota' => 'required|integer|min:1'
        ]);

        $jurusan = Jurusan::findOrFail($id);
        $jurusan->update($request->only(['kode', 'nama', 'kuota']));
        return back()->with('success', 'Jurusan berhasil diupdate');
    }

    public function deleteJurusan($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        $jurusan->delete();
        return back()->with('success', 'Jurusan berhasil dihapus');
    }

    public function gelombang()
    {
        $gelombang = Gelombang::all();
        return view('admin.master.gelombang', compact('gelombang'));
    }

    public function storeGelombang(Request $request)
    {
        $request->validate([
            'nama_gelombang' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'biaya_pendaftaran' => 'required|numeric|min:0'
        ]);

        Gelombang::create($request->all());
        return back()->with('success', 'Gelombang berhasil ditambahkan');
    }

    public function toggleStatusGelombang($id)
    {
        $gelombang = Gelombang::findOrFail($id);
        
        if ($gelombang->status === 'nonaktif') {
            // Nonaktifkan semua gelombang lain terlebih dahulu
            Gelombang::where('id', '!=', $id)->update(['status' => 'nonaktif']);
            $gelombang->status = 'aktif';
            $status = 'diaktifkan';
        } else {
            $gelombang->status = 'nonaktif';
            $status = 'dinonaktifkan';
        }
        
        $gelombang->save();
        return back()->with('success', "Gelombang {$gelombang->nama} berhasil {$status}");
    }
}