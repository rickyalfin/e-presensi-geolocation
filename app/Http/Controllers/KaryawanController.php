<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = Karyawan::query();
        $query->select('karyawan.*', 'nama_dept'); // .* artinya kita akan menampilkan semua field yang ada di tabel karyawan
        $query->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept'); // untuk menampilkan nama departeman kita joinkan tabel departemen dengan tabel karyawan, dimana yang menghubungkan field kode_dept di tabel karyawan dengan kode_dept di tabel departeman
        $query->orderBy('nik');
        if (!empty($request->nama_karyawan)) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama_karyawan . '%'); // 'like' artinya kata apa saja cocok dengan semua isi akan ditampilkan semuanya
        }

        if (!empty($request->kode_dept)) {
            $query->where('karyawan.kode_dept', $request->kode_dept); // karena kode_dept ada di 2 tabel di tabel karyawan dan tabel departemen dan sudah kita joinkan diatas, maka kita harus definisikan kode_dept diambil dari tabel mana supaya tidak ambigu. maka kita ambil saja kode_dept dari tabek karyawan
        }

        $karyawan = $query->paginate(10); // untuk menampilkan data 10 baris per halaman

        // Menampilkan data departeman di option search
        $departemen = DB::table('departemen')->get();

        return view('karyawan.index', compact('karyawan', 'departemen'));
    }
}
