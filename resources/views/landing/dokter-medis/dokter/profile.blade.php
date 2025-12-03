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
             class="py-5"
             style="background-color: #f7f9fc;">
        <div class="container">

            <!-- CARD HEADER PROFIL -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4"
                 style="background: #ffffff;">

                <div class="row align-items-center">

                    <!-- Foto Dokter -->
                    <div class="col-md-3 text-center mb-3">
                        <div class="rounded-circle mx-auto shadow"
                             style="
                            width: 160px;
                            height: 160px;
                            overflow: hidden;
                            border: 4px solid #fff;
                         ">
                            <img src="{{ $dokters->foto_dokter ? asset('storage/' . $dokters->foto_dokter) : asset('images/foto-profile.png') }}"
                                 class="img-fluid"
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    </div>

                    <!-- Info Dokter -->
                    <div class="col-md-9">
                        <h3 class="fw-bold mb-1 text-dark">{{ $dokters->nm_dokter }}</h3>

                        <p class="text-uppercase fw-semibold mb-2"
                           style="color: #f58220; letter-spacing: 0.5px;">
                            {{ $dokters->nama_spesialis ?? 'SPESIALIS' }}
                        </p>

                        <div class="mt-3">
                            <p class="fw-bold text-dark mb-1">Kompetensi:</p>
                            <p class="text-muted">{!! $dokters->keahlian ?? '-' !!}</p>

                            <p class="fw-bold text-dark mb-1">Pendidikan:</p>
                            <p class="text-muted">{!! $dokters->pendidikan ?? '-' !!}</p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- TAB NAVIGATION -->
            <ul class="nav nav-tabs mb-4"
                id="dokterTab">
                @php
                    $tabs = [
                        'tentang' => 'Tentang',
                        'keahlian' => 'Kompetensi',
                        'pendidikan' => 'Pendidikan',
                        'fellowship' => 'Fellowship',
                        'pengalaman' => 'Pengalaman',
                        'organisasi' => 'Organisasi',
                        'jadwal' => 'Jadwal',
                    ];
                @endphp

                @foreach ($tabs as $id => $label)
                    <li class="nav-item">
                        <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                data-bs-toggle="tab"
                                data-bs-target="#{{ $id }}"
                                style="
                                font-weight: 600;
                                color: #4a6bf2;
                                border: none;
                                padding: 10px 18px;
                            ">
                            {{ $label }}
                        </button>
                    </li>
                @endforeach
            </ul>

            <!-- TAB CONTENT -->
            <div class="tab-content">

                <!-- Tentang -->
                <div class="tab-pane fade show active"
                     id="tentang">
                    <div class="card shadow-sm border-0 rounded-4 p-4 mb-4">
                        <h5 class="fw-bold text-main mb-3">Tentang Dokter</h5>
                        <p class="text-muted"
                           style="text-align: justify;">
                            {!! $dokters->tentang !!}
                        </p>

                        {{-- Informasi Cuti --}}
                        @if ($cuti)
                            @php
                                \Carbon\Carbon::setLocale('id');
                                $today = \Carbon\Carbon::today();
                                $mulai = \Carbon\Carbon::parse($cuti->tgl_mulai);
                                $selesai = \Carbon\Carbon::parse($cuti->tgl_selesai);
                            @endphp

                            @if ($today->lt($mulai))
                                <div class="alert alert-info mt-4 rounded-3 shadow-sm">
                                    <strong>Informasi:</strong> Dokter akan cuti
                                    dari <b>{{ $mulai->translatedFormat('l, d F Y') }}</b>
                                    sampai <b>{{ $selesai->translatedFormat('l, d F Y') }}</b>.
                                </div>
                            @elseif ($today->between($mulai, $selesai))
                                <div class="alert alert-warning mt-4 rounded-3 shadow-sm">
                                    <strong>Informasi:</strong> Dokter sedang cuti
                                    sampai <b>{{ $selesai->translatedFormat('l, d F Y') }}</b>.
                                </div>
                            @else
                                <div class="alert alert-success mt-4 rounded-3 shadow-sm">
                                    <strong>Informasi:</strong> Dokter aktif kembali
                                    sejak <b>{{ $selesai->addDay()->translatedFormat('l, d F Y') }}</b>.
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Loop Konten Tab -->
                @foreach ($tabs as $id => $label)
                    @if ($id !== 'tentang' && $id !== 'jadwal')
                        <div class="tab-pane fade"
                             id="{{ $id }}">
                            <div class="card shadow-sm border-0 rounded-4 p-4 mb-4">
                                <h5 class="fw-bold text-main mb-3">{{ $label }}</h5>
                                <p class="text-muted">{!! $dokters->$id ?? '-' !!}</p>
                            </div>
                        </div>
                    @endif
                @endforeach

                <!-- Jadwal -->
                <div class="tab-pane fade"
                     id="jadwal">
                    <div class="card shadow-sm border-0 rounded-4 p-4 mb-4">
                        <h5 class="fw-bold text-main mb-3">Jadwal Praktik</h5>

                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead style="background: #fff7ef;">
                                    <tr>
                                        <th>Hari</th>
                                        <th>Jam Mulai</th>
                                        <th>Jam Selesai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($jadwals as $jadwal)
                                        <tr>
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
                                            <td>{{ $jadwal->jam_mulai ? \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') : '-' }}</td>
                                            <td>{{ $jadwal->jam_selesai ? \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') : '-' }}</td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3"
                                                    class="text-muted py-3">Tidak ada jadwal tersedia.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </section>



        @include('landing.main.layanan-unggulan')
        @include('landing.main.kerjasama')
        @include('landing.main.kontak-lokasi')

    @endsection
