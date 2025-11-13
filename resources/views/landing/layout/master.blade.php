<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet"
          href="{{ asset('plugins/bootstrap-5.2.3/css/bootstrap.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('css/landing/landing-style.css') }}">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet"
          href="{{ asset('plugins/sweeper/css/swiper-bundle.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light fixed-top modern-navbar py-3">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand fw-bold d-flex align-items-center"
               href="{{ route('landing.index') }}">
                <img src="{{ asset('images/logo.png') }}"
                     alt="RSKM Regina Eye Center"
                     height="45"
                     class="me-2">
                <span>RSKM Regina Eye Center</span>
            </a>

            <!-- Toggle Button -->
            <button class="navbar-toggler border-0 shadow-sm"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarMenu"
                    aria-controls="navbarMenu"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu -->
            <div class="collapse navbar-collapse"
                 id="navbarMenu">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link @yield('menuLandingBeranda')"
                           href="{{ route('landing.index') }}">Beranda</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link @yield('menuTentangKami') dropdown-toggle"
                           href="#"
                           id="tentangDropdown"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            Tentang Kami
                        </a>
                        <ul class="dropdown-menu shadow-lg border-0 rounded-4 animate-dropdown"
                            aria-labelledby="tentangDropdown">
                            <li><a class="dropdown-item @yield('menuProfilSingkat')"
                                   href="{{ route('landing.profilsingkat') }}">Profil Singkat</a></li>
                            <li><a class="dropdown-item @yield('menuSejarahRumahSakit')"
                                   href="{{ route('landing.sejarahrumahsakit') }}">Sejarah Rumah Sakit</a></li>
                            <li><a class="dropdown-item @yield('menuVisiMisi')"
                                   href="{{ route('landing.visimisi') }}">Visi & Misi</a></li>
                            <li><a class="dropdown-item @yield('menuStrukturOrganisasi')"
                                   href="{{ route('landing.strukturorganisasi') }}">Struktur Organisasi</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link @yield('menuDokterMedis') dropdown-toggle"
                           href="#"
                           id="dokterDropdown"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            Dokter & Medis
                        </a>
                        <ul class="dropdown-menu shadow-lg border-0 rounded-4 animate-dropdown"
                            aria-labelledby="dokterDropdown">
                            <li><a class="dropdown-item @yield('menuDokterSpesialis')"
                                   href="{{ route('landing.dokterspesialis') }}">Dokter Spesialis</a></li>
                            <li><a class="dropdown-item @yield('menuTenagaMedis')"
                                   href="{{ route('landing.tenagamedis') }}">Tenaga Medis</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link @yield('menuLayanan') dropdown-toggle"
                           href="#"
                           id="layananDropdown"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            Layanan
                        </a>
                        <ul class="dropdown-menu shadow-lg border-0 rounded-4 animate-dropdown"
                            aria-labelledby="layananDropdown">
                            <li><a class="dropdown-item @yield('menuLayananUnggulan')"
                                   href="{{ route('landing.layananunggulan') }}">Layanan Unggulan</a></li>
                            <li><a class="dropdown-item @yield('menuLayananUmum')"
                                   href="{{ route('landing.layananumum') }}">Layanan Umum</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link @yield('menuFasilitas') dropdown-toggle"
                           href="#"
                           id="fasilitasDropdown"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            Fasilitas
                        </a>
                        <ul class="dropdown-menu shadow-lg border-0 rounded-4 animate-dropdown"
                            aria-labelledby="fasilitasDropdown">
                            <li><a class="dropdown-item @yield('menuRuangOperasi')"
                                   href="{{ route('landing.ruangoperasi') }}">Ruang Operasi</a></li>
                            <li><a class="dropdown-item"
                                   href="#ruang-periksa">Ruang Periksa</a></li>
                            <li><a class="dropdown-item"
                                   href="#ruang-tunggu">Ruang Tunggu</a></li>
                            <li><a class="dropdown-item"
                                   href="#penunjang">Fasilitas Penunjang</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link  @yield('menuInformasiPasien') dropdown-toggle"
                           href="#"
                           id="informasiDropdown"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            Informasi Pasien
                        </a>
                        <ul class="dropdown-menu shadow-lg border-0 rounded-4 animate-dropdown"
                            aria-labelledby="informasiDropdown">
                            <li><a class="dropdown-item @yield('menuTataTertib')"
                                   href="{{ route('landing.tatatertib') }}">Jam Besuk & Tata Tertib</a></li>
                            <li><a class="dropdown-item @yield('menuKerjasamaAsuransi')"
                                   href="{{ route('landing.kerjasamaasuransi') }}">Kerjasama Asuransi</a></li>
                            <li><a class="dropdown-item"
                                   href="#bpjs">Informasi BPJS</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link @yield('menuLandingBerita')"
                           href="{{ route('landing.berita') }}">Berita</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link @yield('menuLandingKontak')"
                           href="{{ route('landing.kontak') }}">Kontak</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="footer bg-dark text-light pt-5 pb-3 mt-5">
        <div class="container">
            <div class="row gy-4">
                <!-- Kolom 1: Logo & Deskripsi -->
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('images/logo.png') }}"
                             alt="Regina Eye Center"
                             height="50"
                             class="me-2">
                        <h5 class="fw-bold text-orange mb-0">RSKM Regina Eye Center</h5>
                    </div>
                    <p class="small text-white-50">
                        Rumah Sakit Khusus Mata Regina Eye Center berkomitmen memberikan pelayanan kesehatan mata yang profesional, empatik, dan berstandar tinggi untuk masyarakat Sumatera Barat dan
                        sekitarnya.
                    </p>
                </div>

                <!-- Kolom 2: Menu Cepat -->
                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-bold text-orange mb-3">Menu Cepat</h6>
                    <ul class="list-unstyled">
                        <li><a href="#tentang"
                               class="footer-link">Tentang Kami</a></li>
                        <li><a href="#layanan"
                               class="footer-link">Layanan</a></li>
                        <li><a href="#dokter"
                               class="footer-link">Dokter</a></li>
                        <li><a href="#kontak-lokasi"
                               class="footer-link">Kontak</a></li>
                    </ul>
                </div>

                <!-- Kolom 3: Kontak Ringkas -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold text-orange mb-3">Kontak Kami</h6>
                    <ul class="list-unstyled small text-white-50">
                        <li><i class="bi bi-geo-alt-fill text-orange me-2"></i> Jl. H. Agus Salim No.11A, Sawahan, Kota Padang</li>
                        <li><i class="bi bi-telephone-fill text-orange me-2"></i> (0751) 810456</li>
                        <li><i class="bi bi-envelope-fill text-orange me-2"></i> humasmarketing.rec@gmail.com</li>
                    </ul>
                </div>

                <!-- Kolom 4: Sosial Media -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold text-orange mb-3">Ikuti Kami</h6>
                    <div class="d-flex gap-3 fs-5">
                        <a href="https://www.facebook.com/share/1D2tTG3X8E/"
                           class="text-light social-link"
                           target="_blank"><i class="bi bi-facebook"></i></a>
                        <a href="https://x.com/reginaeyecenter?t=_7b7-V_PD1nvLnFOoowX1w&s=09"
                           class="text-light social-link"
                           target="_blank"><i class="bi bi-twitter"></i></a>
                        <a href="https://www.instagram.com/regina.eyecenter?utm_source=qr&igsh=dXF4eTlhcXZneDF3"
                           class="text-light social-link"
                           target="_blank"><i class="bi bi-instagram"></i></a>
                        <a href="https://www.youtube.com/@ReginaEyeCenter"
                           class="text-light social-link"
                           target="_blank"><i class="bi bi-youtube"></i></a>
                        <a href="https://www.tiktok.com/@reginaeyecenter?_r=1&_t=ZS-911nMQccA7z"
                           class="text-light social-link"
                           target="_blank"><i class="bi bi-tiktok"></i></a>
                    </div>
                </div>
            </div>

            <!-- Garis pemisah -->
            <hr class="border-secondary my-4">

            <!-- Hak Cipta -->
            <div class="text-center small text-white-50">
                © {{ date('Y') }} <span class="text-orange fw-semibold">RSKM Regina Eye Center</span>. Semua Hak Dilindungi.
            </div>
        </div>
    </footer>

    <script src="{{ asset('plugins/jquery/jquery-min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-5.2.3/js/bootstrap.bundle.min.js') }}"></script>
    <!-- SwiperJS -->
    <script src="{{ asset('plugins/sweeper/js/swiper-bundle.min.js') }}"></script>

    <script>
        const swiper = new Swiper(".mySwiper", {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            grabCursor: true,
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                576: {
                    slidesPerView: 2
                },
                768: {
                    slidesPerView: 3
                },
                992: {
                    slidesPerView: 4
                },
            },
        });
    </script>
    <script>
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.modern-navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
    @stack('custom-script')
</body>

</html>
