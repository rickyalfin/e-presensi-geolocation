@extends('layouts.presensi')

@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">History Presensi</div>
    <div class="right"></div>
</div>
<!-- * App -->
@endsection

@section('content')
    <div class="row" style="margin-top: 70px">
        <div class="col">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <select name="bulan" id="bulan" class="form-control">
                            <option value="">Bulan</option>
                            <!-- Membuaat option nama bulan -->
                            @for ($i = 1; $i <= 12; $i++)
                                <!-- date("m") == $i ? 'selected' : '' (jika bulan saat ini = variabel i, maka tambahkan selected didalam option) -->
                                <option value="{{ $i }}" {{ date("m") == $i ? 'selected' : '' }}>{{ $namabulan[$i] }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <select name="tahun" id="tahun" class="form-control">
                            <option value="">Tahun</option>
                            @php
                                $tahunmulai = 2024; //tahun mulai
                                $tahunnow = date("Y"); // tahun sekarang sesuai dengan waktu yang berjalan
                            @endphp
                            <!-- Looping dari tahun mulai sampai tahun sekarang dan seterusnya -->
                            @for($tahun = $tahunmulai; $tahun <= $tahunnow; $tahun++)
                            <option value="{{ $tahun }}" {{ date("Y") == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <button class="btn btn-primary btn-block" id="getdata">
                            <ion-icon name="search-outline">
                                </ion-icon>Search</button>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Membuat sebuah element untuk menampilkan data dari gethistory -->
    <div class="row">
        <div class="col" id="showhistory"></div>
    </div>
@endsection

<!-- push('myscript') supaya kode JQuery yang kita ketik muncul dibawah script yang ada di master tamplatenya  -->
@push('myscript')
    <script>
        $(function(){
            // sebuah event dimana jika button search di klik, maka akan mengeksekusi function
            $("#getdata").click(function(e){
                var bulan = $("#bulan").val(); // variable bulan ini berisi value dari form bulan
                var tahun = $("#tahun").val(); // variabel tahun ini berisi value dari form tahun
                $.ajax({
                    type: "POST",
                    url:'/gethistory',
                    data:{
                        _token : "{{ csrf_token() }}",
                        bulan : bulan,
                        tahun  : tahun,
                    },
                    cache:false,
                    success:function(respond) {
                        $("#showhistory").html(respond); // kenapa html? karena return dari gethistory nantinya element html. Kita akan memasukan element html ke id showhistory
                    }
                });
            });
        });
    </script>
@endpush