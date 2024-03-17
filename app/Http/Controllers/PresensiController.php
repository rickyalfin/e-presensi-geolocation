<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
{
    public function create()
    {
        // query untuk mengecek apakah karyawan tersebut sudah melakukan absen atau belum
        $hariini = date("Y-m-d");
        $nik = Auth::guard('karyawan')->user()->nik;
        $cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nik', $nik)->count();
        return view('presensi.create', compact('cek'));
    }

    public function store(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        // $latitudekantor = -7.0932457259068356;
        // $longitudekantor = 110.30030361183256;
        $latitudekantor = -7.2574719;
        $longitudekantor = 112.7520883;

        $lokasi = $request->lokasi;
        $lokasi_user = explode(",", $lokasi);
        $latitudeuser = $lokasi_user[0];
        $longitudeuser = $lokasi_user[1];

        $jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
        $radius = round($jarak["meters"]);

        $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->count();
        // mengecek apakah karyawan sudah melakukan absen, keterangan out. Jika belum maka in
        if ($cek > 0) {
            $ket = "out";
        } else {
            $ket = "in";
        }
        $image = $request->image;
        // lokasi penyimpanan  gambar di folder public/uploads/absensi
        $folderpath = 'public/uploads/absensi/';
        // merename nama file gambar sesuai dengan yang sudah ditentukan (jadi nanti formatnya adalah 'nik-tanggalpresensi-keterangan(in/out)')
        $formatName = $nik . "-" . $tgl_presensi . "-" . $ket;
        // decode file image dari base64
        $image_parts = explode(";base64,", $image);
        $image_base64 = base64_decode($image_parts[1]);
        // konfigurasi untuk tipe data
        $fileName = $formatName . ".png";
        $file = $folderpath . $fileName;

        // jika karyawan tersebut sudah melakukan absen, maka perintah yang dijalankan bukan perintah menyimpan lagi. Tapi perintah untuk mengupdate data
        // Sebelum pengecekan absen dibawah, melakukan cek dulu radius jaraknya
        if ($radius > 20) {
            echo "error|Maaf Anda Berada Diluar Radius, Jarak Anda " . $radius . " Meter Dari Kantor|radius";
        } else {
            if ($cek > 0) {
                $data_pulang = [
                    'jam_out' => $jam,
                    'foto_out' => $fileName,
                    'lokasi_out' => $lokasi,
                ];
                // jika update berhasil maka mereturn 0 dan menyimpan file foto ketika pulang
                $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->update($data_pulang);
                if ($update) {
                    echo "success|Terimakasih, Hati-hati Di Jalan|out";
                    Storage::put($file, $image_base64);
                } else {
                    echo "error|Maaf Gagal Absen, Hubungi Tim IT|out";
                }
            } else {
                // jika data masih kosong (belum absen), maka yang dijalankan adalah proses penyimpanan data
                $data = [
                    'nik' => $nik,
                    'tgl_presensi' => $tgl_presensi,
                    'jam_in' => $jam,
                    'foto_in' => $fileName,
                    'lokasi_in' => $lokasi,
                ];
                $simpan = DB::table('presensi')->insert($data);
                if ($simpan) {
                    echo "success|Terimakasih, Selamat Bekerja|in";
                    Storage::put($file, $image_base64);
                } else {
                    echo "error|Maaf Gagal Absen, Hubungi Tim IT|in";
                }
            }
        }
    }

    // Menghitung Jarak
    public function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }

    // methode atau function untuk edit profile
    public function editprofile()
    {
        // mengambil data dari karyawan yang login dan menampilkan di view edit profile
        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

        // compact untuk mengirimkan query data ke view edit profile
        return view('presensi.editprofile', compact('karyawan'));
    }

    // method atau function update profile
    public function updateprofile(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        // menangkap nilai dari nama lengkap
        $nama_lengkap = $request->nama_lengkap;
        // menangkap nilai dari no_hp
        $no_hp = $request->no_hp;
        // menangkap nilai password dari password dan melakukan hash (enkrips)
        $password = Hash::make($request->password);
        // query untuk mengambil data karyawan
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        // hasFile, jika kita akan mengecek apakah form yang dikirimkan ada file foto yg diupload atau tidak
        if ($request->hasFile('foto')) {
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension(); // format nama foto ketika diupload
        } else {
            $foto = $karyawan->foto; // jika kita tidak mengupload foto, maka data fotonya akan diambil dari data foto sebelumnya
        }

        // query untuk update
        // buat kondisi jika password kosong (tidak dirubah), maka tidak akan mengupdate password
        if (empty($request->password)) {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'foto' => $foto,
            ];
        } else {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'password' => $password,
                'foto' => $foto,
            ];
        }

        $update = DB::table('karyawan')->where('nik', $nik)->update($data);
        // kondisi jika data berhasil di update, maka akan return ke halaman profil dengan mengirimkan status pesan sukses
        if ($update) {
            // perintah untuk menyimpan file foto yang sudah ditentukan
            if ($request->hasFile('foto')) {
                $folderpath = "public/uploads/karyawan/";
                $request->file('foto')->storeAs($folderpath, $foto); // untuk menyimpan ke storage nya gunakan perintah ini
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return Redirect::back()->with(['error' => 'Data Gagal Di Update']);
        }
    }

    // function untuk membuat history presensi
    public function history()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober",
            "November", "Desember"];
        return view('presensi.history', compact('namabulan'));
    }

    // function untuk gethistory
    public function gethistory(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nik = Auth::guard('karyawan')->user()->nik;

        // query untuk menampilkan data dari history presensi dari karyawan tersebut
        // artinya 'tampilkan data presensi dimana bulan presensinya = (contoh maret) dan tahunnya = (contoh 2024) maka
        // tampilkan data presensi dimana tanggal presensi di bulan dan tahun tersebut dari nik yang sedang login
        $history = DB::table('presensi')
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->where('nik', $nik)
            ->orderBy('tgl_presensi')
            ->get();

        // jika sudah mendapatkan datanya tampilkan data tersebut ke dalam view
        return view('presensi.gethistory', compact('history'));
    }
}
