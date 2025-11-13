@extends('landing.layout.master')
@section('title', 'Ruang Operasi | RSKM Regina Eye Center')
@section('menuFasilitas', 'active')
@section('menuRuangOperasi', 'active')

@section('content')
    <!-- HERO SECTION -->
    <section class="hero-section text-center text-white d-flex align-items-center"
             style="background: linear-gradient(to right, #f58220, #f4a261); height: 45vh;">
        <div class="container">
            <h1 class="fw-bold display-5">Ruang Operasi</h1>
            <p class="lead">Fasilitas modern untuk tindakan bedah mata yang aman dan nyaman</p>
        </div>
    </section>

    <!-- RUANG OPERASI SECTION -->
    <section class="py-5 bg-light position-relative overflow-hidden">

        <div class="container position-relative">
            <div class="row align-items-center gy-5">
                <!-- Gambar -->
                <div class="col-lg-6">
                    <div class="rounded-4 shadow-sm overflow-hidden">
                        <img src="{{ asset('images/fasilitas/ruang-operasi.jpg') }}"
                             alt="Ruang Operasi Regina Eye Center"
                             class="img-fluid">
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-3 text-warning text-uppercase">Sarana Kamar Bedah</h2>
                    <p class="text-muted mb-4">
                        RSKM Regina Eye Center dilengkapi dengan fasilitas ruang operasi berstandar tinggi yang dirancang
                        untuk mendukung berbagai tindakan bedah mata dengan keamanan, kebersihan, dan kenyamanan pasien
                        sebagai prioritas utama.
                    </p>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled lh-lg">
                                <li>• 4 Unit Kamar Operasi Mayor</li>
                                <li>• 1 Unit Kamar Operasi Minor</li>
                                <li>• 1 Unit Kamar HCU</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled lh-lg">
                                <li>• Ruang sterilisasi modern</li>
                                <li>• Ruang pemulihan pasca operasi</li>
                                <li>• Sistem ventilasi bertekanan positif</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <hr class="my-5">

            <!-- Sarana Penunjang -->
            <div class="text-center">
                <h3 class="fw-bold text-warning text-uppercase mb-3">Sarana Penunjang</h3>
                <p class="text-muted mx-auto" style="max-width: 700px;">
                    Untuk mendukung tindakan operasi yang presisi dan aman, ruang operasi kami dilengkapi dengan sarana
                    penunjang mutakhir seperti <strong>microsurgical system</strong>, <strong>phacoemulsification unit</strong>,
                    <strong>microscope dengan kamera HD</strong>, serta sistem pemantauan vital pasien yang canggih.
                </p>
            </div>
        </div>
    </section>
@endsection
