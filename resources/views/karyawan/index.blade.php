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
                            <div class="row">
                                <div class="col-12">
                                    {{-- menampilkan pesan ketika berhasil tambah data --}}
                                    @if (Session::get('success'))
                                        <div class="alert alert-success">
                                            {{ Session::get('success') }}
                                        </div>
                                    @endif

                                    {{-- menampilkan pesan ketika gagal tambah data --}}
                                    @if (Session::get('warning'))
                                        <div class="alert alert-warning">
                                            {{ Session::get('warning') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <a href="#" class="btn btn-primary" id="btnTambahkaryawan">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  
                                        viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  
                                        stroke-linecap="round"  stroke-linejoin="round"  
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                        Tambah Data
                                    </a>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <form action="/karyawan" method="GET">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <input type="text" name="nama_karyawan" id="nama_karyawan" class="form-control" placeholder="Nama Karyawan" value="{{ Request('nama_karyawan') }}">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <select name="kode_dept" id="kode_dept" class="form-select">
                                                        <option value="">Departemen</option>
                                                        <!-- looping data departemen pada option -->
                                                        @foreach ($departemen as $d)
                                                            <option {{ Request('kode_dept')==$d->kode_dept ? 'selected' : '' }} value="{{ $d->kode_dept }}">{{ $d->nama_dept }} <!--  Request('kode_dept')==$d->kode_dept ? 'selected' : '' artinya kalo kita seacrh data nama departemen dan ada data yang ditampilkan maka option nama departemen tidak akan hilang -->
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                                                    Cari</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
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
                                                <td>{{ $loop->iteration + $karyawan->firstItem()-1 }}</td> <!-- + $karyawan->firstItem()-1 = membuat nomor menjadi urut / melanjutkan dari data halaman sebelumnya ketika halaman di next  -->
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
                                                <td>{{ $d->nama_dept }}</td> <!-- field nama_dept sudah di joinkan di KaryawanController supaya nama_dept bisa muncul, dimana antara tabel karyawan dengan departemen dimana kode_dept di field karyawan harus sama dengan field kode_dept di tabel     departemen -->
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="#" class="edit btn btn-info btn-sm" nik="{{ $d->nik }}">
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  
                                                        viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  
                                                        stroke-linecap="round"  stroke-linejoin="round"  
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                        <path d="M16 5l3 3" />
                                                    </svg>
                                                    </a>
                                                    <form action="/karyawan/{{ $d->nik }}/delete" method="POST" style="margin-left: 5px">
                                                        @csrf
                                                        <a class="btn btn-danger btn-sm delete-confirm">
                                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  
                                                            fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  
                                                            stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" />
                                                            <path d="M10 11l0 6" /><path d="M14 11l0 6" />
                                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                        </svg>
                                                        </a>
                                                    </form>
                                                    </div> 
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    </table>
                                    {{ $karyawan->links('vendor.pagination.bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modals Tambah Data-->
    <div class="modal modal-blur fade" id="modal-inputkaryawan" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tambah Data Karyawan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="/karyawan/store" method="POST" id="frmKaryawan" enctype="multipart/form-data"> <!-- kalo di dalam form ada upload foto jangan lupa untuk dikasih enctype="multipart/form-data" -->
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  
                                  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  
                                  stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-barcode">
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                  <path d="M4 7v-1a2 2 0 0 1 2 -2h2" />
                                  <path d="M4 17v1a2 2 0 0 0 2 2h2" />
                                  <path d="M16 4h2a2 2 0 0 1 2 2v1" />
                                  <path d="M16 20h2a2 2 0 0 0 2 -2v-1" />
                                  <path d="M5 11h1v2h-1z" />
                                  <path d="M10 11l0 2" />
                                  <path d="M14 11h1v2h-1z" />
                                  <path d="M19 11l0 2" />
                                </svg>
                                </span>
                                <input type="text" value="" id="nik" class="form-control" name="nik" placeholder="NIK">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  
                                  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  
                                  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                  <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                  <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                </svg>
                                </span>
                                <input type="text" value="" id="nama_lengkap" class="form-control" name="nama_lengkap" placeholder="Nama Lengkap">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  
                                  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  
                                  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-diamond">
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                  <path d="M6 5h12l3 5l-8.5 9.5a.7 .7 0 0 1 -1 0l-8.5 -9.5l3 -5" />
                                  <path d="M10 12l-2 -2.2l.6 -1" />
                                </svg>
                                </span>
                                <input type="text" value="" id="jabatan" class="form-control" name="jabatan" placeholder="Jabatan">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  
                                  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  
                                  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-phone">
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                  <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" />
                                </svg>
                                </span>
                                <input type="text" value="" id="no_hp" class="form-control" name="no_hp" placeholder="No. HP">
                        </div>
                    </div>
                </div>
                <!-- Upload Foto -->
                <div class="row">
                    <div class="col-12">
                            <input type="file" name="foto" class="form-control">
                    </div>
                </div>
                <dv class="row mt-3">
                    <div class="col-12">
                        <select name="kode_dept" id="kode_dept" class="form-select">
                            <option value="">Departemen</option>
                            <!-- looping data departemen pada option -->
                            @foreach ($departemen as $d)
                            <option {{ Request('kode_dept')==$d->kode_dept ? 'selected' : '' }} value="{{ $d->kode_dept }}">{{ $d->nama_dept }} <!--  Request('kode_dept')==$d->kode_dept ? 'selected' : '' artinya kalo kita seacrh data nama departemen dan ada data yang ditampilkan maka option nama departemen tidak akan hilang -->
                            </option>
                            @endforeach
                        </select>
                    </div>
                </dv>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="form-group">
                            <button class="btn btn-primary w-100">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  
                                fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  
                                stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-send">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14l11 -11" />
                                <path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />
                            </svg>
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modals Edit Data-->
    <div class="modal modal-blur fade" id="modal-editkaryawan" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Data Karyawan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="loadeditform">
          </div>
        </div>
      </div>
    </div>
@endsection

{{-- jquery --}}
@push('myscript')
    <script>
        $(function(){
            $("#btnTambahkaryawan").click(function(){ // kita akan membuat event ketika button btnTambahkaryawan di klik, kita ingin menampilkan modal
                $("#modal-inputkaryawan").modal("show"); // memunculkan modal nya dengan cara kita panggil id modalnya lalu event modal dan kasih parameter show
            });

            $(".edit").click(function(){ // kenapa pakai class '.edit' karena ketika kita pilih salah satu data yang akan kita edit maka akan menampilkan semua data yang akan diedit. berbeda jika kita menggukan 'id' maka tidak akan bisa menampilkan data yang dipilih, karena jika pakai id hanya bisa ke satu elemen. misalnya ada 2 id yang sama maka hanya bisa memunculkan 1 elemen saja       
            var nik = $(this).attr('nik');
                // cara menampilkan form dari file yang berbeda (edit.blade.php), cara memasukkan / menampilkan form kedalam loadeditform adalah kita menggukan ajax
                $.ajax({
                    type : 'POST',
                    url : '/karyawan/edit',
                    cache : false,
                    data : {
                        _token:"{{ csrf_token(); }}", //token harus dikirim supaya tidak expired formnya
                        nik: nik
                    },
                    success : function(respond){ // kalo berhasil mengakses halaman ini yang berisi form edit tersebut maka akan ditampung ke respond ini, dan kemudian akan kita load ke id loadeditform
                        $("#loadeditform").html(respond); // kita memasukkan elemen html ke dalam id loadeditform
                    }
                });
            $("#modal-editkaryawan").modal("show"); // memunculkan modal nya dengan cara kita panggil id modalnya lalu event modal dan kasih parameter show
            });

            $(".delete-confirm").click(function(e){
                var form = $(this).closest('form');
                e.preventDefault(); 
                Swal.fire({
                    title: "Apakah Anda Yakin Akan Menghapus Data Tersebut?",
                    text: "Jika Ya Maka Data Akan Terhapus!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Hapus Saja!"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                        Swal.fire({
                        title: "Deleted!",
                        text: "Data Berhasil Di Hapus",
                        icon: "success"
                        });
                    }
                });
            });

            $("#frmKaryawan").submit(function(){ // event ketika form di submit
                var nik = $("#nik").val();
                var nama_lengkap = $("#nama_lengkap").val();
                var jabatan = $("#jabatan").val();
                var no_hp = $("#no_hp").val();
                var kode_dept = $("#frmKaryawan").find("#kode_dept").val();

                if (nik=="") {
                    // alert('NIK Harus Diisi');
                    Swal.fire({
                        title: 'Warning!',
                        text: 'NIK Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        $("#nik").focus(); // memposisikan kursor ke nik
                    });
                    return false; // supaya form berhenti tidak ter submit
                } else if (nama_lengkap == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Nama Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        $("#nama_lengkap").focus();
                    });
                    return false;
                } else if (jabatan == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Jabatan Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        $("#jabatan").focus();
                    });
                    return false;
                } else if (no_hp == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'No. HP Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        $("#no_hp").focus();
                    });
                    return false;
                } else if (kode_dept == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Departemen Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        $("#kode_dept").focus();
                    });
                    return false;
                }
            }); 
        });
    </script>
@endpush