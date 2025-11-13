@php
    use Carbon\Carbon;

    $bulanSekarang = Carbon::now()->month;
    $tahunSekarang = Carbon::now()->year;

    $direkturLembur = \App\Models\Lembur::where('status', '3')->whereMonth('tgl_mulai', $bulanSekarang)->whereYear('tgl_mulai', $tahunSekarang)->count();
    $direkturKegiatan = \App\Models\KegiatanHarian::where('status', '3')->whereMonth('tgl_kegiatan', $bulanSekarang)->whereYear('tgl_kegiatan', $tahunSekarang)->count();
    $direkturCutiTahunan = \App\Models\CutiTahunan::where('status', '3')->whereYear('tgl_mulai', $tahunSekarang)->count();
    $direkturIzinTerlambat = \App\Models\IzinTerlambat::where('status', '3')->whereMonth('tgl_izin', $bulanSekarang)->whereYear('tgl_izin', $tahunSekarang)->count();
    $direkturSakit = \App\Models\Sakit::where('status', '3')->whereMonth('tgl_mulai', $bulanSekarang)->whereYear('tgl_mulai', $tahunSekarang)->count();
    $direkturCutiMelahirkan = \App\Models\CutiMelahirkan::where('status', '3')->whereYear('tgl_mulai', $tahunSekarang)->count();
    $direkturIzinKeluar = \App\Models\IzinKeluar::where('status', '3')->whereMonth('tgl_izin', $bulanSekarang)->whereYear('tgl_izin', $tahunSekarang)->count();
    $direkturPotongGaji = \App\Models\PotongGaji::where('status', '3')->whereYear('tgl_mulai', $tahunSekarang)->count();
    $direkturPulangCepat = \App\Models\PulangCepat::where('status', '3')->whereYear('tgl_izin', $tahunSekarang)->count();
    $direkturCutiMenikah = \App\Models\CutiMenikah::where('status', '3')->whereYear('tgl_mulai', $tahunSekarang)->count();
@endphp
<li class="nav-item">
    <a class="nav-link @yield('menuManajemenKehadiran')"
       data-toggle="collapse"
       href="#manajemen-kehadiran"
       aria-expanded="false"
       aria-controls="manajemen-kehadiran">
        <i class="typcn typcn-calendar-outline menu-icon"></i>
        <span class="menu-title">Kehadiran</span>
        <i class="menu-arrow"></i>
    </a>
    <div class="collapse"
         id="manajemen-kehadiran">
        <ul class="nav flex-column sub-menu">
            <li class="nav-item">
                <a class="nav-link @yield('menuDataLembur')"
                   href="{{ route('direktur-lembur.index') }}">
                    Lembur
                    @if ($direkturLembur)
                        <div class="badge badge-danger">
                            {{ $direkturLembur > 9 ? '9+' : $direkturLembur }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataKegiatanHarian')"
                   href="{{ route('direktur-kegiatanharian.index') }}">
                    Kegiatan Harian
                    @if ($direkturKegiatan)
                        <div class="badge badge-danger">
                            {{ $direkturKegiatan > 9 ? '9+' : $direkturKegiatan }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataCutiTahunan')"
                   href="{{ route('direktur-cutitahunan.index') }}">
                    Cuti Tahunan
                    @if ($direkturCutiTahunan)
                        <div class="badge badge-danger">
                            {{ $direkturCutiTahunan > 9 ? '9+' : $direkturCutiTahunan }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataIzinTerlambat')"
                   href="{{ route('direktur-izinterlambat.index') }}">
                    Izin Terlambat
                    @if ($direkturIzinTerlambat)
                        <div class="badge badge-danger">
                            {{ $direkturIzinTerlambat > 9 ? '9+' : $direkturIzinTerlambat }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataIzinSakit')"
                   href="{{ route('direktur-sakit.index') }}">
                    Izin Sakit
                    @if ($direkturSakit)
                        <div class="badge badge-danger">
                            {{ $direkturSakit > 9 ? '9+' : $direkturSakit }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataCutiMelahirkan')"
                   href="{{ route('direktur-cutimelahirkan.index') }}">
                    Cuti Melahirkan
                    @if ($direkturCutiMelahirkan)
                        <div class="badge badge-danger">
                            {{ $direkturCutiMelahirkan > 9 ? '9+' : $direkturCutiMelahirkan }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataIzinKeluar')"
                   href="{{ route('direktur-izinkeluar.index') }}">
                    Izin Keluar
                    @if ($direkturIzinKeluar)
                        <div class="badge badge-danger">
                            {{ $direkturIzinKeluar > 9 ? '9+' : $direkturIzinKeluar }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataPotongGaji')"
                   href="{{ route('direktur-potonggaji.index') }}">
                    Cuti Potong Gaji
                    @if ($direkturPotongGaji)
                        <div class="badge badge-danger">
                            {{ $direkturPotongGaji > 9 ? '9+' : $direkturPotongGaji }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataPulangCepat')"
                   href="{{ route('direktur-pulangcepat.index') }}">
                    Izin Pulang Cepat
                    @if ($direkturPulangCepat)
                        <div class="badge badge-danger">
                            {{ $direkturPulangCepat > 9 ? '9+' : $direkturPulangCepat }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataCutiMenikah')"
                   href="{{ route('direktur-cutimenikah.index') }}">
                    Cuti Menikah
                    @if ($direkturCutiMenikah)
                        <div class="badge badge-danger">
                            {{ $direkturCutiMenikah > 9 ? '9+' : $direkturCutiMenikah }}
                        </div>
                    @endif
                </a>
            </li>
        </ul>
    </div>
</li>
