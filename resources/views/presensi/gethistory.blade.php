<!-- Notifikasi jika data history kosong -->
@if ($history->isEmpty())
    <div class="alert alert-warning">
        <p>Data Belum Ada</p>
    </div>
@endif

@foreach ($history as $d)
<ul class="listview image-listview">
    <li>
        <div class="item">
            <!-- dibagian image nya kita masukan foto ketika user absen masuk -->
            @php
                $path = Storage::url('uploads/absensi/'.$d->foto_in); 
            @endphp
            <img src="{{ url($path) }}" alt="image" class="image">
                <div class="in">
                    <div>
                        <b>{{ date("d-m-Y", strtotime($d->tgl_presensi)) }} </b><br> <!-- mengubah format tangal menjadi (tanggal-bulan-tahun), dimana default dari mysql (tahun-bulan-tanggal) -->
                        {{-- <small class="text-muted">{{ $d->jabatan }}</small> --}}
                    </div>
                    <!-- jika kondisi masuk lebih dari jam 07:00 maka warna background merah jika tidak maka hijau-->
                    <span class="badge {{ $d->jam_in < "07:00" ? "bg-success" : "bg-danger" }}">{{ $d->jam_in }}</span>
                    <span class="badge bg-primary">{{ $d->jam_out }}</span>
                </div>
            </div>
    </li>
</ul>
@endforeach