@extends('layouts.admin.tabler')
@section('content')
    <div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <!-- Page pre-title -->
                <h2 class="page-title">
                  Data Karyawan
                </h2>
              </div>
            </div>
          </div>
        </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>No. HP</th>
                                <th>Foto</th>
                                <th>Departemen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($karyawan as $d)
                            <!-- variabel untuk menampung url gambar -->
                            @php
                                $path = Storage::url('uploads/karyawan/'.$d->foto);
                            @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $d->nik }}</td>
                                    <td>{{ $d->nama_lengkap }}</td>
                                    <td>{{ $d->jabatan }}</td>
                                    <td>{{ $d->no_hp }}</td>
                                    <td>
                                        @if (empty($d->foto))
                                            <img src="{{ asset('assets/img/no_photo.png') }}" class="avatar" alt="">
                                        @else
                                            <img src="{{ url($path) }}" class="avatar" alt="">
                                        @endif
                                    </td>
                                    <td>{{ $d->nama_dept }}</td> <!-- field nama_dept sudah di joinkan di KaryawanController supaya nama_dept bisa muncul, dimana antara tabel karyawan dengan departemen dimana kode_dept di field karyawan harus sama dengan field kode_dept di tabel departemen -->
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection