@php
    use Carbon\Carbon;

    $bulanSekarang = Carbon::now()->month;
    $tahunSekarang = Carbon::now()->year;

    $kepalaLembur = \App\Models\Lembur::where('atasan_id', Auth::user()->id)
        ->where('status', '1')
        ->whereMonth('tgl_mulai', $bulanSekarang)
        ->whereYear('tgl_mulai', $tahunSekarang)
        ->count();

    $kepalaKegiatanHarian = \App\Models\KegiatanHarian::where('atasan_id', Auth::user()->id)
        ->where('status', '1')
        ->whereMonth('tgl_kegiatan', $bulanSekarang)
        ->whereYear('tgl_kegiatan', $tahunSekarang)
        ->count();

    $kepalaCutiTahunan = \App\Models\CutiTahunan::where('atasan_id', Auth::user()->id)
        ->where('status', '1')
        ->whereYear('tgl_mulai', $tahunSekarang)
        ->count();

    $kepalaIzinTerlambat = \App\Models\IzinTerlambat::where('atasan_id', Auth::user()->id)
        ->where('status', '1')
        ->whereMonth('tgl_izin', $bulanSekarang)
        ->whereYear('tgl_izin', $tahunSekarang)
        ->count();

    $kepalaIzinSakit = \App\Models\Sakit::where('atasan_id', Auth::user()->id)
        ->where('status', '1')
        ->whereMonth('tgl_mulai', $bulanSekarang)
        ->whereYear('tgl_mulai', $tahunSekarang)
        ->count();

    $kepalaCutiMelahirkan = \App\Models\CutiMelahirkan::where('atasan_id', Auth::user()->id)
        ->where('status', '1')
        ->whereYear('tgl_mulai', $tahunSekarang)
        ->count();

    $kepalaIzinKeluar = \App\Models\IzinKeluar::where('atasan_id', Auth::user()->id)
        ->where('status', '1')
        ->whereMonth('tgl_izin', $bulanSekarang)
        ->whereYear('tgl_izin', $tahunSekarang)
        ->count();

    $kepalaPotongGaji = \App\Models\PotongGaji::where('atasan_id', Auth::user()->id)
        ->where('status', '1')
        ->whereMonth('tgl_mulai', $bulanSekarang)
        ->whereYear('tgl_mulai', $tahunSekarang)
        ->count();

    $kepalaPulangCepat = \App\Models\PulangCepat::where('atasan_id', Auth::user()->id)
        ->where('status', '1')
        ->whereMonth('tgl_izin', $bulanSekarang)
        ->whereYear('tgl_izin', $tahunSekarang)
        ->count();

    $kepalaCutiMenikah = \App\Models\CutiMenikah::where('atasan_id', Auth::user()->id)
        ->where('status', '1')
        ->whereYear('tgl_mulai', $tahunSekarang)
        ->count();
@endphp

{{--  Manajemen Kehadiran  --}}
<li class="nav-item">
    <a class="nav-link @yield('menuPeriksaKehadiran')"
       data-toggle="collapse"
       href="#periksa-kehadiran"
       aria-expanded="false"
       aria-controls="periksa-kehadiran">
        <i class="typcn typcn-time menu-icon"></i>
        <span class="menu-title">Periksa Kehadiran</span>
        <i class="menu-arrow"></i>
    </a>
    <div class="collapse"
         id="periksa-kehadiran">
        <ul class="nav flex-column sub-menu">
            <li class="nav-item">
                <a class="nav-link @yield('menuDataPeriksaLembur')"
                   href="{{ route('karyawan-periksalembur.index') }}">
                    Lembur
                    @if ($kepalaLembur)
                        <div class="badge badge-danger">
                            {{ $kepalaLembur > 9 ? '9+' : $kepalaLembur }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataPeriksaKegiatanHarian')"
                   href="{{ route('karyawan-periksakegiatanharian.index') }}">
                    Kegiatan Harian
                    @if ($kepalaKegiatanHarian)
                        <div class="badge badge-danger">
                            {{ $kepalaKegiatanHarian > 9 ? '9+' : $kepalaKegiatanHarian }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataPeriksaCutiTahunan')"
                   href="{{ route('karyawan-periksacutitahunan.index') }}">
                    Cuti Tahunan
                    @if ($kepalaCutiTahunan)
                        <div class="badge badge-danger">
                            {{ $kepalaCutiTahunan > 9 ? '9+' : $kepalaCutiTahunan }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataPeriksaIzinTerlambat')"
                   href="{{ route('karyawan-periksaizinterlambat.index') }}">
                    Izin Terlambat
                    @if ($kepalaIzinTerlambat)
                        <div class="badge badge-danger">
                            {{ $kepalaIzinTerlambat > 9 ? '9+' : $kepalaIzinTerlambat }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataPeriksaIzinSakit')"
                   href="{{ route('karyawan-periksasakit.index') }}">
                    Izin Sakit
                    @if ($kepalaIzinSakit)
                        <div class="badge badge-danger">
                            {{ $kepalaIzinSakit > 9 ? '9+' : $kepalaIzinSakit }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuPeriksaDataCutiMelahirkan')"
                   href="{{ route('karyawan-periksacutimelahirkan.index') }}">
                    Cuti Melahirkan
                    @if ($kepalaCutiMelahirkan)
                        <div class="badge badge-danger">
                            {{ $kepalaCutiMelahirkan > 9 ? '9+' : $kepalaCutiMelahirkan }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuPeriksaDataIzinKeluar')"
                   href="{{ route('karyawan-periksaizinkeluar.index') }}">
                    Izin Keluar
                    @if ($kepalaIzinKeluar)
                        <div class="badge badge-danger">
                            {{ $kepalaIzinKeluar > 9 ? '9+' : $kepalaIzinKeluar }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuPeriksaDataPotongGaji')"
                   href="{{ route('karyawan-periksapotonggaji.index') }}">
                    Cuti Potong Gaji
                    @if ($kepalaPotongGaji)
                        <div class="badge badge-danger">
                            {{ $kepalaPotongGaji > 9 ? '9+' : $kepalaPotongGaji }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuPeriksaDataPulangCepat')"
                   href="{{ route('karyawan-periksapulangcepat.index') }}">
                    Izin Pulang Cepat
                    @if ($kepalaPulangCepat)
                        <div class="badge badge-danger">
                            {{ $kepalaPulangCepat > 9 ? '9+' : $kepalaPulangCepat }}
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuPeriksaDataCutiMenikah')"
                   href="{{ route('karyawan-periksacutimenikah.index') }}">
                    Cuti Menikah
                    @if ($kepalaCutiMenikah)
                        <div class="badge badge-danger">
                            {{ $kepalaCutiMenikah > 9 ? '9+' : $kepalaCutiMenikah }}
                        </div>
                    @endif
                </a>
            </li>
        </ul>
    </div>
</li>
