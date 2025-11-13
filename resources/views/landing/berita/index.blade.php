@extends('landing.layout.master')
@section('title', 'Berita & Informasi | RSKM Regina Eye Center')
@section('menuLandingBerita', 'active')

@section('content')
    <!-- HERO SECTION -->
    <section class="hero-section text-center text-white d-flex align-items-center"
             style="background: linear-gradient(to right, #f58220, #f4a261); height: 40vh;">
        <div class="container">
            <h1 class="fw-bold display-5">Berita & Informasi</h1>
            <p class="lead">Kabar dan informasi terkini seputar RSKM Regina Eye Center</p>
        </div>
    </section>

    <!-- BERITA SECTION -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                @forelse ($beritas as $berita)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 hover-card">
                            <!-- Gambar Berita -->
                            <div class="position-relative">
                                @if ($berita->gambar_berita)
                                    <img src="{{ asset('storage/' . $berita->gambar_berita) }}"
                                         class="card-img-top rounded-top-4"
                                         alt="{{ $berita->judul }}">
                                @else
                                    <img src="{{ asset('images/foto-profile.png') }}"
                                         class="card-img-top rounded-top-4"
                                         alt="Berita RSKM Regina">
                                @endif
                                <span class="badge bg-orange position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill">
                                    {{ $berita->nm_kategori ?? 'Berita Umum' }}
                                </span>
                            </div>

                            <!-- Isi Ringkas -->
                            <div class="card-body p-4 d-flex flex-column">
                                <h5 class="fw-bold text-dark mb-2">{{ $berita->judul }}</h5>
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-calendar3"></i>
                                    {{ \Carbon\Carbon::parse($berita->tgl_berita)->translatedFormat('d F Y') }}
                                </p>
                                <p class="text-muted flex-grow-1">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($berita->ringkasan), 120) }}
                                </p>
                                <a href="{{ route('landing.showberita', $berita->slug ?? '-') }}"
                                   class="btn btn-orange rounded-pill mt-3 align-self-start">
                                    Baca Selengkapnya <i class="bi bi-arrow-right-short"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <h5 class="text-muted">Belum ada berita yang tersedia saat ini.</h5>
                    </div>
                @endforelse
            </div>

            <!-- PAGINATION -->
            <!-- PAGINATION -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-5 gap-3">
                <div class="text-muted small">
                    Showing {{ $beritas->firstItem() }} to {{ $beritas->lastItem() }} of {{ $beritas->total() }} results
                </div>
                <div>
                    {{ $beritas->links('pagination::bootstrap-5') }}
                </div>
            </div>

        </div>
    </section>
@endsection
