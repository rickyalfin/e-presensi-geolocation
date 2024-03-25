<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawan = DB::table('karyawan')->orderBy('nik')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept') // untuk menampilkan nama departeman kita joinkan tabel departemen dengan tabel karyawan, dimana yang menghubungkan field kode_dept di tabel karyawan dengan kode_dept di tabel departeman
            ->get();
        return view('karyawan.index', compact('karyawan'));
    }
}
