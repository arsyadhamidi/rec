@extends('landing.layout.master')
@section('title', 'Dokter Spesialis | RSKM Regina Eye Center')
@section('menuDokterMedis', 'active')
@section('menuDokterSpesialis', 'active')

@section('content')
    !-- HERO SECTION -->
    <section class="hero-section text-center text-white d-flex align-items-center"
             style="background: linear-gradient(to right, #f58220, #f4a261); height: 45vh;">
        <div class="container">
            <h1 class="fw-bold display-5">Dokter Spesialis</h1>
            <p class="lead">Temukan dokter terbaik untuk kesehatan mata Anda</p>
        </div>
    </section>

    <!-- DOCTOR LIST SECTION -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4">

                <!-- SIDEBAR FILTER (KIRI) -->
                <div class="col-lg-3">
                    <div class="card shadow-sm border-0 rounded-4 p-4 sticky-top"
                         style="top: 100px;">
                        <h5 class="fw-bold mb-3">🔍 Pencarian Dokter</h5>
                        <div class="mb-3">
                            <input type="text"
                                   id="searchDokter"
                                   class="form-control rounded-pill"
                                   placeholder="Cari nama dokter...">
                        </div>

                        <h6 class="fw-bold mt-4 mb-2">🩺 Spesialisasi</h6>
                        <div class="mb-3">
                            <select id="filterSpesialis"
                                    class="form-select rounded-pill">
                                <option value="">Semua Spesialisasi</option>
                                @foreach ($spesialis as $data)
                                    <option value="{{ $data->nama_spesialis ?? '-' }}">{{ $data->nama_spesialis ?? '-' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="text-center">
                            <button id="resetFilter"
                                    class="btn btn-warning rounded-pill px-4">
                                <i class="bi bi-arrow-repeat"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>

                <!-- GRID DOKTER (KANAN) -->
                <div class="col-lg-9">
                    <div id="doctorList"
                         class="row g-4">
                        @foreach ($dokters as $dokter)
                            <div class="col-md-4 dokter-item"
                                 data-nama="{{ strtolower($dokter->nm_dokter) }}"
                                 data-spesialis="{{ strtolower($dokter->nama_spesialis) }}">
                                <div class="card border-0 shadow-sm rounded-4 h-100 hover-card">
                                    <div class="position-relative">
                                        <img src="{{ $dokter->foto_dokter ? asset('storage/' . $dokter->foto_dokter) : asset('images/foto-profile.png') }}"
                                             class="card-img-top rounded-top-4"
                                             alt="{{ $dokter->nm_dokter }}">
                                        <div class="position-absolute top-0 end-0 bg-warning text-white px-3 py-1 rounded-start-pill small">
                                            {{ $dokter->nama_spesialis }}
                                        </div>
                                    </div>
                                    <div class="card-body text-center">
                                        <h5 class="fw-bold mb-1">{{ $dokter->nm_dokter }}</h5>
                                        <p class="text-muted mb-2">{{ $dokter->keahlian ?? '-' }}</p>
                                        <a href="{{ route('landing.profildokter', $dokter->slug ?? '-') }}"
                                           class="btn btn-outline-warning btn-sm rounded-pill">
                                            <i class="bi bi-eye"></i> Lihat Profil
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Jika tidak ada hasil -->
                    <div id="noResult"
                         class="text-center text-muted mt-5 d-none">
                        <i class="bi bi-emoji-frown fs-1"></i>
                        <p class="mt-2">Tidak ada dokter ditemukan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('custom-script')
    <!-- SCRIPT PENCARIAN & FILTER -->
    <script>
        $(document).ready(function() {
            function filterDokter() {
                let search = $('#searchDokter').val().toLowerCase();
                let spesialis = $('#filterSpesialis').val().toLowerCase();
                let count = 0;

                $('.dokter-item').each(function() {
                    let nama = $(this).data('nama');
                    let spesialisasi = $(this).data('spesialis');
                    let matchNama = nama.includes(search);
                    let matchSpesialis = spesialis === '' || spesialisasi === spesialis;

                    if (matchNama && matchSpesialis) {
                        $(this).show();
                        count++;
                    } else {
                        $(this).hide();
                    }
                });

                if (count === 0) {
                    $('#noResult').removeClass('d-none');
                } else {
                    $('#noResult').addClass('d-none');
                }
            }

            $('#searchDokter').on('keyup', filterDokter);
            $('#filterSpesialis').on('change', filterDokter);
            $('#resetFilter').on('click', function() {
                $('#searchDokter').val('');
                $('#filterSpesialis').val('');
                filterDokter();
            });
        });
    </script>
@endpush
