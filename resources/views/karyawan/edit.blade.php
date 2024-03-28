<form action="/karyawan/{{ $karyawan->nik }}/update" method="POST" id="frmKaryawan" enctype="multipart/form-data"> <!-- kalo di dalam form ada upload foto jangan lupa untuk dikasih enctype="multipart/form-data" -->
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
                                <input type="text" readonly value="{{ $karyawan->nik }}" id="nik" class="form-control" name="nik" placeholder="NIK">
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
                                <input type="text" value="{{ $karyawan->nama_lengkap }}" id="nama_lengkap" class="form-control" name="nama_lengkap" placeholder="Nama Lengkap">
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
                                <input type="text" value="{{ $karyawan->jabatan }}" id="jabatan" class="form-control" name="jabatan" placeholder="Jabatan">
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
                                <input type="text" value="{{ $karyawan->no_hp }}" id="no_hp" class="form-control" name="no_hp" placeholder="No. HP">
                        </div>
                    </div>
                </div>
                <!-- Upload Foto -->
                <div class="row">
                    <div class="col-12">
                            <input type="file" name="foto" class="form-control">
                            <input type="hidden" name="old_foto" value="{{ $karyawan->foto }}">
                    </div>
                </div>
                <dv class="row mt-3">
                    <div class="col-12">
                        <select name="kode_dept" id="kode_dept" class="form-select">
                            <option value="">Departemen</option>
                            <!-- looping data departemen pada option -->
                            @foreach ($departemen as $d)
                            <option {{ $karyawan->kode_dept == $d->kode_dept ? 'selected' : '' }} value="{{ $d->kode_dept }}">{{ $d->nama_dept }} <!--  Request('kode_dept')==$d->kode_dept ? 'selected' : '' artinya kalo kita seacrh data nama departemen dan ada data yang ditampilkan maka option nama departemen tidak akan hilang -->
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