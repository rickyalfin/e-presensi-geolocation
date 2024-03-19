<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $hariini = date('Y-m-d');
        $bulanini = date('m') * 1;
        $tahunini = date('Y');
        $nik = Auth::guard('karyawan')->user()->nik;
        $presensihariini = DB::table('presensi')->where('nik', $nik)->where('tgl_presensi', $hariini)->first();
        $historybulanini = DB::table('presensi')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulanini . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahunini . '"')
            ->orderBy('tgl_presensi')
            ->get();

        // Query ini berfungsi untuk menghitung berapa kali karyawan tersebut melakukan presensi pada bulan saat ini
        $rekappresensi = DB::table('presensi')
            ->selectRaw('COUNT(nik) as jmlhadir, SUM(IF(jam_in > "07:00",1,0)) as jmlterlambat')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulanini . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahunini . '"')
            ->first();

        // Query leaderboard, akan menampilkan data presensi yang aktif pada hari ini
        $leaderboard = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik') // link kan / joinkan tabel presensi ke tabel karyawan melalui field 'nik'
            ->where('tgl_presensi', $hariini)
            ->orderBy('jam_in') // mengurutkan sesuai jam masuk (otomatis acs jika tidak ditambahkan desc)
            ->get();

        // Nama bulan supaya dinamis sesuai dengan bulan yang berjalan (saat ini)
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober",
            "November", "Desember"];

        // Query menampilkan rekap izin dan sakit di dashboard
        $rekapizin = DB::table('pengajuan_izin')
            ->selectRaw('SUM(IF(status="i",1,0)) as jmlizin, SUM(IF(status="s",1,0)) as jmlsakit') // jumlahkan status i / izin maka hitung izin 1++, jumlahkan status s / sakit maka hitung sakit 1++
            ->where('nik', $nik)
            ->whereRaw('MONTH(tgl_izin)="' . $bulanini . '"')
            ->whereRaw('YEAR(tgl_izin)="' . $tahunini . '"')
            ->where('status_approved', 1)
            ->first();

        // Mengirim data ke view (parsing)
        return view('dashboard.dashboard', compact('presensihariini', 'historybulanini', 'namabulan', 'bulanini', 'tahunini',
            'rekappresensi', 'leaderboard', 'rekapizin'));
    }
}
