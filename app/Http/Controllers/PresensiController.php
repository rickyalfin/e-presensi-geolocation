<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    //Menghitung Jarak
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

}
