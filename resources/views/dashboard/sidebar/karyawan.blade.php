{{--  Manajemen Kehadiran  --}}
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
                   href="{{ route('karyawan-lembur.index') }}">
                    Lembur
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataKegiatanHarian')"
                   href="{{ route('karyawan-kegiatanharian.index') }}">
                    Kegiatan Harian
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataCutiTahunan')"
                   href="{{ route('karyawan-cutitahunan.index') }}">
                    Cuti Tahunan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataIzinTerlambat')"
                   href="{{ route('karyawan-izinterlambat.index') }}">
                    Izin Terlambat
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataIzinSakit')"
                   href="{{ route('karyawan-sakit.index') }}">
                    Izin Sakit
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataCutiMelahirkan')"
                   href="{{ route('karyawan-cutimelahirkan.index') }}">
                    Cuti Melahirkan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataIzinKeluar')"
                   href="{{ route('karyawan-izinkeluar.index') }}">
                    Izin Keluar
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataPotongGaji')"
                   href="{{ route('karyawan-potonggaji.index') }}">
                    Cuti Potong Gaji
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataPulangCepat')"
                   href="{{ route('karyawan-pulangcepat.index') }}">
                    Izin Pulang Cepat
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataCutiMenikah')"
                   href="{{ route('karyawan-cutimenikah.index') }}">
                    Cuti Menikah
                </a>
            </li>
        </ul>
    </div>
</li>
