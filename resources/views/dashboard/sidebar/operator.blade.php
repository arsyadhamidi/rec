@php
    use Carbon\Carbon;

    $bulanSekarang = Carbon::now()->month;
    $tahunSekarang = Carbon::now()->year;

    $operatorLembur = \App\Models\Lembur::where('status', '2')->whereMonth('tgl_mulai', $bulanSekarang)->whereYear('tgl_mulai', $tahunSekarang)->count();
    $operatorKegiatan = \App\Models\KegiatanHarian::where('status', '2')->whereMonth('tgl_kegiatan', $bulanSekarang)->whereYear('tgl_kegiatan', $tahunSekarang)->count();
    $operatorCutiTahunan = \App\Models\CutiTahunan::where('status', '2')->whereYear('tgl_mulai', $tahunSekarang)->count();
    $operatorIzinTerlambat = \App\Models\IzinTerlambat::where('status', '2')->whereMonth('tgl_izin', $bulanSekarang)->whereYear('tgl_izin', $tahunSekarang)->count();
    $operatorSakit = \App\Models\Sakit::where('status', '2')->whereMonth('tgl_mulai', $bulanSekarang)->whereYear('tgl_mulai', $tahunSekarang)->count();
    $operatorCutiMelahirkan = \App\Models\CutiMelahirkan::where('status', '2')->whereYear('tgl_mulai', $tahunSekarang)->count();
    $operatorIzinKeluar = \App\Models\IzinKeluar::where('status', '2')->whereMonth('tgl_izin', $bulanSekarang)->whereYear('tgl_izin', $tahunSekarang)->count();
    $operatorPotongGaji = \App\Models\PotongGaji::where('status', '2')->whereYear('tgl_mulai', $tahunSekarang)->count();
    $operatorPulangCepat = \App\Models\PulangCepat::where('status', '2')->whereYear('tgl_izin', $tahunSekarang)->count();
    $operatorCutiMenikah = \App\Models\CutiMenikah::where('status', '2')->whereYear('tgl_mulai', $tahunSekarang)->count();
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
                   href="{{ route('operator-lembur.index') }}">
                    Lembur
                    @if ($operatorLembur)
                        <div class="badge badge-danger">
                            {{ $operatorLembur > 9 ? '9+' : $operatorLembur }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataKegiatanHarian')"
                   href="{{ route('operator-kegiatanharian.index') }}">
                    Kegiatan Harian
                    @if ($operatorKegiatan)
                        <div class="badge badge-danger">
                            {{ $operatorKegiatan > 9 ? '9+' : $operatorKegiatan }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataCutiTahunan')"
                   href="{{ route('operator-cutitahunan.index') }}">
                    Cuti Tahunan
                    @if ($operatorCutiTahunan)
                        <div class="badge badge-danger">
                            {{ $operatorCutiTahunan > 9 ? '9+' : $operatorCutiTahunan }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataIzinTerlambat')"
                   href="{{ route('operator-izinterlambat.index') }}">
                    Izin Terlambat
                    @if ($operatorIzinTerlambat)
                        <div class="badge badge-danger">
                            {{ $operatorIzinTerlambat > 9 ? '9+' : $operatorIzinTerlambat }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataIzinSakit')"
                   href="{{ route('operator-sakit.index') }}">
                    Izin Sakit
                    @if ($operatorSakit)
                        <div class="badge badge-danger">
                            {{ $operatorSakit > 9 ? '9+' : $operatorSakit }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataCutiMelahirkan')"
                   href="{{ route('operator-cutimelahirkan.index') }}">
                    Cuti Melahirkan
                    @if ($operatorCutiMelahirkan)
                        <div class="badge badge-danger">
                            {{ $operatorCutiMelahirkan > 9 ? '9+' : $operatorCutiMelahirkan }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataIzinKeluar')"
                   href="{{ route('operator-izinkeluar.index') }}">
                    Izin Keluar
                    @if ($operatorIzinKeluar)
                        <div class="badge badge-danger">
                            {{ $operatorIzinKeluar > 9 ? '9+' : $operatorIzinKeluar }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataPotongGaji')"
                   href="{{ route('operator-potonggaji.index') }}">
                    Cuti Potong Gaji
                    @if ($operatorPotongGaji)
                        <div class="badge badge-danger">
                            {{ $operatorPotongGaji > 9 ? '9+' : $operatorPotongGaji }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataPulangCepat')"
                   href="{{ route('operator-pulangcepat.index') }}">
                    Izin Pulang Cepat
                    @if ($operatorPulangCepat)
                        <div class="badge badge-danger">
                            {{ $operatorPulangCepat > 9 ? '9+' : $operatorPulangCepat }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataCutiMenikah')"
                   href="{{ route('operator-cutimenikah.index') }}">
                    Cuti Menikah
                    @if ($operatorCutiMenikah)
                        <div class="badge badge-danger">
                            {{ $operatorCutiMenikah > 9 ? '9+' : $operatorCutiMenikah }}
                        </div>
                    @endif
                </a>
            </li>
        </ul>
    </div>
</li>

{{--  Manajemen User  --}}
<li class="nav-item">
    <a class="nav-link @yield('menuDataAutentikasi')"
       data-toggle="collapse"
       href="#data-autentikasi"
       aria-expanded="false"
       aria-controls="data-autentikasi">
        <i class="typcn typcn-user-outline menu-icon"></i>
        <span class="menu-title">Manajemen User</span>
        <i class="menu-arrow"></i>
    </a>
    <div class="collapse"
         id="data-autentikasi">
        <ul class="nav flex-column sub-menu">
            <li class="nav-item">
                <a class="nav-link @yield('menuDataUserRegistrasi')"
                   href="{{ route('operator-users.index') }}">
                    User Registrasi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuStatusAutentikasi')"
                   href="{{ route('operator-level.index') }}">
                    Status Autentikasi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataJabatan')"
                   href="{{ route('operator-jabatan.index') }}">
                    Jabatan
                </a>
            </li>
        </ul>
    </div>
</li>
