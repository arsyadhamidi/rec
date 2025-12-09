{{-- Data Master --}}
<li class="nav-item">
    <a class="nav-link @yield('menuDataMaster')"
       data-toggle="collapse"
       href="#data-master"
       aria-expanded="false"
       aria-controls="data-master">
        <i class="typcn typcn-folder-open menu-icon"></i>
        <span class="menu-title">Data Master</span>
        <i class="menu-arrow"></i>
    </a>
    <div class="collapse"
         id="data-master">
        <ul class="nav flex-column sub-menu">
            <li class="nav-item">
                <a class="nav-link @yield('menuDataDokter')"
                   href="{{ route('admin-dokter.index') }}">
                    Data Dokter
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuSpesialis')"
                   href="{{ route('admin-spesialis.index') }}">
                    Data Spesialis
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuKategoriBerita')"
                   href="{{ route('admin-kategoriberita.index') }}">
                    Kategori Berita
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuKerjasama')"
                   href="{{ route('admin-kerjasama.index') }}">
                    Asuransi Kesehatan
                </a>
            </li>
        </ul>
    </div>
</li>

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
                   href="{{ route('admin-lembur.index') }}">
                    Lembur
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataKegiatanHarian')"
                   href="{{ route('admin-kegiatanharian.index') }}">
                    Kegiatan Harian
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataCutiTahunan')"
                   href="{{ route('admin-cutitahunan.index') }}">
                    Cuti Tahunan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataIzinTerlambat')"
                   href="{{ route('admin-izinterlambat.index') }}">
                    Izin Terlambat
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataIzinSakit')"
                   href="{{ route('admin-sakit.index') }}">
                    Izin Sakit
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataCutiMelahirkan')"
                   href="{{ route('admin-cutimelahirkan.index') }}">
                    Cuti Melahirkan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataIzinKeluar')"
                   href="{{ route('admin-izinkeluar.index') }}">
                    Izin Keluar
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataPotongGaji')"
                   href="{{ route('admin-potonggaji.index') }}">
                    Cuti Potong Gaji
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataPulangCepat')"
                   href="{{ route('admin-pulangcepat.index') }}">
                    Izin Pulang Cepat
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataCutiMenikah')"
                   href="{{ route('admin-cutimenikah.index') }}">
                    Cuti Menikah
                </a>
            </li>
        </ul>
    </div>
</li>

{{--  Publikasi / Berita  --}}
<li class="nav-item">
    <a class="nav-link @yield('menuBerita')" href="{{ route('admin-berita.index') }}">
        <i class="typcn typcn-news menu-icon"></i>
        <span class="menu-title">Publikasi / Berita</span>
    </a>
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
                   href="{{ route('admin-users.index') }}">
                    User Registrasi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuStatusAutentikasi')"
                   href="{{ route('admin-level.index') }}">
                    Status Autentikasi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuDataJabatan')"
                   href="{{ route('admin-jabatan.index') }}">
                    Jabatan
                </a>
            </li>
        </ul>
    </div>
</li>
