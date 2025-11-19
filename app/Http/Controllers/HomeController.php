<?php

namespace App\Http\Controllers;

use App\Models\Pendaftar\Gelombang;

class HomeController extends Controller
{
    public function index()
    {
        $gelombang = Gelombang::orderBy('tgl_mulai')->get();
        return view('userend.index', compact('gelombang'));
    }
}
