@extends('landing.layout.master')
@section('title', 'Tenaga Medis & Ahli | RSKM Regina Eye Center')
@section('menuDokterMedis', 'active')
@section('menuTenagaMedis', 'active')

@section('content')

    <!-- HERO SECTION -->
    <section class="hero-section text-center text-white d-flex align-items-center"
             style="background: linear-gradient(135deg, #f58220, #f4a261); height: 45vh;">
        <div class="container">
            <h1 class="fw-bold display-5">Tenaga Medis & Ahli</h1>
            <p class="lead mb-0">Kami bangga memiliki tenaga profesional terbaik di bidang kesehatan mata</p>
        </div>
    </section>

    <!-- TENAGA MEDIS SECTION -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-uppercase text-dark">
                    Tenaga <span class="text-warning">Medis & Ahli</span>
                </h2>
                <div class="mx-auto"
                     style="width: 80px; height: 3px; background: #f58220;"></div>
                <p class="text-muted mt-3">
                    Tim kami terdiri dari dokter spesialis, perawat, apoteker, dan staf ahli yang berdedikasi penuh
                    memberikan pelayanan terbaik bagi pasien.
                </p>
            </div>

            <div class="row g-4">
                <!-- KOLOM KIRI -->
                <div class="col-md-6">
                    <div class="vstack gap-3">
                        @php
                            $leftData = [
                                ['20 Dokter Spesialis Mata', '20 Dokter Spesialis Mata yang ahli dan profesional di bidangnya', 'bi-eye'],
                                ['2 Sp. Penyakit Dalam & 1 Sp. Anestesi', 'Dokter spesialis yang mendukung pelayanan medis secara menyeluruh', 'bi-heart-pulse'],
                                ['2 Apoteker', 'Apoteker profesional yang memastikan keamanan dan efektivitas obat', 'bi-capsule-pill'],
                                ['22 Perawat Rawat Jalan', 'Perawat rawat jalan dengan dedikasi tinggi', 'bi-person-vcard'],
                                ['4 Perawat Rawat Inap', 'Perawat profesional yang berfokus pada perawatan intensif', 'bi-hospital'],
                                ['9 Perekam Medis & 3 Admisi', 'Petugas administrasi medis yang teliti dan terlatih', 'bi-journal-medical'],
                                ['1 Perawat IPCN', 'Perawat pengendali infeksi yang memastikan standar keamanan pasien', 'bi-shield-plus'],
                            ];
                        @endphp

                        @foreach ($leftData as $item)
                            <div class="card border-0 shadow-sm rounded-4 hover-card">
                                <div class="card-body d-flex align-items-center">
                                    <div class="icon-wrapper bg-warning bg-opacity-25 text-warning rounded-circle me-3">
                                        <i class="bi {{ $item[2] }} fs-3"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold text-dark mb-1">{{ $item[0] }}</h5>
                                        <p class="text-muted small mb-0">{{ $item[1] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- KOLOM KANAN -->
                <div class="col-md-6">
                    <div class="vstack gap-3">
                        @php
                            $rightData = [
                                ['1 Direktur', 'Pemimpin rumah sakit dengan visi pelayanan kesehatan unggul', 'bi-person-badge'],
                                ['2 Dokter Verifikator', 'Dokter yang memastikan mutu pelayanan dan klaim kesehatan', 'bi-file-earmark-medical'],
                                ['6 Dokter Umum', 'Dokter umum yang siap melayani pemeriksaan dasar pasien', 'bi-person-vcard-fill'],
                                ['4 Asisten Apoteker', 'Mendukung apoteker dalam penyiapan dan distribusi obat', 'bi-prescription'],
                                ['6 Perawat Kamar Bedah', 'Tenaga ahli dalam mendukung operasi bedah mata', 'bi-scissors'],
                                ['2 Perawat UGD', 'Perawat tanggap darurat untuk penanganan cepat pasien', 'bi-lightning-charge'],
                                ['12 Staff Manajemen & 18 Staff Dukungan', 'Tim manajemen profesional yang menjaga kelancaran operasional', 'bi-people'],
                                ['2 Ahli Refraksi Optisi', 'Ahli refraksi optisi yang berpengalaman dalam pemeriksaan penglihatan', 'bi-eyeglasses'],
                            ];
                        @endphp

                        @foreach ($rightData as $item)
                            <div class="card border-0 shadow-sm rounded-4 hover-card">
                                <div class="card-body d-flex align-items-center">
                                    <div class="icon-wrapper bg-warning bg-opacity-25 text-warning rounded-circle me-3">
                                        <i class="bi {{ $item[2] }} fs-3"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold text-dark mb-1">{{ $item[0] }}</h5>
                                        <p class="text-muted small mb-0">{{ $item[1] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
