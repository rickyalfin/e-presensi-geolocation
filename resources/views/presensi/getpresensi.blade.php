@foreach ($presensi as $d)
@php
    $foto_in = Storage::url('uploads/absensi/'. $d->foto_in);
    $foto_out = Storage::url('uploads/absensi/'. $d->foto_out);
@endphp
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $d->nik }}</td>
        <td>{{ $d->nama_lengkap }}</td>
        <td>{{ $d->nama_dept }}</td>
        <td>{{ $d->jam_in }}</td>
        <td>
            <img src="{{ url($foto_in) }}" class="avatar" alt="">
        </td>
        <td>{!! $d->jam_out != null ? $d->jam_out : '<span class="badge bg-danger">Belum Absen</span>' !!}</td> <!-- tanda '!!' digunakan untuk membaca kode html pada <span class="badge bg-danger"> -->
        <td>
            @if ($d->jam_out != null)
                <img src="{{ url($foto_out) }}" class="avatar" alt="">
                @else
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  
                stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  
                class="icon icon-tabler icons-tabler-outline icon-tabler-camera-off">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M8.297 4.289a.997 .997 0 0 1 .703 -.289h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v8m-1.187 2.828c-.249 .11 -.524 .172 -.813 .172h-14a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2h1c.298 0 .58 -.065 .834 -.181" />
                <path d="M10.422 10.448a3 3 0 1 0 4.15 4.098" />
                <path d="M3 3l18 18" />
            </svg>
            @endif
        </td>
        <td>
            @if ($d->jam_in >= '06.00')
                <span class="badge bg-danger">Terlambat</span>
                @else
                <span class="badge bg-success">Tepat waktu</span>
            @endif
        </td>
    </tr>
@endforeach