<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

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

    public function store(Request $request)
    {
        $nik = $request->nik;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $kode_dept = $request->kode_dept;
        $password = Hash::make('12345');
        // hasFile, jika kita akan mengecek apakah form yang dikirimkan ada file foto yg diupload atau tidak
        if ($request->hasFile('foto')) {
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension(); // format nama foto ketika diupload
        } else {
            $foto = null; // jika kita tidak mengupload foto, maka data fotonya akan null atau kosong
        }

        // proses simpan data
        try {
            // array untuk menampung data apa saja yang akan kita simpan di database
            $data = [
                'nik' => $nik,
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'kode_dept' => $kode_dept,
                'foto' => $foto,
                'password' => $password,
            ];
            $simpan = DB::table('karyawan')->insert($data);
            if ($simpan) {
                // perintah untuk menyimpan file foto yang sudah ditentukan
                if ($request->hasFile('foto')) {
                    $folderpath = "public/uploads/karyawan/";
                    $request->file('foto')->storeAs($folderpath, $foto); // untuk menyimpan ke storage nya gunakan perintah ini
                }
                return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    // Method Edit Karyawan
    public function edit(Request $request)
    {
        $nik = $request->nik;
        $departemen = DB::table('departemen')->get();
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first(); // first karena data yang akan kita tampilkan cuman satu saja
        return view('karyawan.edit', compact('departemen', 'karyawan'));
    }

    // Method Update karyawan
    public function update($nik, Request $request)
    {
        $nik = $request->nik;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $kode_dept = $request->kode_dept;
        $password = Hash::make('12345');
        $old_foto = $request->old_foto;
        // hasFile, jika kita akan mengecek apakah form yang dikirimkan ada file foto yg diupload atau tidak
        if ($request->hasFile('foto')) {
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension(); // format nama foto ketika diupload
        } else {
            $foto = $old_foto; // jika kita tidak mengupload foto, maka data fotonya akan null atau kosong
        }

        // proses simpan data
        try {
            // array untuk menampung data apa saja yang akan kita simpan di database
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'kode_dept' => $kode_dept,
                'foto' => $foto,
                'password' => $password,
            ];
            $update = DB::table('karyawan')->where('nik', $nik)->update($data);
            if ($update) {
                // perintah untuk menyimpan file foto yang sudah ditentukan
                if ($request->hasFile('foto')) {
                    $folderpath = "public/uploads/karyawan/";
                    $folderpathOld = "public/uploads/karyawan/" . $old_foto;
                    Storage::delete($folderpathOld); // sebelum kita menyimpan variabel foto yang baru ke dalam folderpath, kita akan hapus dulu old fotonya
                    $request->file('foto')->storeAs($folderpath, $foto); // untuk menyimpan ke storage nya gunakan perintah ini
                }
                return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    // Method Delete data karyawan
    public function delete($nik)
    {
        $delete = DB::table('karyawan')->where('nik', $nik)->delete();
        if ($delete) {
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Dihapus']);
        }
    }
}
