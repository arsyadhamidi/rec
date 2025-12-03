@extends('landing.layout.master')
@section('title', 'Beranda | RSKM Regina Eye Center')
@section('menuLandingBeranda', 'active')
@section('content')
    <!-- ======= Hero Section ======= -->
    <section id="hero"
             class="position-relative">
        <div id="heroCarousel"
             class="carousel slide"
             data-bs-ride="carousel">

            <!-- Indicators -->
            <div class="carousel-indicators">
                <button type="button"
                        data-bs-target="#heroCarousel"
                        data-bs-slide-to="0"
                        class="active"
                        aria-current="true"
                        aria-label="Slide 1"></button>
                <button type="button"
                        data-bs-target="#heroCarousel"
                        data-bs-slide-to="1"
                        aria-label="Slide 2"></button>
                <button type="button"
                        data-bs-target="#heroCarousel"
                        data-bs-slide-to="2"
                        aria-label="Slide 3"></button>
            </div>

            <!-- Slides -->
            <div class="carousel-inner">

                <!-- Slide 1 -->
                <div class="carousel-item active">
                    <img src="{{ asset('images/gallery-1.jpg') }}"
                         class="d-block w-100"
                         alt="RSKM Regina Eye Center">
                    <div class="carousel-caption d-flex flex-column justify-content-center align-items-center text-center">
                        <h1 class="fw-bold display-5 text-white shadow-sm">Pusat Pelayanan Kesehatan Mata Terpadu di Sumatera Barat</h1>
                        <p class="lead text-white-50 mb-4">RSKM Regina Eye Center berkomitmen memberikan pelayanan profesional untuk menjaga kesehatan mata Anda.</p>
                        <div>
                            <a href="https://wa.me/628116640456?text=Halo%20admin%2C%20saya%20ingin%20reservasi%20berobat%20ke%20dokter"
                               class="btn btn-primary btn-lg px-4 me-3"
                               target="_blank">Daftar via WhatsApp</a>
                            <a href="#dokter"
                               class="btn btn-outline-light btn-lg px-4">Lihat Jadwal Dokter</a>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item">
                    <img src="{{ asset('images/gallery-2.jpg') }}"
                         class="d-block w-100"
                         alt="Layanan Katarak">
                    <div class="carousel-caption d-flex flex-column justify-content-center align-items-center text-center">
                        <h2 class="fw-bold text-white">Operasi Katarak Phacoemulsifikasi</h2>
                        <p class="text-white-50 mb-4">Teknologi modern tanpa jahitan untuk pemulihan penglihatan yang cepat.</p>
                        <a href="#layanan"
                           class="btn btn-primary btn-lg px-4">Pelajari Lebih Lanjut</a>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="carousel-item">
                    <img src="{{ asset('images/gallery-3.jpg') }}"
                         class="d-block w-100"
                         alt="Layanan Retina">
                    <div class="carousel-caption d-flex flex-column justify-content-center align-items-center text-center">
                        <h2 class="fw-bold text-white">Layanan Retina dan Refraksi</h2>
                        <p class="text-white-50 mb-4">Diagnosis dan perawatan menyeluruh untuk menjaga kesehatan mata Anda.</p>
                        <a href="#layanan"
                           class="btn btn-primary btn-lg px-4">Lihat Semua Layanan</a>
                    </div>
                </div>
            </div>

            <!-- Controls -->
            <button class="carousel-control-prev"
                    type="button"
                    data-bs-target="#heroCarousel"
                    data-bs-slide="prev">
                <span class="carousel-control-prev-icon"
                      aria-hidden="true"></span>
                <span class="visually-hidden">Sebelumnya</span>
            </button>
            <button class="carousel-control-next"
                    type="button"
                    data-bs-target="#heroCarousel"
                    data-bs-slide="next">
                <span class="carousel-control-next-icon"
                      aria-hidden="true"></span>
                <span class="visually-hidden">Berikutnya</span>
            </button>

        </div>
    </section>
    <!-- ======= End Hero Section ======= -->

    <!-- ===== Tentang Kami Section ===== -->
    <section id="tentang"
             class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">

                <!-- Foto Gedung -->
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('images/about.jpg') }}"
                         alt="Gedung RSKM Regina Eye Center"
                         class="img-fluid rounded-3 shadow-sm">
                </div>

                <!-- Deskripsi Profil -->
                <div class="col-lg-6">
                    <h6 class="text-uppercase fw-bold text-secondary mb-2">Tentang Kami</h6>
                    <h2 class="fw-bold mb-3 text-dark">Rumah Sakit Khusus Mata Regina Eye Center</h2>

                    <p class="text-muted"
                       style="text-align: justify;">
                        Rumah Sakit Khusus Mata Regina Eye Center adalah rumah sakit swasta yang terletak di
                        Jln. H. Agus Salim No. 11 A, Kelurahan Sawahan, Kecamatan Padang Timur, Kota Padang,
                        Provinsi Sumatera Barat. Regina Eye Center didirikan pada tanggal <b>28 April 2008</b>
                        di bawah kepemilikan <b>PT. Regina Cahaya Insani</b> dengan Akta Notaris Nomor 28
                        tanggal 25 Juni 2008. Awalnya berdiri sebagai <b>Klinik Mata Regina Eye Center</b>.
                    </p>

                    <p class="text-muted"
                       style="text-align: justify;">
                        Dalam rangka meningkatkan mutu pelayanan dan kepuasan pasien,
                        klinik ini resmi menjadi <b>Rumah Sakit Khusus Mata Regina Eye Center</b> sesuai
                        dengan Surat Keputusan Dinas Badan Penanaman Modal dan Pelayanan Perizinan Terpadu
                        Kota Padang dengan Nomor: <b>001.1/PK-10RS/DPMPTSP/VIII-2020</b>.
                    </p>

                    <!-- Nilai Utama -->
                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center me-3"
                                     style="width: 45px; height: 45px; color: var(--main-color); font-size: 1.3rem;">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <span class="fw-semibold">Profesional</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center me-3"
                                     style="width: 45px; height: 45px; color: var(--main-color); font-size: 1.3rem;">
                                    <i class="bi bi-heart-fill"></i>
                                </div>
                                <span class="fw-semibold">Empatik</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center me-3"
                                     style="width: 45px; height: 45px; color: var(--main-color); font-size: 1.3rem;">
                                    <i class="bi bi-eye-fill"></i>
                                </div>
                                <span class="fw-semibold">Berpengalaman</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center me-3"
                                     style="width: 45px; height: 45px; color: var(--main-color); font-size: 1.3rem;">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <span class="fw-semibold">Terpercaya</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol -->
                    <a href="#detail-tentang"
                       class="btn btn-main mt-4 px-4 py-2">Selengkapnya</a>
                </div>

            </div>
        </div>
    </section>

    <section id="dokter"
             class="py-5">
        <div class="container-fluid text-center">
            <h2 class="fw-bold text-main mb-3">Dokter & Tenaga Medis</h2>
            <p class="text-muted mb-5">
                Tim dokter spesialis berpengalaman siap memberikan pelayanan terbaik untuk kesehatan mata Anda.
            </p>

            <!-- Swiper Container -->
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @foreach ($dokters as $dokter)
                        <div class="swiper-slide">
                            <div class="card border-0 shadow-sm doctor-card h-100">
                                @if ($dokter->foto_dokter)
                                    <img src="{{ asset('storage/' . $dokter->foto_dokter) }}"
                                         class="card-img-top"
                                         alt="Dokter">
                                @else
                                    <img src="{{ asset('images/foto-profile.png') }}"
                                         class="card-img-top"
                                         alt="Dokter">
                                @endif
                                <div class="card-body text-center">
                                    <h5 class="card-title fw-bold mb-1">{{ $dokter->nm_dokter ?? '-' }}</h5>
                                    <p class="text-muted mb-2">{{ $dokter->nama_spesialis ?? '-' }}</p>
                                    <p class="text-muted mb-2">{!! \Illuminate\Support\Str::limit($dokter->tentang ? $dokter->tentang : '-', 100) !!}</p>
                                    <a href="{{ route('landing.profildokter', $dokter->slug ?? '') }}"
                                       class="btn btn-main btn-sm mt-2">Lihat Profil</a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

                <!-- Pagination & Navigation -->
                <div class="swiper-pagination mt-4"></div>
                <div class="swiper-button-next text-main"></div>
                <div class="swiper-button-prev text-main"></div>
            </div>
        </div>
    </section>
    <!-- ======= End Dokter & Tenaga Medis ======= -->

    <!-- ========================== -->
    <!--  LAYANAN IGD 24 JAM SECTION -->
    <!-- ========================== -->
    <section id="layanan-igd"
             class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3 text-blue">LAYANAN <span class="text-orange">UGD 24 JAM</span></h2>
                <p class="text-muted">
                    Unit Gawat Darurat (UGD) kami siap siaga 24 jam melayani pasien dengan kondisi darurat yang membutuhkan penanganan cepat dan tepat.
                </p>
            </div>

            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="{{ asset('images/about.jpg') }}"
                         alt="Layanan IGD"
                         class="img-fluid rounded-4 shadow-sm">
                </div>

                <div class="col-md-6 mt-4 mt-md-0">
                    <h5 class="fw-bold text-orange mb-3">Layanan Unit Gawat Darurat (UGD)</h5>
                    <ul class="list-unstyled text-muted mb-4">
                        <li>• Mata merah tiba-tiba</li>
                        <li>• Mata kemasukan benda asing</li>
                        <li>• Penurunan penglihatan tiba-tiba</li>
                        <li>• Mata nyeri tiba-tiba</li>
                        <li>• Trauma zat kimia pada mata</li>
                        <li>• Trauma tumpul dan tajam pada mata</li>
                        <li>• Ancaman jiwa</li>
                        <li>• Gangguan pernapasan</li>
                        <li>• Penurunan kesadaran</li>
                        <li>• Nyeri hebat</li>
                        <li>• Gangguan hemodinamik</li>
                        <li>• Memerlukan tindakan segera</li>
                        <li>• Perdarahan</li>
                        <li>• Cedera parah</li>
                        <li>• Keracunan</li>
                    </ul>

                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-orange text-white px-3 py-2">Rawat Inap</span>
                        <span class="badge bg-primary text-white px-3 py-2">Laboratorium</span>
                        <span class="badge bg-success text-white px-3 py-2">Optik</span>
                        <span class="badge bg-secondary text-white px-3 py-2">Farmasi</span>
                        <span class="badge bg-danger text-white px-3 py-2">Tindakan Medis</span>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section id="layanan-unggulan"
             class="py-5 bg-light">
        <div class="container text-center">
            <!-- Title -->
            <h2 class="fw-bold mb-3 text-blue">LAYANAN <span class="text-orange">UNGGULAN</span></h2>
            <p class="text-muted mb-5">Kami menghadirkan layanan terbaik dengan teknologi modern dan tenaga medis berpengalaman.</p>

            <!-- Cards -->
            <div class="row g-4">
                <div class="col-md-4 col-sm-6">
                    <div class="service-card h-100">
                        <div class="icon-wrapper mb-3">
                            <i class="bi bi-eye"></i>
                        </div>
                        <h5 class="fw-bold text-orange">Operasi Katarak Phacoemulsifikasi</h5>
                        <p class="text-muted">Prosedur tanpa jahitan dengan pemulihan cepat dan hasil optimal bagi penglihatan Anda.</p>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6">
                    <div class="service-card h-100">
                        <div class="icon-wrapper mb-3">
                            <i class="bi bi-heart-pulse"></i>
                        </div>
                        <h5 class="fw-bold text-orange">Operasi Mata Trabekulektomi</h5>
                        <p class="text-muted">Menurunkan tekanan bola mata pada penderita glaukoma dengan teknik bedah mikro.</p>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6">
                    <div class="service-card h-100">
                        <div class="icon-wrapper mb-3">
                            <i class="bi bi-layers"></i>
                        </div>
                        <h5 class="fw-bold text-orange">Vitreo-Retina</h5>
                        <p class="text-muted">Layanan untuk gangguan retina dan vitreous dengan peralatan diagnostik terkini.</p>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6">
                    <div class="service-card h-100">
                        <div class="icon-wrapper mb-3">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <h5 class="fw-bold text-orange">Pediatrik Oftalmologi & Strabismus</h5>
                        <p class="text-muted">Menangani gangguan mata pada anak dan penanganan mata juling dengan profesional.</p>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6">
                    <div class="service-card h-100">
                        <div class="icon-wrapper mb-3">
                            <i class="bi bi-person-hearts"></i>
                        </div>
                        <h5 class="fw-bold text-orange">Rekonstruksi & Okuloplasti</h5>
                        <p class="text-muted">Perbaikan struktur kelopak mata, saluran air mata, dan estetika sekitar mata.</p>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6">
                    <div class="service-card h-100">
                        <div class="icon-wrapper mb-3">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5 class="fw-bold text-orange">Infeksi & Imunologi Mata</h5>
                        <p class="text-muted">Diagnosis dan terapi penyakit mata akibat infeksi maupun gangguan sistem imun.</p>
                    </div>
                </div>
            </div>

            <!-- Button -->
            <a href="#semua-layanan"
               class="btn btn-main mt-5 px-4 py-2">Lihat Semua Layanan</a>
        </div>
    </section>

    <section id="fasilitas"
             class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-uppercase text-primary">Fasilitas <span class="text-orange">Rumah Sakit</span></h2>
                <p class="text-muted">Kami menyediakan fasilitas modern untuk mendukung pelayanan terbaik bagi pasien.</p>
            </div>

            <!-- Carousel -->
            <div id="fasilitasCarousel"
                 class="carousel slide carousel-fade"
                 data-bs-ride="carousel">
                <div class="carousel-inner">

                    <!-- Item 1 -->
                    <div class="carousel-item active">
                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                            <img src="{{ asset('images/fasilitas/ugd.jpg') }}"
                                 class="d-block w-100 fasilitas-img"
                                 alt="Ruang Operasi">
                            <div class="card-img-overlay bg-dark bg-opacity-50 d-flex flex-column justify-content-end p-4">
                                <h4 class="text-white fw-bold">Ruang Unit Gawat Darurat (UGD) 24 Jam</h4>
                                <p class="text-light">
                                    Layanan darurat mata 24 jam dengan tenaga medis berpengalaman dan fasilitas lengkap untuk penanganan cepat.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Item 2 -->
                    <div class="carousel-item">
                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                            <img src="{{ asset('images/fasilitas/rawat-jalan.jpg') }}"
                                 class="d-block w-100 fasilitas-img"
                                 alt="Ruang Tunggu Nyaman">
                            <div class="card-img-overlay bg-dark bg-opacity-50 d-flex flex-column justify-content-end p-4">
                                <h4 class="text-white fw-bold">Ruang Rawat Jalan</h4>
                                <p class="text-light">
                                    Dilengkapi fasilitas modern dengan suasana nyaman untuk mendukung pemeriksaan dan perawatan pasien secara optimal.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Item 3 -->
                    <div class="carousel-item">
                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                            <img src="{{ asset('images/fasilitas/rawat-inap.jpg') }}"
                                 class="d-block w-100 fasilitas-img"
                                 alt="Ruang Tunggu Nyaman">
                            <div class="card-img-overlay bg-dark bg-opacity-50 d-flex flex-column justify-content-end p-4">
                                <h4 class="text-white fw-bold">Ruang Rawat Inap</h4>
                                <p class="text-light">
                                    Menyediakan fasilitas lengkap dan lingkungan yang nyaman untuk mendukung proses pemulihan pasien dengan optimal.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Item 4 (optional video) -->
                    <div class="carousel-item">
                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden position-relative">
                            <video class="d-block w-100 fasilitas-img"
                                   autoplay
                                   muted
                                   loop>
                                <source src="https://cdn.pixabay.com/vimeo/472066533/eye-63069.mp4?width=640&hash=a1ce3d3c7c64"
                                        type="video/mp4">
                            </video>
                            <div class="card-img-overlay bg-dark bg-opacity-50 d-flex flex-column justify-content-end p-4">
                                <h4 class="text-white fw-bold">Virtual Tour Rumah Sakit</h4>
                                <p class="text-light">Nikmati pengalaman berkeliling rumah sakit secara virtual.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Controls -->
                <button class="carousel-control-prev"
                        type="button"
                        data-bs-target="#fasilitasCarousel"
                        data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-dark rounded-circle p-2"
                          aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next"
                        type="button"
                        data-bs-target="#fasilitasCarousel"
                        data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-dark rounded-circle p-2"
                          aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </section>

    <!-- ========================== -->
    <!-- 7. BERITA & INFORMASI SECTION -->
    <!-- ========================== -->
    <section id="berita"
             class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-uppercase text-primary">Berita & <span class="text-orange">Informasi</span></h2>
                <p class="text-muted">Update seputar kegiatan, layanan, dan edukasi kesehatan mata dari RSKM Regina Eye Center.</p>
            </div>

            <div class="row g-4">
                @forelse ($beritas as $berita)
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 hover-card">
                            <!-- Gambar Berita -->
                            <div class="position-relative">
                                <img src="{{ $berita->gambar_berita ? asset('storage/' . $berita->gambar_berita) : asset('images/foto-profile.png') }}"
                                     class="card-img-top rounded-top-4"
                                     alt="{{ $berita->judul ?? 'Gambar Berita' }}"
                                     style="height: 240px; object-fit: cover;">

                                <span class="badge bg-orange position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill shadow-sm">
                                    {{ $berita->nm_kategori ?? 'Tanpa Kategori' }}
                                </span>
                            </div>

                            <!-- Isi Card -->
                            <div class="card-body p-4 d-flex flex-column">
                                <h5 class="fw-bold mb-2 text-dark">
                                    {{ Str::limit($berita->judul, 70) }}
                                </h5>

                                <p class="text-muted small mb-3">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $berita->tgl_berita ? \Carbon\Carbon::parse($berita->tgl_berita)->translatedFormat('d F Y') : '-' }}
                                </p>

                                <p class="text-muted flex-grow-1">
                                    {{ Str::limit(strip_tags($berita->ringkasan), 120, '...') }}
                                </p>

                                <a href="{{ route('landing.showberita', $berita->slug ?? '-') }}"
                                   class="btn btn-orange rounded-pill mt-3 align-self-start">
                                    Baca Selengkapnya
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <h5 class="text-muted">Belum ada berita yang tersedia.</h5>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('landing.berita') }}"
                   class="btn btn-outline-orange rounded-pill px-4 py-2">
                    <i class="bi bi-newspaper me-2"></i> Lihat Semua Berita
                </a>
            </div>
        </div>
    </section>


    @include('landing.main.kerjasama')

    @include('landing.main.kontak-lokasi')

@endsection
