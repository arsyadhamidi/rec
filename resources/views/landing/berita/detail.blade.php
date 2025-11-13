@extends('landing.layout.master')
@section('title', 'Detail Berita | RSKM Regina Eye Center')
@section('menuLandingBerita', 'active')

@section('content')
    <!-- HERO SECTION -->
    <section class="hero-section text-center text-white d-flex align-items-center"
             style="background: linear-gradient(to right, #f58220, #f4a261); height: 50vh;">
        <div class="container">
            <h1 class="fw-bold display-5">Detail Berita / Informasi</h1>
            <p class="lead">Rumah Sakit Khusus Mata Regina Eye Center</p>
        </div>
    </section>

    <!-- DETAIL BERITA -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                        <!-- Gambar Utama -->
                        @if ($details->gambar_berita)
                            <img src="{{ asset('storage/' . $details->gambar_berita) }}"
                                 class="img-fluid rounded-4 mb-4 w-100"
                                 alt="{{ $details->judul ?? '-' }}">
                        @else
                            <img src="{{ asset('images/foto-profile.png') }}"
                                 class="img-fluid rounded-4 mb-4 w-100"
                                 alt="Gambar Berita">
                        @endif

                        <!-- Kategori dan Tanggal -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-orange px-3 py-2 rounded-pill">
                                {{ $details->nm_kategori ?? 'Berita Umum' }}
                            </span>
                            <small class="text-muted">
                                <i class="bi bi-calendar3"></i>
                                {{ $details->tgl_berita ? \Carbon\Carbon::parse($details->tgl_berita)->translatedFormat('d F Y') : '-' }}
                            </small>
                        </div>

                        <!-- Judul -->
                        <h2 class="fw-bold text-dark mb-3">{{ $details->judul ?? '-' }}</h2>

                        <!-- Ringkasan -->
                        @if ($details->ringkasan)
                            <p class="text-muted fst-italic">{{ $details->ringkasan }}</p>
                        @endif

                        <hr class="my-4">

                        <!-- Isi Berita -->
                        <div class="berita-content">
                            {!! $details->isi_berita ?? '<p>Konten berita belum tersedia.</p>' !!}
                        </div>

                        <!-- Tombol Kembali -->
                        <div class="text-center mt-5">
                            <a href="{{ route('landing.index') }}"
                               class="btn btn-outline-orange rounded-pill px-4 py-2">
                                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Berita
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                        <h5 class="fw-bold text-dark mb-4">
                            <i class="bi bi-newspaper"></i> Berita Lainnya
                        </h5>

                        @if ($beritas->count() > 0)
                            @foreach ($beritas as $berita)
                                @if ($berita->id !== $details->id)
                                    {{-- Jangan tampilkan berita yang sedang dibuka --}}
                                    <div class="d-flex mb-3 border-bottom pb-3 align-items-center">
                                        <!-- Thumbnail kecil -->
                                        <div class="me-3 flex-shrink-0">
                                            @if ($berita->gambar_berita)
                                                <img src="{{ asset('storage/' . $berita->gambar_berita) }}"
                                                     alt="{{ $berita->judul }}"
                                                     class="rounded-3"
                                                     style="width: 70px; height: 70px; object-fit: cover;">
                                            @else
                                                <img src="{{ asset('images/foto-profile.png') }}"
                                                     alt="Berita"
                                                     class="rounded-3"
                                                     style="width: 70px; height: 70px; object-fit: cover;">
                                            @endif
                                        </div>

                                        <!-- Informasi berita -->
                                        <div>
                                            <a href="{{ route('landing.showberita', $berita->slug ?? '') }}"
                                               class="text-dark fw-semibold d-block"
                                               style="text-decoration: none;">
                                                {{ \Illuminate\Support\Str::limit($berita->judul, 45) }}
                                            </a>
                                            <small class="text-muted d-block mt-1">
                                                <i class="bi bi-calendar3"></i>
                                                {{ \Carbon\Carbon::parse($berita->tgl_berita)->translatedFormat('d M Y') }}
                                            </small>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <p class="text-muted">Belum ada berita lainnya.</p>
                        @endif
                    </div>
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
                                    <p class="text-muted mb-2">{{ $dokter->keahlian ?? '-' }}</p>
                                    <a href="{{ route('landing.profildokter', $dokter->id ?? '') }}"
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

    @include('landing.main.layanan-unggulan')
    @include('landing.main.kerjasama')
    @include('landing.main.kontak-lokasi')
@endsection
