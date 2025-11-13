@extends('landing.layout.master')
@section('title', 'Layanan Umum | RSKM Regina Eye Center')
@section('menuLayanan', 'active')
@section('menuLayananUmum', 'active')

@section('content')

    <!-- HERO SECTION -->
    <section class="hero-section text-center text-white d-flex align-items-center"
             style="background: linear-gradient(to right, #f58220, #f4a261); height: 45vh;">
        <div class="container">
            <h1 class="fw-bold display-5">Layanan Umum</h1>
            <p class="lead">Temukan layanan terbaik untuk kesehatan mata Anda</p>
        </div>
    </section>

    <!-- LAYANAN SECTION -->
    <section class="py-5 bg-white">
        <div class="container">

            <!-- UGD -->
            <div class="mb-5">
                <h5 class="text-uppercase text-warning fw-bold mb-3">Layanan <span class="text-dark">Unit Gawat Darurat (UGD)</span></h5>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li>• Mata merah tiba-tiba</li>
                            <li>• Mata kemasukan benda asing</li>
                            <li>• Penurunan penglihatan tiba-tiba</li>
                            <li>• Mata nyeri tiba-tiba</li>
                            <li>• Trauma zat kimia pada mata</li>
                            <li>• Trauma tumpul dan tajam pada mata</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li>• Ancaman jiwa</li>
                            <li>• Gangguan pernapasan</li>
                            <li>• Penurunan kesadaran</li>
                            <li>• Nyeri hebat</li>
                            <li>• Gangguan hemodinamik</li>
                            <li>• Memerlukan tindakan segera</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- TINDAKAN -->
            <div class="mb-5">
                <h5 class="text-uppercase text-warning fw-bold mb-3">Layanan <span class="text-dark">Tindakan</span></h5>
                <ul class="list-unstyled">
                    <li>• Tindakan Laser pada Retina (<span class="text-success">Green Laser</span>)</li>
                    <li>• Tindakan Laser pada Katarak Sekunder</li>
                </ul>
            </div>

            <!-- RAWAT JALAN -->
            <div class="mb-5">
                <h5 class="text-uppercase text-warning fw-bold mb-3">Layanan <span class="text-dark">Rawat Jalan</span></h5>
                <ul class="list-unstyled">
                    <li>• Pemeriksaan Glaukoma</li>
                    <li>• Pemeriksaan Saraf Mata</li>
                    <li>• Pemeriksaan Tumor Mata</li>
                    <li>• Pemeriksaan Mata Juling</li>
                    <li>• Pemeriksaan Buta Warna</li>
                    <li>• Pemeriksaan Vitreoretina</li>
                    <li>• Pemeriksaan Kelopak Mata</li>
                    <li>• Pemeriksaan Mata pada Anak</li>
                    <li>• Pemeriksaan Refraksi (<span class="text-info">Kacamata</span>)</li>
                    <li>• Pemeriksaan Katarak dan Bedah Refraktif</li>
                    <li>• Pemeriksaan Kelainan Infeksi dan Imunologi Mata</li>
                </ul>
            </div>

            <!-- OPERASI / BEDAH -->
            <div class="mb-5">
                <h5 class="text-uppercase text-warning fw-bold mb-3">Layanan <span class="text-dark">Operasi / Bedah</span></h5>
                <ul class="list-unstyled">
                    <li>• Operasi Vitrektomi</li>
                    <li>• Operasi Tumor Mata</li>
                    <li>• Operasi Tahi Lalat (<span class="text-info">Nevus</span>)</li>
                    <li>• Operasi Evakuasi Silicon Oil</li>
                    <li>• Operasi Bintitan (<span class="text-info">Hordeolum</span>)</li>
                    <li>• Injeksi Obat ke Dalam Bola Mata</li>
                    <li>• Operasi Kelainan Mata</li>
                    <li>• Operasi Rekonstruksi Kelopak Mata</li>
                    <li>• Operasi Lensa Mata yang Bergeser</li>
                    <li>• Operasi Katarak (<span class="text-info">Phacoemulsifikasi</span>)</li>
                    <li>• Operasi Penurunan Tekanan Bola Mata (<span class="text-info">Trabekulektomi</span>)</li>
                </ul>
            </div>

            <!-- LAYANAN TAMBAHAN -->
            <div class="mb-5">
                <h5 class="text-uppercase text-warning fw-bold mb-3">Layanan <span class="text-dark">Lainnya</span></h5>
                <ul class="list-unstyled">
                    <li>• Laboratorium</li>
                    <li>• Rawat Inap</li>
                    <li>• Farmasi</li>
                    <li>• Optik</li>
                </ul>
            </div>

        </div>
    </section>

@endsection
