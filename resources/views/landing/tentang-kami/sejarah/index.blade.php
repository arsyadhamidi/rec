@extends('landing.layout.master')
@section('title', 'Sejarah Rumah Sakit | RSKM Regina Eye Center')
@section('menuTentangKami', 'active')
@section('menuSejarahRumahSakit', 'active')

@section('content')
    <!-- HERO SECTION -->
    <section class="hero-section text-center text-white d-flex align-items-center"
             style="background: linear-gradient(to right, #f58220, #f4a261); height: 50vh;">
        <div class="container">
            <h1 class="fw-bold display-5">Sejarah Rumah Sakit</h1>
            <p class="lead">Rumah Sakit Khusus Mata Regina Eye Center</p>
        </div>
    </section>

    <!-- TIMELINE SECTION -->
    <section class="timeline-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-uppercase text-primary">Sejarah Regina Eye Center</h2>
                <p class="text-muted">Perjalanan panjang kami dalam memberikan pelayanan kesehatan mata terbaik</p>
            </div>

            <div class="timeline">
                <!-- ITEM 1 -->
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <h4 class="fw-bold text-orange">28 April 2008</h4>
                        <p>Regina Eye Center berdiri sebagai <strong>Klinik Mata</strong> di bawah kepemilikan <strong>PT. Regina Cahaya Insani</strong>.</p>
                    </div>
                </div>

                <!-- ITEM 2 -->
                <div class="timeline-item right">
                    <div class="timeline-content">
                        <h4 class="fw-bold text-orange">28 September 2015</h4>
                        <p>Klinik Mata Regina Eye Center resmi <strong>berubah status menjadi Rumah Sakit Khusus Mata Regina Eye Center</strong>.</p>
                    </div>
                </div>

                <!-- ITEM 3 -->
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <h4 class="fw-bold text-orange">01 Juli 2017</h4>
                        <p>Menjalin kerja sama dengan <strong>BPJS Kesehatan</strong> untuk memberikan layanan kesehatan mata bagi peserta BPJS.</p>
                    </div>
                </div>

                <!-- ITEM 4 -->
                <div class="timeline-item right">
                    <div class="timeline-content">
                        <h4 class="fw-bold text-orange">01 Desember 2019</h4>
                        <p>Menyelenggarakan kegiatan <strong>survey Akreditasi</strong> dengan hasil predikat <strong>Tingkat Dasar (Bintang 1)</strong>.</p>
                    </div>
                </div>

                <!-- ITEM 5 -->
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <h4 class="fw-bold text-orange">07 Desember 2023</h4>
                        <p>Menyelenggarakan <strong>survey Akreditasi</strong> kembali dengan hasil predikat <strong>Tingkat Dasar (Bintang 5)</strong>.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
