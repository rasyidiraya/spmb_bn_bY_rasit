<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KelolaUserController extends Controller
{
    public function index()
    {
        $users = Pengguna::where('role', '!=', 'pendaftar')->get();
        return view('admin.kelola-user.index', compact('users'));
    }

    public function pendaftar()
    {
        $users = Pengguna::where('role', 'pendaftar')->get();
        return view('admin.kelola-user.pendaftar', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pengguna',
            'hp' => 'required|string|max:20',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,verifikator_adm,keuangan,kepsek'
        ]);

        Pengguna::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'hp' => $request->hp,
            'password_hash' => Hash::make($request->password),
            'role' => $request->role,
            'aktif' => true
        ]);

        return back()->with('success', 'User berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pengguna,email,' . $id,
            'hp' => 'required|string|max:20',
            'role' => 'required|in:admin,verifikator_adm,keuangan,kepsek'
        ]);

        $user = Pengguna::findOrFail($id);
        $data = $request->only(['nama', 'email', 'hp', 'role']);
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $data['password_hash'] = Hash::make($request->password);
        }

        $user->update($data);
        return back()->with('success', 'User berhasil diupdate');
    }

    public function destroy($id)
    {
        $user = Pengguna::findOrFail($id);
        $user->delete();
        return back()->with('success', 'User berhasil dihapus');
    }

    public function toggleStatus($id)
    {
        $user = Pengguna::findOrFail($id);
        
        // Cek jika user mencoba menonaktifkan dirinya sendiri
        if ($user->id == auth('admin')->id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun Anda sendiri');
        }
        
        // Cek jika ini admin terakhir yang aktif
        if ($user->role == 'admin' && $user->aktif) {
            $activeAdminCount = Pengguna::where('role', 'admin')->where('aktif', true)->count();
            if ($activeAdminCount <= 1) {
                return back()->with('error', 'Tidak dapat menonaktifkan admin terakhir');
            }
        }
        
        $user->aktif = !$user->aktif;
        $user->save();
        
        $status = $user->aktif ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "User berhasil {$status}");
    }
}