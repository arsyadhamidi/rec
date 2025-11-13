@extends('landing.layout.master')
@section('title', 'Profil Dokter | RSKM Regina Eye Center')
@section('menuDokterMedis', 'active')
@section('menuDokterSpesialis', 'active')

@section('content')
    <!-- HERO SECTION -->
    <section class="hero-section text-center text-white d-flex align-items-center"
             style="background: linear-gradient(to right, #f58220, #f4a261); height: 50vh;">
        <div class="container">
            <h1 class="fw-bold display-5">Profil Dokter</h1>
            <p class="lead">Rumah Sakit Khusus Mata Regina Eye Center</p>
        </div>
    </section>
    <section id="profil-dokter"
             class="py-5 my-5"
             style="background-color: var(--light-bg);">
        <div class="container my-5">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

                <div class="row g-0">
                    <!-- Foto Dokter -->
                    <div class="col-md-4 bg-white text-center d-flex align-items-center justify-content-center p-4">
                        @if ($dokters->foto_dokter)
                            <img src="{{ asset('storage/' . $dokters->foto_dokter) }}"
                                 alt="{{ $dokters->nm_dokter ?? '-' }}"
                                 class="rounded-circle shadow-sm img-fluid"
                                 style="width: 240px; height: 240px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/foto-profile.png') }}"
                                 alt="{{ $dokters->nm_dokter ?? '-' }}"
                                 class="rounded-circle shadow-sm img-fluid"
                                 style="width: 240px; height: 240px; object-fit: cover;">
                        @endif
                    </div>

                    <!-- Info Dokter -->
                    <div class="col-md-8 bg-white">
                        <div class="card-body p-4">
                            <h3 class="fw-bold mb-1 text-dark">{{ $dokters->nm_dokter }}</h3>
                            <h6 class="text-main mb-3 fw-semibold">
                                {{ strtoupper($dokters->nama_spesialis ?? 'Spesialis Tidak Tersedia') }}
                            </h6>

                            <ul class="list-unstyled small text-muted mb-3">
                                <li><i class="bi bi-geo-alt text-main me-2"></i>
                                    <strong>Tempat, Tanggal Lahir:</strong>
                                    {{ $dokters->tmp_lahir }},
                                    {{ \Carbon\Carbon::parse($dokters->tgl_lahir)->translatedFormat('d M Y') }}
                                </li>
                                <li><i class="bi bi-gender-ambiguous text-main me-2"></i>
                                    <strong>Jenis Kelamin:</strong>
                                    {{ $dokters->jk == '1' ? 'Laki-laki' : 'Perempuan' }}
                                </li>
                                {{--  <li><i class="bi bi-house-door text-main me-2"></i>
                                    <strong>Alamat:</strong> {{ $dokters->alamat }}
                                </li>  --}}
                            </ul>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h6 class="fw-semibold text-main">Pendidikan</h6>
                                    <p class="text-dark mb-0">{{ $dokters->pendidikan }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h6 class="fw-semibold text-main">Keahlian</h6>
                                    <p class="text-dark mb-0">{{ $dokters->keahlian }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tentang Dokter -->
                <div class="card-body bg-light p-4">
                    <h5 class="fw-bold text-main mb-3">Tentang Dokter</h5>
                    <p class="text-muted"
                       style="text-align: justify;">
                        {{ $dokters->tentang }}
                    </p>

                    @if ($cuti)
                        @php

                            \Carbon\Carbon::setLocale('id');

                            $today = \Carbon\Carbon::today();
                            $tglMulai = \Carbon\Carbon::parse($cuti->tgl_mulai);
                            $tglSelesai = \Carbon\Carbon::parse($cuti->tgl_selesai);
                        @endphp

                        {{-- Dokter AKAN CUTI --}}
                        @if ($today->lt($tglMulai))
                            <div class="alert alert-info mt-4 shadow-sm border-0 rounded-3">
                                <i class="bi bi-calendar-event-fill me-2"></i>
                                <strong>Informasi:</strong> Dokter akan menjalani <span class="fw-bold text-primary">cuti</span>
                                <br>
                                <span class="ms-4">
                                    mulai tanggal <strong>{{ $tglMulai->translatedFormat('l, d F Y') }}</strong>
                                    sampai <strong>{{ $tglSelesai->translatedFormat('l, d F Y') }}</strong>.
                                </span>
                            </div>

                            {{-- Dokter SEDANG CUTI --}}
                        @elseif ($today->between($tglMulai, $tglSelesai))
                            <div class="alert alert-warning mt-4 shadow-sm border-0 rounded-3">
                                <i class="bi bi-calendar-x-fill me-2"></i>
                                <strong>Informasi:</strong> Dokter sedang <span class="fw-bold text-danger">cuti</span>
                                <br>
                                <span class="ms-4">
                                    dari tanggal <strong>{{ $tglMulai->translatedFormat('l, d F Y') }}</strong>
                                    sampai <strong>{{ $tglSelesai->translatedFormat('l, d F Y') }}</strong>.
                                </span>
                            </div>

                            {{-- Dokter SUDAH AKTIF --}}
                        @elseif ($today->gt($tglSelesai))
                            <div class="alert alert-success mt-4 shadow-sm border-0 rounded-3">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <strong>Informasi:</strong> Dokter telah kembali praktek
                                <br>
                                <span class="ms-4">
                                    sejak <strong>{{ $tglSelesai->addDay()->translatedFormat('l, d F Y') }}</strong>.
                                </span>
                            </div>
                        @endif
                    @endif



                    <!-- Jadwal Praktik -->
                    @if ($jadwals && count($jadwals) > 0)
                        <div class="mt-4">
                            <h6 class="fw-bold text-main mb-3">Jadwal Praktik</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle"
                                       style="font-size: 0.95rem;">
                                    <thead class="table-light">
                                        <tr class="text-center">
                                            <th>Hari</th>
                                            <th>Jam Mulai</th>
                                            <th>Jam Selesai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jadwals as $jadwal)
                                            <tr class="text-center">
                                                <td>
                                                    @switch($jadwal->hari_dokter)
                                                        @case('1')
                                                            Senin
                                                        @break

                                                        @case('2')
                                                            Selasa
                                                        @break

                                                        @case('3')
                                                            Rabu
                                                        @break

                                                        @case('4')
                                                            Kamis
                                                        @break

                                                        @case('5')
                                                            Jumat
                                                        @break

                                                        @case('6')
                                                            Sabtu
                                                        @break

                                                        @case('7')
                                                            Minggu
                                                        @break

                                                        @default
                                                            -
                                                    @endswitch
                                                </td>
                                                <td>
                                                    {{ $jadwal->jam_mulai ? \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') : '-' }}
                                                </td>
                                                <td>
                                                    {{ $jadwal->jam_selesai ? \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') : '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @include('landing.main.layanan-unggulan')
    @include('landing.main.kerjasama')
    @include('landing.main.kontak-lokasi')

@endsection
